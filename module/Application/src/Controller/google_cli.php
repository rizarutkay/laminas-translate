<?php
namespace Google_cli;

use Google_Client;
use Google_Service;
use Google_Service_Translate;
use Google_Service_Translate_TranslateTextRequest;
class Googlecli {

function __construct() {

$this->client = new Google_Client();
$this->client->setAuthConfig(__DIR__.'/client_crd.json');
$this->client->addScope('https://www.googleapis.com/auth/cloud-translation');
$this->client->setAccessType('offline');
$this->service=new Google_Service_Translate($this->client);
}

function supportedLanguages(){   
$lang=$this->service->projects->getSupportedLanguages('projects/angular-stacker-311411/locations/global',['displayLanguageCode'=>'tr']);
$lang=get_object_vars($lang);
return $lang;
}

function translate($source,$target,$content){   
    $translate=new Google_Service_Translate_TranslateTextRequest();
    $translate->setContents([$content]);
    $translate->setTargetLanguageCode($target);
    $translate->setSourceLanguageCode($source);

    //$data=['contents'=>['bu bir elma'],'targetLanguageCode'=>'en'];
    $text=$this->service->projects->translateText('projects/angular-stacker-311411/locations/global',$translate);
    $text=get_object_vars($text);
    return $text;
    }

}