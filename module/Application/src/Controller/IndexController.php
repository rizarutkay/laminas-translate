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
use Google_Client;
use Google_Service;
use Google_Service_Translate;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        // $client = new Google_Client();
        // $client->setAuthConfig(__DIR__.'/client_crd.json');
        // $client->addScope('https://www.googleapis.com/auth/cloud-translation');
        // $client->setAccessType('offline');

        // $var=new Google_Service_Translate($client);



        // $lang=$var->projects->getSupportedLanguages('projects/angular-stacker-311411/locations/global');
        // $lang=get_object_vars($lang);
        // var_dump($lang);
        // return new ViewModel();
    }
}
