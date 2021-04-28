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
function saveFavorite($data,$index)
{
    $dizi=json_decode($this->client->get('favs'),true);
    $dizi[]=array_merge($data,['histid'=>$index]); $lastindex=array_key_last($dizi);
    $this->client->set('favs',json_encode($dizi));
    return $lastindex;
}
function getFavorite()
{
    $dizi=json_decode($this->client->get('favs'),true);
    return $dizi;
}
function getFavoriteHistid($id)
{
    $dizi=json_decode($this->client->get('favs'),true);
    return $dizi[$id]['histid'];
}
function removeFavorite($index)
{
    $dizi=json_decode($this->client->get('favs'),true);
    unset($dizi[$index]);
    $this->client->set('favs',json_encode($dizi));
}

}