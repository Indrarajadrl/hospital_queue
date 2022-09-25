<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */


namespace Khansia;
use Zend\Mvc\MvcEvent;

class Module
{
    const VERSION = '3.0.3-dev';
    
	/**
     * 
     * @param \Zend\Mvc\MvcEvent $e
    */

    public function getConfigs()
    {   
        $con =  './config/modules.config.php';
        return include $con;
    }
    
	public function getConfig(){        
        $con = $this->getConfigs();
        $dir =  './module/'.$con[6].'/config/module.config.php';
        return include $dir;
    }

    public function onBootstrap(MvcEvent $e)
    {

        // Allow from any origin
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }

        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            }

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            }
            exit(0);
        }
    }

    public function getAutoloaderConfig() {

        $namespaces = array();
        $list = scandir(__DIR__);
        foreach ($list as $item) {
          if (is_dir(__DIR__ . '/' . $item)) {
            if (($item != '.') && ($item != '..')) {
              $namespaces[__NAMESPACE__ . '\\' . $item] = __DIR__ . '/' . $item;
            }
          }
        }
    
        return array(
          'Zend\Loader\StandardAutoloader' => array(
            'namespaces' => $namespaces,
          ),
        );
    }
      
}
