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


class AdminController extends \Application\Master\GlobalActionController 
{
    
    public function __construct($headScript)
    {
        $this->headScript = $headScript;
    }

    public function indexAction()
    {
        echo 'forbidden';die;
    }
  
    public function antrianadminAction()
    {
        $this->isLoggedIn();
        
        
        $view   = new ViewModel();
        $result = new Result();
        $session  = $this->getSession(); 
        // echo "<pre>";
       
        // print_r($session->get('usernamed'));

        /* get url */
        $uri     = $this->getRequest()->getUri();
        $baseurl = sprintf('//%s', $uri->getHost());
        $getpath =  explode("/",$uri->getPath()); 
        // echo "<pre>";

        
        $this->headScript->appendScript(' var baseURL = "' . $baseurl . '"');
        $this->headScript->appendScript(' var id_poli = "' . $session->get('id_poli') . '"');

        $this->headScript->appendFile('/action-js/antrian-js/action-admin.js');
        $this->layout()->name = $session->get('name');
        $this->layout("layout/layoutAdmin");
        return $view;
    }
  

 
    public function antrianterlewatAction()
    {
		$this->isLoggedIn();
        $view   = new ViewModel();
        $result = new Result();
        $session  = $this->getSession();
        /* get url */
        $uri     = $this->getRequest()->getUri();
        $baseurl = sprintf('//%s', $uri->getHost());

        $this->headScript->appendScript(' var baseURL = "' . $baseurl . '"');        
        $this->headScript->appendScript(' var id_poli = "' . $session->get('id_poli') . '"');
        $this->headScript->appendFile('/action-js/antrian-js/action-kelolaantrian.js');
        $this->layout()->name = $session->get('name');
        $this->layout("layout/layoutAdmin");
        return $view;
    }
    public function antrianunregisterAction()
    {
		$this->isLoggedIn();
        $view   = new ViewModel();
        $result = new Result();
        $session  = $this->getSession();
        
        /* get url */
        $uri     = $this->getRequest()->getUri();
        $baseurl = sprintf('//%s', $uri->getHost());

        $this->headScript->appendScript(' var baseURL = "' . $baseurl . '"');       
        $this->headScript->appendScript(' var id_poli = "' . $session->get('id_poli') . '"'); 
        $this->headScript->appendFile('/action-js/antrian-js/action-kelolaantrianunreg.js');
    
        $this->layout()->name = $session->get('name');
        $this->layout("layout/layoutAdmin");
        return $view;
    }

}

   

