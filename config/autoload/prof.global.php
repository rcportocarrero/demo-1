<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//$dbParams = array(
//    'database' => 'cpa_comite',
//    'username' => 'developer',
//    'password' => 'D3v3l0p3r$***$M1n3Du2016**Peru',
//    'hostname' => 'diedds.com',
//    // buffer_results - only for mysqli buffered queries, skip for others
//    'options' => array('buffer_results' => true)
//);
//return array(
//    'service_manager' => array(
//        'factories' => array(
//            'Zend\Db\Adapter\Adapter' => function ($sm) use ($dbParams) {
//                $adapter = new BjyProfiler\Db\Adapter\ProfilingAdapter(array(
//                    'driver' => 'pdo',
//                    'dsn' => 'mysql:dbname=' . $dbParams['database'] . ';host=' . $dbParams['hostname'],
//                    'database' => $dbParams['database'],
//                    'username' => $dbParams['username'],
//                    'password' => $dbParams['password'],
//                    'hostname' => $dbParams['hostname'],
//                ));
//
//                if (php_sapi_name() == 'cli') {
//                    $logger = new Zend\Log\Logger();
//                    // write queries profiling info to stdout in CLI mode
//                    $writer = new Zend\Log\Writer\Stream('php://output');
//                    $logger->addWriter($writer, Zend\Log\Logger::DEBUG);
//                    $adapter->setProfiler(new BjyProfiler\Db\Profiler\LoggingProfiler($logger));
//                } else {
//                    $adapter->setProfiler(new BjyProfiler\Db\Profiler\Profiler());
//                }
//                if (isset($dbParams['options']) && is_array($dbParams['options'])) {
//                    $options = $dbParams['options'];
//                } else {
//                    $options = array();
//                }
//                $adapter->injectProfilingStatementPrototype($options);
//                return $adapter;
//            },
//                ),
//            ),
//        );
        

return array(
    'stefano_db_profiler' => array(
        /**
         * List of all Db adapters which you want to profile
         */
        'dbAdapterServiceManagerKey' => array(
            'db_maestro',
        ),
    ),
);

return [];