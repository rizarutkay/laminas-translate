<?php

/**
 * @see       https://github.com/laminas/laminas-mvc-skeleton for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mvc-skeleton/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mvc-skeleton/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use Google_cli\Googlecli;
use Predis_cli\Prediscli;

require_once (__DIR__.'/google_cli.php');
require_once (__DIR__.'/predis_cli.php');

class IndexController extends AbstractActionController
{

    public function __construct()
{
    $this->googlecli=new Googlecli();    
    $this->prediscli=new Prediscli();
}

public function search($data)
{
    session_start(); 
    $dizifav=$this->prediscli->getFavorite()?? []; $dizihis=$_SESSION['history'] ?? [];
    $dizi=array_merge($dizifav,$dizihis);

    if(count($dizi)>0){foreach($dizi as $key=>$row){
    $result_array = array_intersect($row, $data);
    if(count($result_array)>0){ 
    if($data['source']==$row['source'] and $data['target']==$row['target'] and $data['content']==$row['content']){ return $row;}}
    }}

    return null;



}
    public function indexAction()
    {   
        $value = $this->prediscli->getLanguages();
        if($value===NULL){
            $lang=$this->googlecli->supportedLanguages();
            $predis->setLanguages(json_encode($lang['languages']));
        }
        $lang=json_decode($this->prediscli->getLanguages());
        return new ViewModel(['languages'=>$lang]);

    }

    public function viewAction()
{
    $request = $this->getRequest();
    if ($request->isPost()) {
        $tip=$this->params()->fromPost()['tip'];
        if($tip=='his'){
        session_start();
        $dizi=$_SESSION['history'];
        }
        elseif($tip=='fav')
        {
          $dizi=$this->prediscli->getFavorite();  
        }
        $view=new ViewModel();
        $view->setVariables(['dizi'=>$dizi,'tip'=>$tip]);
        $view->setTerminal(true);
        return $view;
        
    }
    
    else { return $this->redirect()->toUrl('/');}
}

  public function translateAction()
  {
    session_start();
    $request = $this->getRequest();

    if ($request->isPost()) {
        $sonislem=$_SESSION['lastaction']; $now=new \DateTime('now');
        if($sonislem==null){$interval=11;}

        else{ $interval = date_diff($sonislem, $now); $interval=($interval->format('%s')+($interval->format('%i')*60));}
        
        if($interval>10){
        $formdata=$this->params()->fromPost();
        $sourcelang=$formdata['source'];
        $targetlang=$formdata['target'];
        $content=$formdata['metin'];

        if($sourcelang=='default'){
        $sourcelang=$this->googlecli->detectLanguage($content); }

        $source=$this->prediscli->getLangbycode($sourcelang); 
        $target=$this->prediscli->getLangbycode($targetlang); 

        $data=['source'=>$source[0],'target'=>$target[0],'content'=>$content];
        $dizi=$this->search($data);
        if($dizi===null){ 
        $translate=$this->googlecli->translate($sourcelang,$targetlang,$content);
        $response=$translate["translations"][0]->translatedText;
        $history=['source'=>$source[0],'target'=>$target[0],'content'=>$content,'translate'=>$response,'class'=>'btn btn-light mr-1','favsid'=>null];
        } 
        else{  
        $response=$dizi['translate'];
        $history=['source'=>$dizi['source'],'target'=>$dizi['target'],'content'=>$dizi['content'],'translate'=>$dizi['translate'],'class'=>'btn btn-light mr-1','favsid'=>null]; 
        }
        $status=true;
        $dizi=$_SESSION['history'];
        if(!is_countable($dizi)){$dizi[]=$history;}
        elseif(count($dizi)<20 or empty($dizi)) {$dizi[]=$history;}
        else{
        $dizikey=array_key_first($dizi);
        unset($dizi[$dizikey]);
        $dizi[]=$history;
        }
        
        $_SESSION['history']=$dizi;  $_SESSION['lastaction']=new \DateTime('now'); $_SESSION['count']++;
        }else{$response="10 saniyede 1'den fazla çeviri yapamazsınız"; $status=false;}
        return new JsonModel(['response'=>$response,'status'=>$status]);
    }

else { return $this->redirect()->toUrl('/');}

}
public function updateAction()
{
    $request = $this->getRequest();
    if ($request->isPost()) {
    session_start();
    $formdata=$this->params()->fromPost();
    $tip=$formdata['tip'];
    if($tip=='his-del')
    {
    $index=$formdata['index'];
    $dizi=$_SESSION['history'];
    unset($dizi[$index]); $_SESSION['history']=$dizi;
    }
    if($tip=='fav-del')
    {
    $index=$formdata['index'];
    $histid=$this->prediscli->getFavoriteHistid($index);
    $_SESSION['history'][$histid]['class']='btn btn-light mr-1';
    $this->prediscli->removeFavorite($index);

    }
    elseif($tip=='clear-all')
    {
        unset($_SESSION['history']);        
    }

    elseif($tip=='history-save')
    {
        $index=$formdata['index'];
        $data=$_SESSION['history'][$index];
        $favs=$this->prediscli->saveFavorite($data,$index);
        $data['favsid']=$favs; $data['class']='btn btn-success mr-1'; $_SESSION['history'][$index]=$data;
    }
    elseif($tip=='history-unsave')
    {
        $index=$formdata['index'];
        $data=$_SESSION['history'][$index];
        $favs=$this->prediscli->removeFavorite($data['favsid']);
        $data['favsid']=null; $data['class']='btn btn-light mr-1'; $_SESSION['history'][$index]=$data;
    }

    return new JsonModel(['response'=>$tip]);
    }

    else { return $this->redirect()->toUrl('/');}
}


}
