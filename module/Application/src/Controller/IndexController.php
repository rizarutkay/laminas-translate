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
//use Predis;
use Google_cli\Googlecli;
use Predis_cli\Prediscli;
//require '../../../../Predis/Autoloader.php';
//require_once __DIR__ . "/../PHP/functions.php";
//require_once __DIR__ . '/../../../../vendor/predis/predis/autoload.php';
//Predis\Autoloader::register();
require_once (__DIR__.'/google_cli.php');
require_once (__DIR__.'/predis_cli.php');

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $predis=new Prediscli();
        $value = $predis->getData();
        if($value===NULL){
            $google=new Googlecli();
            $lang=$google->supportedLanguages();
            $predis->setData(json_encode($lang['languages']));
        }
        $lang=json_decode($predis->getData());

        //  $google=new Googlecli();
        //  $lang=$google->supportedLanguages();
        //  $asd=$google->translate();
        //  var_dump($asd);
        return new ViewModel(['languages'=>$lang]);

    }

    public function viewAction()
    {

//Predis\Autoloader::register();

//$client = new Predis\Client();


$predis=new Prediscli();
$value = json_decode($predis->getData(),true);
var_dump($value);

// $client = new Predis\Client([
//     'scheme' => 'tcp',
//     'host'   => 'redis',
//     'port'   => 6379,
// ]);

// $client->set('foo', 'anan');
// $value = $client->get('foo');
// var_dump($value);

// $dizi=["firstName" => "Foo", "lastName" => "Bar"];
// $client->hmset('user_details',$dizi);
//get all as an Associate Array
// $value=$client->hgetall('user_details');
// var_dump($value);



  }

  public function translateAction()
  {
    $request = $this->getRequest();

    if ($request->isPost()) {
        $formdata=$this->params()->fromPost();
        $sourcelang=$formdata['source'];
        $targetlang=$formdata['target'];
        $content=$formdata['metin'];

        if($sourcelang=='default'){$sourcelang=NULL;}
        $google=new Googlecli();

        $translate=$google->translate($sourcelang,$targetlang,$content);
        //var_dump($translate["translations"][0]->translatedText);
        $dizi=['message'=>$translate["translations"][0]->translatedText];
         
        return new JsonModel($dizi);
    }

else{
    return new JsonModel([
        'status' => 'SUCCESS',
        'message'=>'Here is your data',
        'data' => [
            'full_name' => 'John Doe',
            'address' => '51 Middle st.'
        ]
    ]);
}

  }


}
