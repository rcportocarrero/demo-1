<?php

namespace Seleccion;

use Zend\Authentication\AuthenticationService;
use Zend\ModuleManager\ModuleManager;

class Module {

    public function getAutoloaderConfig() {
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

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig() {
        return array(
            'factories' => array(
                'Seleccion\Model\SpTable' => function($sm) {
                    $dbAdapter = $sm->get('db_maestro');
                    $table = new \Seleccion\Model\SpTable($dbAdapter);
                    return $table;
                },
                'Seleccion\Model\SeleccionTable' => function($sm) {
                    $dbAdapter = $sm->get('db_maestro');
                    $table = new \Seleccion\Model\SeleccionTable($dbAdapter);
                    return $table;
                },
                'Seleccion\Model\DreTable' => function($sm) {
                    $dbAdapter = $sm->get('db_maestro');
                    $table = new \Seleccion\Model\DreTable($dbAdapter);
                    return $table;
                },
                'Seleccion\Model\UgelTable' => function($sm) {
                    $dbAdapter = $sm->get('db_maestro');
                    $table = new \Seleccion\Model\UgelTable($dbAdapter);
                    return $table;
                },
                'Seleccion\Model\AplicadorTable' => function($sm) {
                    $dbAdapter = $sm->get('db_maestro');
                    $table = new \Seleccion\Model\AplicadorTable($dbAdapter);
                    return $table;
                },
                'Seleccion\Validator\SeleccionValidator' => function() {
                    $table = new \Seleccion\Validator\SeleccionValidator();
                    return $table;
                },
                'Seleccion\Model\MuestraTable' => function($sm) {
                    $dbAdapter = $sm->get('db_maestro');
                    $table = new \Seleccion\Model\MuestraTable($dbAdapter);
                    return $table;
                },
                'Seleccion\Model\AmbitoTable' => function($sm) {
                    $dbAdapter = $sm->get('db_maestro');
                    $table = new \Seleccion\Model\AmbitoTable($dbAdapter);
                    return $table;
                },
                'Seleccion\Model\InstrumentoTable' => function($sm) {
                    $dbAdapter = $sm->get('db_maestro');
                    $table = new \Seleccion\Model\InstrumentoTable($dbAdapter);
                    return $table;
                },
                'Seleccion\Model\TipoInstrumentoTable' => function($sm) {
                    $dbAdapter = $sm->get('db_maestro');
                    $table = new \Seleccion\Model\TipoInstrumentoTable($dbAdapter);
                    return $table;
                },
                'Seleccion\Model\TipoInformanteTable' => function($sm) {
                    $dbAdapter = $sm->get('db_maestro');
                    $table = new \Seleccion\Model\TipoInformanteTable($dbAdapter);
                    return $table;
                },
                'Seleccion\Model\GrupoPreguntaTable' => function($sm) {
                    $dbAdapter = $sm->get('db_maestro');
                    $table = new \Seleccion\Model\GrupoPreguntaTable($dbAdapter);
                    return $table;
                },
                'Seleccion\Model\Hashid' => function($sm) {
                    $table = new \Seleccion\Model\Hashid();
                    return $table;
                },
            ),
        );
    }

    public function init(ModuleManager $manager) {
        $events = $manager->getEventManager();
        $sharedEvents = $events->getSharedManager();
        $sharedEvents->attach(__NAMESPACE__, 'dispatch', function($e) {
            $controller = $e->getTarget();
            if (get_class($controller) == 'Seleccion\Controller\IndexController') {
                $controller->layout('layout/layout_seleccion');
            }
        }, 100);
    }

}
