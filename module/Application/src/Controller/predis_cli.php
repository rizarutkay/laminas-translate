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



function getData(){   
    return $this->client->get('languages');
    //return $this->client->get('foo');
    }

    function setData($data){   
        return $this->client->set('languages',$data);
        //return $this->client->get('foo');
        }

}


