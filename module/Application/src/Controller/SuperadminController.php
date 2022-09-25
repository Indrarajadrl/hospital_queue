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


class SuperadminController extends \Application\Master\GlobalActionController 
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

        $this->layout("layout/layoutSuperAdmin");
        return $view;
    }
 
    public function registrasidokterAction()
    {
		$this->isLoggedIn();
        $view   = new ViewModel();
        $result = new Result();
        
        /* get url */
        $uri     = $this->getRequest()->getUri();
        $baseurl = sprintf('//%s', $uri->getHost());

        $this->headScript->appendScript(' var baseURL = "' . $baseurl . '"');        
        $this->headScript->appendFile('/action-js/antrian-js/action-registrasidokter.js');

        $this->layout("layout/layoutSuperAdmin");
        return $view;
    }
    public function registrasialldokAction()
    {
		$this->isLoggedIn();
        $view   = new ViewModel();
        $result = new Result();
        
        /* get url */
        $uri     = $this->getRequest()->getUri();
        $baseurl = sprintf('//%s', $uri->getHost());

        $this->headScript->appendScript(' var baseURL = "' . $baseurl . '"');        
        $this->headScript->appendFile('/action-js/antrian-js/action-registrasialldok.js');

        $this->layout("layout/layoutSuperAdmin");
        return $view;
    }

 
  
    public function kelolapoliAction()
    {
		$this->isLoggedIn();
        $view   = new ViewModel();
        $result = new Result();
        
        /* get url */
        $uri     = $this->getRequest()->getUri();
        $baseurl = sprintf('//%s', $uri->getHost());

        $this->headScript->appendScript(' var baseURL = "' . $baseurl . '"');        
        $this->headScript->appendFile('/action-js/antrian-js/action-kelolapoli.js');

        $this->layout("layout/layoutSuperAdmin");
        return $view;
    }
    public function keloladokterAction()
    {
		$this->isLoggedIn();
        $view   = new ViewModel();
        $result = new Result();
        
        /* get url */
        $uri     = $this->getRequest()->getUri();
        $baseurl = sprintf('//%s', $uri->getHost());

        $this->headScript->appendScript(' var baseURL = "' . $baseurl . '"');        
        $this->headScript->appendFile('/action-js/antrian-js/action-keloladokter.js');

        $this->layout("layout/layoutSuperAdmin");
        return $view;
    }
    public function kelolapasienAction()
    {
		$this->isLoggedIn();
        $view   = new ViewModel();
        $result = new Result();
        
        /* get url */
        $uri     = $this->getRequest()->getUri();
        $baseurl = sprintf('//%s', $uri->getHost());

        $this->headScript->appendScript(' var baseURL = "' . $baseurl . '"');        
        $this->headScript->appendFile('/action-js/antrian-js/action-kelolapasien.js');

        $this->layout("layout/layoutSuperAdmin");
        return $view;
    }
    public function kelolaruanganAction()
    {
		$this->isLoggedIn();
        $view   = new ViewModel();
        $result = new Result();
        
        /* get url */
        $uri     = $this->getRequest()->getUri();
        $baseurl = sprintf('//%s', $uri->getHost());

        $this->headScript->appendScript(' var baseURL = "' . $baseurl . '"');        
        $this->headScript->appendFile('/action-js/antrian-js/action-kelolaruang.js');

        $this->layout("layout/layoutSuperAdmin");
        return $view;
    }
    public function tableAction()
    {
		$this->isLoggedIn();
        $view   = new ViewModel();
       // $result = new Result();
        
        /* get url */
       // $uri     = $this->getRequest()->getUri();
       // $baseurl = sprintf('//%s', $uri->getHost());

        //$this->headScript->appendScript(' var baseURL = "' . $baseurl . '"');        
        //$this->headScript->appendFile('/action-js/index-js/action-listdata.js');

        $this->layout("layout/layoutAdmin");
        return $view;
    }

    public function tampilanumumAction()
    {
		$this->isLoggedIn();
        $view   = new ViewModel();
        $result = new Result();

        $today      = gmdate("d M Y ", time()+60*60*7);
        $view->setVariable('today', $today);
        
        /* get url */
       $uri     = $this->getRequest()->getUri();
        $baseurl = sprintf('//%s', $uri->getHost());

       $this->headScript->appendScript(' var baseURL = "' . $baseurl . '"');        
       $this->headScript->appendFile('/action-js/antrian-js/action-umum.js');

        $this->layout("layout/layoutAdmin");
        return $view;
    }
}
