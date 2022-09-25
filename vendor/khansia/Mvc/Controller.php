<?php
namespace Khansia\Mvc;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Firebase\JWT\JWT;
use Zend\EventManager\EventManagerInterface;

class Controller extends \Zend\Mvc\Controller\AbstractActionController {

  const POST = 0;
  const GET = 1;

  protected $_db = null;
  protected $_config = array();
  
  public function onDispatch(\Zend\Mvc\MvcEvent $epent) {
	
    /* get config! */
    $this->_config = $epent->getApplication()->getServiceManager()->get('config');
    
    /* apply php settings */
    if (isset($this->_config['php'])) {
		$php = $this->_config['php'];
		foreach ($php as $key => $value) {
			ini_set($key, $value);
			if ($key == 'error_reporting') {
				error_reporting($value);
			}
		}
    }

    /* go dispatch! */
    return parent::onDispatch($epent);
    }

	public function getConfig() {    
		return $this->_config;
	}
  
	public function isFieldMandatory($param = null, $colNamed = null){
   
        if(!$param){
            $result = new \Khansia\Generic\Result(0, 1, 'Fields "'.$colNamed.'" Mandatory ');
            $json   = $result->toJson();
        
            header('Content-Type: application/json');
            echo($json);
		    die();		
		}else{
		  return $param;
		}
	}

	public function getOutput($json) {
		$response = $this->getResponse();
		$response->getHeaders()->addHeaderLine('Content-Type', 'application/json');
		$response->setContent($json);
		return $response;
	}

	public function getData($mode = self::POST, $decode = true) {

		if ($mode == self::GET) {
			if ($data = $this->getRequest()->getQuery('data')) {
		  
				return $decode ? json_decode($data, true) : $data;
			
			}
		} else {
			if ($data = $this->getRequest()->getPost('data')) {
			$temp = '';
			if ($decode) {
				$data = str_replace(array("\r", "\n"), '', $data);
				if ($temp = json_decode($data, true)) {
					return $temp;
				} else {
					return json_decode(urldecode($data), true);
				}
			} else {
				return $data;
			}
		}
	  }
	  return 0;
	}

	public function getDb($module = 'primary') {
		//$r = $this->getApplication()->getServiceManager()->get('Config');
		/* not already loaded? */
		if ($this->_db == null) {

		  /* get current config */
		  $temp = $this->_config;
		  //print_r($temp);die;
		  /* primary connection set? */
		  if (isset($temp['databases'][$module])) {

			/* get adapter config and schema list */
			$config = $temp['databases'][$module];
			if (isset($config['schemas'])) {
			  $schemas = $config['schemas'];
			  unset($config['schemas']);
			} else {
			  $schemas = array();
			}
			//print_r($config);die;
			/* remove unnecessary part from module / namespace */
			$part = explode('\\', strtolower($module));
			if ($part !== false) {
			  $min = 0;
			  $max = count($part) - 1;
			  if ($part[0] == 'khansia') { $min = 1; }
			  if ($max > ($min + 1)) { $max = $min + 1; }
			  $module = $part[$min] . '\\' . $part[$max];
			}

			/* module found */
			if (isset($schemas[$module])) {
			  $config['schema'] = $schemas[$module];
			}

			/* debug */
			//echo($module);

            /* Oci8? */
			if ($config['driver'] == 'Oci8') {
			  /* Oci8: generate TNS */
			  $config['connection'] = '(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP) (HOST = '.$config['host'].') (PORT = '.(isset($config['port']) ? $config['port'] : '1521').')) (CONNECT_DATA = (SERVICE_NAME = '.$config['schema'].')))';
			  $config['character_set'] = 'AL32UTF8';
			} elseif($config['driver'] == 'Pdo') {
			  /* MySQL: set dsn */
			  $config['dsn'] = 'mysql:dbname=' . $config['schema'] . ';host=' . $config['host'];
			}else{
        $config['dsn'] = 'pgsql:dbname=' . $config['schema'] . ';host=' . $config['host'];
      }
           
			/* return adapter */
			return new \Zend\Db\Adapter\Adapter($config);

		  } /* config set */

		} /* db null */

		return $this->_db;
	  }

  

    /**
     * @var Integer $httpStatusCode Define Api Response code.
     */
    public $httpStatusCode = 200;

    /**
     * @var array $apiResponse Define response for api
     */
    public $apiResponse;

    /**
     *
     * @var type string 
     */
    public $token;

    /**
     *
     * @var type Object or Array
     */
    public $tokenPayload;

    /**
     * set Event Manager to check Authorization
     * @param \Zend\EventManager\EventManagerInterface $events
     */
    public function setEventManager(EventManagerInterface $events)
    {
        parent::setEventManager($events);
        $events->attach('dispatch', array($this, 'checkAuthorization'), 10);
    }

    /**
     * This Function call from eventmanager to check authntication and token validation
     * @param type $event
     * 
     */
    public function checkAuthorization($event)
    {
        $request = $event->getRequest();
        $response = $event->getResponse();
        $isAuthorizationRequired = $event->getRouteMatch()->getParam('isAuthorizationRequired');
        $config = $event->getApplication()->getServiceManager()->get('Config');
        $event->setParam('config', $config);

        //print_r($isAuthorizationRequired);die;
        if (isset($config['ApiRequest'])) {
            $responseStatusKey = $config['ApiRequest']['responseFormat']['statusKey'];
            //print_r($responseStatusKey);die;
            if (!$isAuthorizationRequired) {
                return;
            }
            $jwtToken = $this->findJwtToken($request);
            //print_r($config);die;
            if ($jwtToken) {
                $this->token = $jwtToken;
                $this->decodeJwtToken();
                if (is_object($this->tokenPayload)) {
                    return;
                }
                $response->setStatusCode(400);
                $jsonModelArr = [$responseStatusKey => $config['ApiRequest']['responseFormat']['statusNokText'], $config['ApiRequest']['responseFormat']['resultKey'] => [$config['ApiRequest']['responseFormat']['errorKey'] => $this->tokenPayload]];
            } else {
                $response->setStatusCode(401);
                $jsonModelArr = [$responseStatusKey => $config['ApiRequest']['responseFormat']['statusNokText'], $config['ApiRequest']['responseFormat']['resultKey'] => [$config['ApiRequest']['responseFormat']['errorKey'] => $config['ApiRequest']['responseFormat']['authenticationRequireText']]];
            }
        } else {
            $response->setStatusCode(400);
            $jsonModelArr = ['status' => 'NOK', 'result' => ['error' => 'Require copy this file vender\multidots\zf3-rest-api\config\restapi.global.php and paste to root config\autoload\restapi.global.php']];
        }

        $response->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $view = new JsonModel($jsonModelArr);
        $response->setContent($view->serialize());
        return $response;
    }

    /**
     * Check Request object have Authorization token or not 
     * @param type $request
     * @return type String
     */
    public function findJwtToken($request)
    {
        $jwtToken = $request->getHeaders("Authorization") ? $request->getHeaders("Authorization")->getFieldValue() : '';
        if ($jwtToken) {
            $jwtToken = trim(trim($jwtToken, "Bearer"), " ");
            return $jwtToken;
        }
        if ($request->isGet()) {
            $jwtToken = $request->getQuery('token');
        }
        if ($request->isPost()) {
            $jwtToken = $request->getPost('token');
        }
        return $jwtToken;
    }

    /**
     * contain user information for createing JWT Token
     */
    protected function generateJwtToken($payload)
    {
        if (!is_array($payload) && !is_object($payload)) {
            $this->token = false;
            return false;
        }
        $this->tokenPayload = $payload;
        $config = $this->getEvent()->getParam('config', false);
        $cypherKey = $config['ApiRequest']['jwtAuth']['cypherKey'];
        $tokenAlgorithm = $config['ApiRequest']['jwtAuth']['tokenAlgorithm'];
        $this->token = JWT::encode($this->tokenPayload, $cypherKey, $tokenAlgorithm);
        return $this->token;
    }

    /**
     * contain encoded token for user.
     */
    protected function decodeJwtToken()
    {
        if (!$this->token) {
            $this->tokenPayload = false;
        }
        $config = $this->getEvent()->getParam('config', false);
        $cypherKey = $config['ApiRequest']['jwtAuth']['cypherKey'];
        $tokenAlgorithm = $config['ApiRequest']['jwtAuth']['tokenAlgorithm'];
        try {
            $decodeToken = JWT::decode($this->token, $cypherKey, [$tokenAlgorithm]);
            $this->tokenPayload = $decodeToken;
        } catch (\Exception $e) {
            $this->tokenPayload = $e->getMessage();
        }
    }

    /**
     * Create Response for api Assign require data for response and check is valid response or give error
     * @return \Zend\View\Model\JsonModel 
     * 
     */
    public function createResponse()
    {
        $config = $this->getEvent()->getParam('config', false);
        $event = $this->getEvent();
        $response = $event->getResponse();

        if (is_array($this->apiResponse)) {
            $response->setStatusCode($this->httpStatusCode);
        } else {
            $this->httpStatusCode = 500;
            $response->setStatusCode($this->httpStatusCode);
            $errorKey = $config['ApiRequest']['responseFormat']['errorKey'];
            $defaultErrorText = $config['ApiRequest']['responseFormat']['defaultErrorText'];
            $this->apiResponse[$errorKey] = $defaultErrorText;
        }
        $statusKey = $config['ApiRequest']['responseFormat']['statusKey'];
        if ($this->httpStatusCode == 200) {
            $sendResponse[$statusKey] = $config['ApiRequest']['responseFormat']['statusOkText'];
        } else {
            $sendResponse[$statusKey] = $config['ApiRequest']['responseFormat']['statusNokText'];
        }
        $sendResponse[$config['ApiRequest']['responseFormat']['resultKey']] = $this->apiResponse;
        return new JsonModel($sendResponse);
    }

    /* end jwt */

    /* parse data fingerPrint */
    public function ParseDataFinger($data,$p1,$p2){
        $data=" ".$data;
        $hasil="";
        $awal=strpos($data,$p1);
        if($awal!=""){
            $akhir=strpos(strstr($data,$p1),$p2);
            if($akhir!=""){
                $hasil=substr($data,$awal+strlen($p1),$akhir-strlen($p1));
            }
        }
        return $hasil;	
    }

    /* elapsed time to string */
    public function timeElapsedString($time_ago, $full = false) {
		$time_ago = strtotime($time_ago);
		$cur_time   = time();
		$time_elapsed   = $cur_time - $time_ago;
		$seconds    = $time_elapsed ;
		$minutes    = round($time_elapsed / 60 );
		$hours      = round($time_elapsed / 3600);
		$days       = round($time_elapsed / 86400 );
		$weeks      = round($time_elapsed / 604800);
		$months     = round($time_elapsed / 2600640 );
		$years      = round($time_elapsed / 31207680 );
		// Seconds
		if($seconds <= 60){
			return "just now";
		}
		//Minutes
		else if($minutes <=60){
			if($minutes==1){
				return "one minute ago";
			}
			else{
				return "$minutes minutes ago";
			}
		}
		//Hours
		else if($hours <=24){
			if($hours==1){
				return "an hour ago";
			}else{
				return "$hours hrs ago";
			}
		}
		//Days
		else if($days <= 7){
			if($days==1){
				return "yesterday";
			}else{
				return "$days days ago";
			}
		}
		//Weeks
		else if($weeks <= 4.3){
			if($weeks==1){
				return "a week ago";
			}else{
				return "$weeks weeks ago";
			}
		}
		//Months
		else if($months <=12){
			if($months==1){
				return "a month ago";
			}else{
				return "$months months ago";
			}
		}
		//Years
		else{
			if($years==1){
				return "one year ago";
			}else{
				return "$years years ago";
			}
		}
    }

    /*  format huruf besar depannya saja */
	public function isUpper($str){
		$text = ucwords(stripslashes(htmlentities(trim($str))));
		return $text;
    }
    
    /* trim data */
    public function isTrim($str){
		$text = ucwords(trim($str));	
		return $text;
    }
    
    /* all upper data */
    public function isUpperAll($str){
		$text = strtoupper(trim($str));	
		return $text;
    }
    
    /* substring data */
    public function subStrText($long, $pan){
		$tes = "";
		if(strlen($long) > $pan ){
			$reduce = substr($long,0, $pan).".";
		}else{
			$reduce = $long;
		}
		return $tes = $reduce;
	}
    
    /* convert decimal jadi format uang */
    public function convertMoney($panjang){
		$angka = "";
		$jumlah_desimal ="0";
		$pemisah_desimal =",";
		$pemisah_ribuan =".";
		
		$angka = number_format($panjang, $jumlah_desimal, $pemisah_desimal, $pemisah_ribuan).",-";
		return $angka;
    }
    
    /* date GMT +7 */
	public function dates(){
		$dates = gmdate('Y-m-d H:i:s', time()+60*60*7);
		return $dates;
	}
	
	public function formatYMD($date){
		$result = date('Y-m-d H:i:s',strtotime($date));
		return $result;
	}
	
	public function formatDMY($date){
		$result = date('d M Y',strtotime($date));
		return $result;
	}
}