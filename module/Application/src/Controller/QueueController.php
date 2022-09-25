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

class QueueController extends \Application\Master\GlobalActionController 
{
    public function __construct($headScript)
    {
        $this->headScript = $headScript;
    }

    public function indexAction()
    {
        echo 'forbidden';die;
    }
    public function berandaAction()
    {
	
        $view   = new ViewModel();
        $result = new Result();
        
        /* get url */
        $uri     = $this->getRequest()->getUri();
        $baseurl = sprintf('//%s', $uri->getHost());

        // $this->headScript->appendScript(' var baseURL = "' . $baseurl . '"');        
        // $this->headScript->appendFile('/action-js/index-js/action-listdata.js');

        $this->layout("layout/layoutQueue");
        return $view;
    }

    public function daftarpasienAction()
    {
        $view   = new ViewModel();
        $result = new Result();
        
        /* get url */
        $uri     = $this->getRequest()->getUri();
        $baseurl = sprintf('//%s', $uri->getHost());

        $this->headScript->appendScript(' var baseURL = "' . $baseurl . '"');        
        $this->headScript->appendFile('/action-js/antrian-js/action-daftarpasien.js');

        $this->layout("layout/layoutQueue");
        return $view;
        
    }
    public function resumecekpasienAction()
    {
	
        $view   = new ViewModel();
        $result = new Result();
        
        /* get url */
        $uri     = $this->getRequest()->getUri();
        $baseurl = sprintf('//%s', $uri->getHost());

    

        $this->headScript->appendScript(' var baseURL = "' . $baseurl . '"');        
        $this->headScript->appendFile('/action-js/antrian-js/action-datapasien.js');
        

        $this->layout("layout/layoutQueue");
        return $view;
        
    }
    public function datapasienAction()
    {
	
        $view   = new ViewModel();
        $result = new Result();
        
        /* get url */
        $uri     = $this->getRequest()->getUri();
        $baseurl = sprintf('//%s', $uri->getHost());
        

        $baseurl = sprintf('//%s', $uri->getHost());    
        $getpath =  explode("/",$uri->getPath()); 
        $decodeid = base64_decode($getpath[3]);

        $this->headScript->appendScript(' var baseURL = "' . $baseurl . '"');        
        $this->headScript->appendScript(' var id = "' . $decodeid . '"');        
        $this->headScript->appendFile('/action-js/antrian-js/action-datapasien.js');
        
        $this->layout("layout/layoutQueue");
        return $view;
        
    }
    public function dokterAction()
    {
	
        $view   = new ViewModel();
        $result = new Result();
        
        /* get url */
        $uri     = $this->getRequest()->getUri();
        $baseurl = sprintf('//%s', $uri->getHost());

        $this->headScript->appendScript(' var baseURL = "' . $baseurl . '"');        
        $this->headScript->appendFile('/action-js/antrian-js/action-dokter.js');

        $this->layout("layout/layoutQueue");
        return $view;
    }
    public function poliAction()
    {
	
        $view   = new ViewModel();
        $result = new Result();
        
        /* get url */
        $uri     = $this->getRequest()->getUri();
        $baseurl = sprintf('//%s', $uri->getHost());

        $this->headScript->appendScript(' var baseURL = "' . $baseurl . '"'); 
        $this->headScript->appendScript(' var id = null');            
        $this->headScript->appendFile('/action-js/antrian-js/action-poli.js');

        $this->layout("layout/layoutQueue");
        return $view;
    }
    public function datapoliAction()
    {
		
        $view   = new ViewModel();
        $result = new Result();

        $id     = $_GET["id"];
        // print_r($id);die;
        
        /* get url */
        $uri     = $this->getRequest()->getUri();
        $baseurl = sprintf('//%s', $uri->getHost());


        $this->headScript->appendScript(' var baseURL = "' . $baseurl . '"');        
        $this->headScript->appendScript(' var id = "' . $id. '"');        
        $this->headScript->appendFile('/action-js/antrian-js/action-poli.js');

        $this->layout("layout/layoutQueue");
        return $view;
    }
   
    public function cekdatapasienAction()
    {
		
        $view   = new ViewModel();
        $result = new Result();
        
        /* get url */
        $uri     = $this->getRequest()->getUri();
        $baseurl = sprintf('//%s', $uri->getHost());

        $this->headScript->appendScript(' var baseURL = "' . $baseurl . '"');        
        $this->headScript->appendFile('/action-js/antrian-js/action-cekdatapasien.js');

        $this->layout("layout/layoutQueue");
        return $view;
    }
    public function cetakantrianAction()
    {
	
        $view   = new ViewModel();
        $result = new Result();
        /* get url */
        $uri     = $this->getRequest()->getUri();
        //echo "<pre>";
        //print_r($uri);die;    
        $baseurl = sprintf('//%s', $uri->getHost());    
        $getpath =  explode("/",$uri->getPath()); 
        $decodeid = base64_decode($getpath[3]);
        
        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $param    	= new \Application\Model\Param($storage);

        $id = $decodeid ;    
        // $iddok = $decodeiddok ;    
         

        /* generate counter number */
        $codeCounter = $param->loadUser($id);

        //  print_r($codeCounter);die;
        foreach($codeCounter->data as $result) {
       
        }
        //print_r($codeCounter);die;
        $view->setVariable('no_rekam_medis', $result['no_rekam_medis']);
        $view->setVariable('nama', $result['nama']);
        $view->setVariable('nama_dokter', $result['nama_dokter']);
        $view->setVariable('kode_poli', $result['nama_poli']);
        $view->setVariable('no_antrian', $result['no_antrian']);
        $view->setVariable('no_antrian', $result['no_antrian']);
        $view->setVariable('kode_dokter', $result['kode_dokter']);
        $view->setVariable('sisa_antrian', $result['sisa_antrian']);

       // print_r($result);die;
        $this->headScript->appendScript(' var baseURL = "' . $baseurl . '"');        
        $this->headScript->appendScript(' var no_rekam_medis_cetak = "' . $result['no_rekam_medis'] . '"');        
        $this->headScript->appendScript(' var nama_pasien_cetak = "' . $result['nama'] . '"');        
        $this->headScript->appendScript(' var nama_dokter_cetak = "' . $result['nama_dokter'] . '"');        
        $this->headScript->appendScript(' var kode_poli_cetak = "' . $result['nama_poli'] . '"');        
        $this->headScript->appendScript(' var no_antrian_cetak = "' . $result['no_antrian'] . '"');        
        $this->headScript->appendScript(' var kode_dokter_cetak = "' . $result['kode_dokter'] . '"');      
        $this->headScript->appendScript(' var sisa_antrian = "' . $result['sisa_antrian'] . '"');        
        $this->headScript->appendFile('/action-js/antrian-js/action-cetak.js');

        $this->layout("layout/layoutQueue");
        return $view;
    }
   
  
}
