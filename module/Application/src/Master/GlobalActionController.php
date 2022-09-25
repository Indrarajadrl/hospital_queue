<?php

namespace Application\Master;
use Khansia\Mvc\Controller;
use Khansia\Access\User;
use Khansia\Access\Session;

class GlobalActionController extends \Khansia\Mvc\Controller {

    const POST  = 0;
    const GET   = 1;      
    const MINUTES_IDLE_SESSION = 86400;
    
    protected $_session = null;

    /**
     * 
     * 
     * ******* Function list ********* 
     * contoh pemanggilan fungsi di library yang extends dari \Khansia\Mvc\Controller
     * 
     * load configuration : $this->getConfig();
     *   
     * load database : $this->getDb(); 
     * 
     * get/post data : $this->getData();
     * 
     * output data : $this->getOutput();
     * 
     * 
     * ** validasi data **
     * 
     * inputan tidak boleh kosong : $this->isFieldMandatory($string, $columnName);
     * 
     * ** authentikasi user by json web token **
     * 
     * cek token: $this->findJwtToken();
     * 
     * generate token:$this->generateJwtToken();
     * 
     * response data: $this->createResponse();
     * 
     *  ** character **
     * 
     * parsing data untuk fingerprints : $this->ParseDataFinger($data, $param1, $param2);
     * 
     * format huruf kecil menjadi besar depannya saja : $this->isUpper($string);
     * 
     * format huruf kecil menjadi besar all : $this->isUpperAll($string);
     * 
     * subsrting character : $this->subStrText($string, $longString);
     * 
     * convert decimal jadi format uang :  $this->convertMoney($string);
     * 
     *  ** date **
     * 
     * get date gmt + 7 : $this->dates();
     * 
     * format date to Y-m-d H:i:s : $this->formatYMD($date);
     * 
     * format date to d M Y : $this->formatDMY($date);
     * 
     * 
    **/

    /* *
        dibawah buat custom function gan
     * */

    protected function getSession() {
        try {
            if ($this->_session == null) {
				
                $session = new Session('KHANSIA', Session::MODE_DATABASE,
                array(
                        'adapter'   => $this->getDb(),
                        'table'     => 'user_data_session',
                        'lifetime'  => self::MINUTES_IDLE_SESSION, 
                        'secure'    => false
                ));
					
                $session->start(false);
                $this->_session = $session;
            }

            return $this->_session;
        } catch (\Exception $ex) {
            //error_log($ex->getMessage());
            return false;
        }
    }

    protected function isHaveAccess() {
        //$this->isLoggedIn();
        $controllerName = $this->params('controller');
        $actionName     = $this->params('action');
        $haveAccess     = false;
        $session        = $this->getSession();

        $roleAction     = $session->get('role_action');

        if($roleAction) {
            foreach($roleAction as $key => $value) {
                if($controllerName == $value['aa_controller']) {
                    if($actionName == $value['aa_action']) {
                        $haveAccess = true;
                    }
                }
            }
        }

        if(!$haveAccess) {
            return $this->redirect()->toRoute('forbidden');
        }
    }

    public function getDb($module = 'primary') {
		
        $adapter = parent::getDb($module);
		
        /* set date format as mysql standard */
        $formats = array(
          'NLS_TIME_FORMAT' => "HH24:MI:SS",
          'NLS_DATE_FORMAT' => "YYYY-MM-DD HH24:MI:SS",
          'NLS_TIMESTAMP_FORMAT' => "YYYY-MM-DD HH24:MI:SS",
          'NLS_TIMESTAMP_TZ_FORMAT' => "YYYY-MM-DD HH24:MI:SS TZH:TZM"
        );
        
        return $adapter;
    }

    /* is login belum bisa di pake sabar yah gan */
    protected function isLoggedIn($anum=null) {
        $actionName     = $this->params('action');
        $controllerName = $this->params('controller');

        if(($controllerName != 'Application\Controller\ApiController' && $controllerName != 'Application\Controller\JsondataController')){
            $this->headScript->appendScript(' var actionControl = "' . $actionName . '"');
        }
        // print_r($actionName);die;
        $this->headScript->appendScript(' var actionControl = "' . $actionName . '"');        
        $this->headScript->appendFile('/action-js/global-js/javaScriptGlobal.js');

        try {
            $session = $this->getSession();
            if($session) {
                $owner = $session->owner();

                if (isset($owner)) {
                    if ($owner != null) {
                        $this->layout()->setVariable('session', $session);
                        return $session;
                    }
                }
                return $this->redirect()->toRoute('login');
            } else {
                return $this->redirect()->toRoute('login');
            }
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('login');
        }
    }
}
