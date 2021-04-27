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
        session_start();
        $tip=$this->params()->fromPost()['tip'];
        $dizi=$_SESSION['history'];
        $view=new ViewModel();
        $view->setVariable('dizi',$dizi);
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
        $formdata=$this->params()->fromPost();
        $sourcelang=$formdata['source'];
        $targetlang=$formdata['target'];
        $content=$formdata['metin'];

        if($sourcelang=='default'){
        $sourcelang=$this->googlecli->detectLanguage($content);
        }
        

        $translate=$this->googlecli->translate($sourcelang,$targetlang,$content);
        $response=$translate["translations"][0]->translatedText;

        $source=$this->prediscli->getLangbycode($sourcelang); 
        $target=$this->prediscli->getLangbycode($targetlang); 

        $history=['source'=>$source[0],'target'=>$target[0],'content'=>$content,'translate'=>$response,'class'=>'btn btn-light mr-1'];

         $dizi=$_SESSION['history'];
         if(count($dizi)<20)
         {$dizi[]=$history;}

         else{
         unset($dizi[0]);
         $dizi[]=$history;
         $dizi=array_values($dizi);}

         $_SESSION['history']=$dizi;


        return new JsonModel(['response'=>$response]);

    }

else { return $this->redirect()->toUrl('/');}

}


}
