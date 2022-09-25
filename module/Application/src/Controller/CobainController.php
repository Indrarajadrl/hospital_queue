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




class CobainController extends \Application\Master\GlobalActionController 
{
    
    public function __construct($headScript)
    {
        $this->headScript = $headScript;
    }

    public function indexAction()
    {
        echo 'forbidden';die;
    }
    public function dasboardAction()
    {
		$this->isLoggedIn();
        $view   = new ViewModel();
        $result = new Result();
        
      
       $uri     = $this->getRequest()->getUri();
       $baseurl = sprintf('//%s', $uri->getHost());
       


       $this->headScript->appendScript(' var baseURL = "' . $baseurl . '"');        
       $this->headScript->appendFile('/action-js/antrian-js/action-dasboaard.js');
    //    $this->headScript->appendFile('/tamplateadmin/js/demo/chart-bar-demo.js');
    //    $this->headScript->appendFile('/tamplateadmin/js/demo/chart-area-demo.js');
       $this->headScript->appendFile('/tamplateadmin/js/demo/chart-pie-demo.js');

        $this->layout("layout/layoutAdmin");
        return $view;
    }
    public function cobainAction()
    {
        
        
        $view   = new ViewModel();
        $result = new Result();
        
        /* get url */
        $uri     = $this->getRequest()->getUri();
        $baseurl = sprintf('//%s', $uri->getHost());
        $getpath =  explode("/",$uri->getPath()); 
        // echo "<pre>";

        
        $this->headScript->appendScript(' var baseURL = "' . $baseurl . '"');      
        $this->headScript->appendFile('/action-js/demo-js/demo.js');

        $this->layout("layout/layoutDemo");
        return $view;
    }
   
}
