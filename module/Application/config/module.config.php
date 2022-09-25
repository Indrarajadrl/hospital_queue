<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
			'404' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/:*',
                    'defaults' => [
                        'controller' => Master\RouteNotFoundController::class,
                        'action' => 'routenotfound',
                    ],
                ],
                'priority' => -1000,
            ],
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\QueueController::class,
                        'action'     => 'beranda',
                    ],
                ],
            ],
            'dashboardsuper' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/dasboard',
                    'defaults' => [
                        'controller' => Controller\SuperadminController::class,
                        'action'     => 'dasboard',
                    ],
                ],
            ],
            'dashboardadmin' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/antrianadmin',
                    'defaults' => [
                        'controller' => Controller\AdminController::class,
                        'action'     => 'antrianadmin',
                    ],
                ],
            ],

            'application' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/application[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
			'api' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/api[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => [
                        'controller' => Controller\ApiController::class,
                        'action'     => 'index',
                        'isAuthorizationRequired' => false
                    ],
                ],
            ],
            'signin' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/api/login',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => [
                        'controller' => Controller\ApiController::class,
                        'action'     => 'login',
                        'isAuthorizationRequired' => false
                    ],
                ],
            ],
            'login' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/login[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action'     => 'login',
                        'isAuthorizationRequired' => false
                    ],
                ],
            ],
            'user' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/user[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action'     => 'index',
                        'isAuthorizationRequired' => false
                    ],
                ],
            ],
            'queue' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/queue[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => [
                        'controller' => Controller\QueueController::class,
                        'action'     => 'index',
                        'isAuthorizationRequired' => false
                    ],
                ],
            ],
            'admin' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/admin[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => [
                        'controller' => Controller\AdminController::class,
                        'action'     => 'index',
                        'isAuthorizationRequired' => false
                    ],
                ],
            ],
            'superadmin' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/superadmin[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => [
                        'controller' => Controller\SuperadminController::class,
                        'action'     => 'index',
                        'isAuthorizationRequired' => false
                    ],
                ],
            ],
            'umum' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/umum[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => [
                        'controller' => Controller\UmumController::class,
                        'action'     => 'index',
                        'isAuthorizationRequired' => false
                    ],
                ],
            ],
            'cobain' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/cobain[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => [
                        'controller' => Controller\CobainController::class,
                        'action'     => 'index',
                        'isAuthorizationRequired' => false
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Factory\IndexControllerFactory::class,
            Controller\UserController::class => Factory\UserControllerFactory::class,
            Controller\QueueController::class => Factory\QueueControllerFactory::class,
     
            Controller\AdminController::class => Factory\AdminControllerFactory::class,
            Controller\SuperadminController::class => Factory\SuperadminControllerFactory::class,
            Controller\UmumController::class => Factory\UmumControllerFactory::class,
            // Master\GlobalActionController::class => Factory\GlobalActionControllerFactory::class,
            Controller\ApiController::class => Factory\ApiControllerFactory::class,
            Controller\CobainController::class => Factory\CobainControllerFactory::class,
           
            // Controller\ApiController::class => InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    'databases' => array(
        'primary'     => array(
            'driver'    => 'pgsql',
            'host'      => 'localhost',
            'username'  => 'postgres',
            'password'  => '',
            'port'      => 5432,
            'schema'    => 'antrian',
        ),
    ),

    'php' => array(
        'display_errors'         => false,
        'error_reporting'        => E_ALL,
        'max_execution_time'     => 200,
        'session.gc_maxlifetime' => 86400, //24 jam
    ),
];
