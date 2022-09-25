<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Khansia\Generic\Result;
use ZfrPusher\Client\Credentials;
use ZfrPusher\Client\PusherClient;
use ZfrPusher\Service\PusherService;


class UmumController extends \Application\Master\GlobalActionController 
{
    public function __construct($headScript)
    {
        $this->headScript = $headScript;
    }

    public function indexAction()
    {
        $view   = new ViewModel();
        $result = new Result();

        // $today      = gmdate("d M Y ", time()+60*60*7);
        // $view->setVariable('today', $today);
        
        /* get url */
        $uri     = $this->getRequest()->getUri();
        $baseurl = sprintf('//%s', $uri->getHost());

        $this->headScript->appendScript(' var baseURL = "' . $baseurl . '"');        
        $this->headScript->appendFile('/action-js/tampilanantiran-js/action-pilihantrian.js');

        $this->layout("layout/layoutSelect");

        return $view;
    }
    public function umumtampilanAction()
    {
        $view   = new ViewModel();
        $result = new Result();

        $today      = gmdate("d M Y ", time()+60*60*7);
        $view->setVariable('today', $today);
        
        /* get url */
       $uri     = $this->getRequest()->getUri();
        $baseurl = sprintf('//%s', $uri->getHost());
        $getpath =  explode("/",$uri->getPath()); 
        // echo "<pre>";
       
        $decodeid = base64_decode($getpath[3]);
             
            // $data = $this->getRequest()->getRawBody();

            // print_r($data);
       $this->headScript->appendScript(' var baseURL = "' . $baseurl . '"');   
       $this->headScript->appendScript(' var id_poli = "' . $decodeid . '"');            
       $this->headScript->appendFile('/action-js/antrian-js/action-umumtampilan.js');


        $this->layout("layout/layoutUmum");
        return $view;
    }

    
}