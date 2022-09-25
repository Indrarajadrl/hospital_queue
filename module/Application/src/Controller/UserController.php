<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Master;
use Zend\View\Model\ViewModel;

class UserController extends \Application\Master\GlobalActionController
{
    public function __construct($headScript)
    {
        $this->headScript = $headScript;
    }

    public function indexAction() {
        $result = new Result(0, 403, 'invalid_controller');
        header('Content-Type: application/json');
        return $this->redirect()->toRoute('login');
    }

    public function loginAction(){
        try {
           
        
            $view = new ViewModel();

            $session        = $this->getSession();
            $token_keamanan = md5(uniqid(mt_rand(), true));
            
            error_log('$token_keamanan: ' . $token_keamanan);
            
            if($session) {
                $session->put(null, array('token_keamanan' => $token_keamanan));
                $message = $session->get('message');
            } else {
                $message = 'Tidak dapat mengakses database. Mohon ulangi beberapa saat lagi.';
            }

            $view->setVariables(array(
                'message'           => $message,
                'token_keamanan'    => $token_keamanan
            ));
            $uri     = $this->getRequest()->getUri();
            $baseurl = sprintf('//%s', $uri->getHost());
            $getpath =  explode("/",$uri->getPath()); 
            // echo "<pre>";
    
            
            $this->headScript->appendScript(' var baseURL = "' . $baseurl . '"');      
            $this->headScript->appendFile('/action-js/antrian-js/action-admin.js');
            //$view->setTerminal(true);
            
            $this->layout("layout/layout");
            
            return $view;
        } catch (Exception $ex) {
            error_log($ex->getMessage());
            return $this->redirect()->toRoute('login');
        }
    }
 
    public function authenticateAction(){

        try {
            $uri     = $this->getRequest()->getUri();
            $baseurl = sprintf('//%s', $uri->getHost());

            $post    = $this->getRequest()->getPost();
            $session = $this->getSession();
        
            // if (strlen('' . $post['token_keamanan']) <= 0 || $post['token_keamanan'] <> $session->get('token_keamanan')) {
            //     $message = htmlspecialchars('Token login tidak sesuai. Mohon ulangi.', ENT_QUOTES, 'UTF-8');
            //     $session->put(null, array('message' => $message));
            //     return $this->redirect()->toRoute('login');
            // }

            $username = $post['username'];
            $password = $post['passwd'];

            $storage = \Khansia\Access\User\Storage::factory($this->getDb(), $this->getConfig());
            $user    =  new \Khansia\Access\User($storage);
                
            if($user->load($username,  \Khansia\Access\User\Storage::LOADBY_CODE)){ // sukses load then

                $authResult = $user->authenticate($password, null, \Khansia\Access\User::RETRIES_TRUE);

                if($authResult->code == $authResult::CODE_SUCCESS) {

                    $session->owner($user->id);
                    
                    /* get access role */
                    $access = $user->loadAccess($user->id);
                    
                    $accessArray = array();
                    
                    foreach($access->data as $data=> $val){

                        if($val['access_status'] == 'TRUE'){
                            $newStat = true;
                        }else{
                            $newStat = false;
                        }
                        $accessArray[$val['access_code']] = $newStat;
                    
                    }

                    $session->put(null, array(
                        'baseurl'           => $baseurl,
                        'user_id'           => $user->id,
                        'usernamed'         => $user->username,
                        'passwd'            => $user->password,
                        'name'              => $user->name,
                        'role'              => $user->role,
                        'status'            => $user->status,
                        'deviceid'          => $user->deviceid,
                        'token'             => $user->token,
                        'retries'           => $user->retries,
                        'create_dtm'        => $user->create_dtm,
                        'access'            => $accessArray,
                        'role_code'         => $access->data[0]['role_code'],
                        'id_poli'           => $user->id_poli,
                        
                    ));
                    $session->flush();
                    if($user->username == 'admin'){
                        return $this->redirect()->toRoute('dashboardsuper');
                    }
                    else{
                        return $this->redirect()->toRoute('dashboardadmin');
                    }
                    /* direct data */
                }else{

                    switch($authResult->code) {
                        case \Khansia\Access\User::CODE_AUTH_INVALID:
                            $authMessage = 'User tidak valid';
                            break;
                        case \Khansia\Access\User::CODE_AUTH_SUSPEND:
                            $authMessage = 'User ditangguhkan';
                            break;
                        case \Khansia\Access\User::CODE_AUTH_LOCKED:
                            $authMessage = 'User tidak aktif';
                            break;
                        case \Khansia\Access\User::CODE_AUTH_FAILED:
                            $authMessage = 'Password tidak sesuai';
                            break;
                    }

                    $message = htmlspecialchars($authMessage, ENT_QUOTES, 'UTF-8');

                    $session->put(null, array('message' => $message));
                    
                    return $this->redirect()->toRoute('login');
                }
            }else{
                
                $session = $this->getSession();
                
                $session->put(null, array('message' => "incorrect username or passowrd "));
    
                return $this->redirect()->toRoute('login');
            }

        } catch (\Exception $ex) {
            $session = $this->getSession();
            $message = htmlspecialchars($ex->getMessage(), ENT_QUOTES, 'UTF-8');
            
            $session->put(null, array('message' => $message));

            return $this->redirect()->toRoute('login');
        }        
    }

    public function logoutAction() {
        try {
            $session = $this->getSession();
            $session->stop();
            
            return $this->redirect()->toRoute('login');
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('login');
        }
    }


}

