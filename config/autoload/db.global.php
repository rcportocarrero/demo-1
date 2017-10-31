<?php

return [
    'db' => array(
        'adapters' => array(
            'db_maestro' => array(
                'charset' => 'utf8',
                'database' => 'db_indago_dev',
                'driver' => 'PDO_Mysql',
                'hostname' => 'localhost',
                'username' => 'root',
                'password' => '',
                'port' => '3306',
                'profiler' => true, 
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Db\Adapter\AdapterAbstractServiceFactory',
        )
    ),
];