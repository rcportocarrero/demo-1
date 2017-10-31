<?php

namespace Usuario;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module {

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $eventManager->getSharedManager()->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', function($event) {
            $controller = $event->getTarget();
            $controllerName = get_class($controller);
            $moduleNamespace = substr($controllerName, 0, strpos($controllerName, '\\'));
            $configs = $event->getApplication()->getServiceManager()->get('config');
            if (isset($configs['module_layouts'][$moduleNamespace]))
            {
                $controller->layout($configs['module_layouts'][$moduleNamespace]);
            }
        }, 100);
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php'
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Ag_Client' => function($sm) {
                    $config = $sm->get('config');
                    $ag_config = $config['apigility']['config'];
                    $ag_client = new \BaseX\Apigility\AgClient($ag_config);
                    $table = [];
                    return $ag_client;
                },
                        'Soap_Client' => function($sm) {
                    $config = $sm->get('config');
                    $soap_config = $config['soap']['config'];
                    $soap_client = new \BaseX\Apigility\SoapClient($soap_config);
                    return $soap_client;
                },
                        'Usuario\Storage\AuthStorage' => function($sm) {
                    $config = $sm->get('config');
                    return new \Usuario\Storage\AuthStorage($config['session']['name']);
                },
                        'AuthService' => function($sm) {
                    $authService = new AuthenticationService();
                    $authService->setStorage($sm->get('Usuario\Storage\AuthStorage'));

                    return $authService;
                },
                        'AuthStorage' => function($sm) {
                    return $sm->get('Usuario\Storage\AuthStorage');
                },
                        'Usuario\Model\OauthTempTable' => function($sm) {
                    $dbAdapter = $sm->get('db_maestro');
                    $table = new \Usuario\Model\OauthTempTable($dbAdapter);
                    return $table;
                },
                    ),
                );
            }

        }
        