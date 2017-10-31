<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Usuario\Controller\Index' => 'Usuario\Controller\IndexController',
            'Usuario\Controller\Sec' => 'Usuario\Controller\SecController',
            'Usuario\Controller\Captcha' => 'Usuario\Controller\CaptchaController',
            'Usuario\Controller\Sesion' => 'Usuario\Controller\SesionController',
            'Usuario\Controller\Perfil' => 'Usuario\Controller\PerfilController',
        )
    ),
    'router' => array(
        'routes' => array(
//            'usuario' => array(
//                'type' => 'segment',
//                'options' => array(
//                    'route' => '/usuario[/:action][/:id]',
//                    'constraints' => array(
//                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
//                        'id' => '[0-9]+'
//                    ),
//                    'defaults' => array(
//                        'controller' => 'Usuario\Controller\Usuario',
//                        'action' => 'index'
//                    )
//                )
//            )

            'usuario' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/usuario',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Usuario\Controller',
                        'controller' => 'Usuario\Controller\Index',
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
                    'sec' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/sec[/:action]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                '__NAMESPACE__' => 'Usuario\Controller',
                                'controller' => 'Usuario\Controller\Sec',
                                'action' => 'index',
                            ),
                        ),
                    ),
                ),
            ),
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
        ),
    ),
//    'module_layouts' => array(
//        'Usuario' => 'layout/layout.phtml'
//    ),
);
