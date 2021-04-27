<?php
namespace Predis_cli;

use Predis;

class Prediscli {

function __construct() {

    $this->client = new Predis\Client([
        'scheme' => 'tcp',
        'host'   => 'redis',
        'port'   => 6379,
    ]);
}



function getLanguages(){   
    return $this->client->get('languages');

    }

function setLanguages($data){   
   $this->client->set('languages',$data);
   $data=json_decode($data,true);
   foreach($data as $key => $value) {
   $langcodes[$value["languageCode"]]= $value['displayName']; }
   $this->client->hmset("getlangbycode", $langcodes);

}
function getLangbycode($code){
    return $this->client->hmget("getlangbycode",$code);
}


}