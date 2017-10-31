<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

return array(
    'controllers' => array(
        'invokables' => array(
            'BaseX\Controller\SessionToolbar' => 'BaseX\Controller\SessionToolbarController'
        )
    ),
    'router' => array(
        'routes' => array(
            'base_x' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/base-x',
                    'defaults' => array(
                        '__NAMESPACE__' => 'BaseX\Controller',
                        'controller' => 'BaseX\Controller\Index',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                    'session-toolbar' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => '/session-toolbar[/:action]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                'controller' => 'BaseX\Controller\SessionToolbar',
                                'action' => 'hola',
                            ],
                        ],
                    ],
                ),
            ),
        )
    ),
    'service_manager' => array(
        'invokables' => array(
            'session.toolbar' =>
            'BaseX\Collector\SessionCollector',
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'zend-developer-tools/toolbar/session-data'
            => __DIR__ . '/../view/zend-developer-tools/toolbar/session-data.phtml',
        ),
    ),
    'zenddevelopertools' => array(
        'profiler' => array(
            'collectors' => array(
                'session.toolbar' => 'session.toolbar',
            ),
        ),
        'toolbar' => array(
            'entries' => array(
                'session.toolbar' => 'zend-developer-tools/toolbar/session-data',
            ),
        ),
    ),
);
