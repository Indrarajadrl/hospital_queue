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

class IndexController extends \Application\Master\GlobalActionController 
{
    public function __construct($headScript)
    {
        $this->headScript = $headScript;
    }

    public function indexAction()
    {
        $view   = new ViewModel();
        $result = new Result();
        /* ini hanya contoh return dari factory IndexControllerFactory gan, dani tamvan */
        $userSession = $this->getSession();
        $owner       = $userSession->owner();

        if($owner){

            /* session data */
            $sessionArray = array(
                'baseurl'       => $userSession->get('baseurl'),
                'user_id'       => $userSession->get('user_id'),
                'usernamed'     => $userSession->get('usernamed'),
                'passwd'        => $userSession->get('passwd'),
                'name'          => $userSession->get('name'),
                'role'          => $userSession->get('role'),
                'status'        => $userSession->get('status'),
                'deviceid'      => $userSession->get('deviceid'),
                'token'         => $userSession->get('token'),
                'retries'       => $userSession->get('retries'),
                'create_dtm'    => $userSession->get('create_dtm'),
                'access'        => $userSession->get('access'),
                'role_code'     => $userSession->get('role_code'),
            );

            //print_r($result);die;
            $view->setVariable('dataa', $sessionArray);

            $baseurl = 'dani';
            $this->headScript->appendScript(' var baseURL = "' . $baseurl . '"');        
            $this->headScript->appendFile('/action-js/index-js/action-index.js');

            return $view;
        }else{
            return $this->redirect()->toRoute('login');
        }
    }
}
