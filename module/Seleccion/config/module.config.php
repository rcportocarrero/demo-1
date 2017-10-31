<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Seleccion\Controller\Index' => 'Seleccion\Controller\IndexController',
            'Seleccion\Controller\Consulta' => 'Seleccion\Controller\ConsultaController',
            'Seleccion\Controller\Configuracion' => 'Seleccion\Controller\ConfiguracionController',
        )
    ),
    'router' => array(
        'routes' => array(
            'seleccion' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/seleccion',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Seleccion\Controller',
                        'controller' => 'Seleccion\Controller\Index',
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
                ),
            ),
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'seleccion' => __DIR__ . '/../view'
        ),
        'template_map' => array(
            'layout/layout_seleccion' => __DIR__ . '/../view/layout/layout.phtml',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    'asset_manager' => array(
        'resolver_configs' => array(
            'map' => array(
                'strings_vars.js' => __DIR__ . '/../public/js/strings_vars.js',
                'm_config_instrumentos.js' => __DIR__ . '/../public/js/m_config_instrumentos.js',
                'm_seccion_instrumentos.js' => __DIR__ . '/../public/js/m_seccion_instrumentos.js',
                'grid_instrumentos.js' => __DIR__ . '/../public/js/grid_instrumentos.js',
                'main.js' => __DIR__ . '/../public/js/main.js',
                'template.js' => __DIR__ . '/../public/js/template.js',
            ),
            'collections' => array(
                'd.js' => array(                  
                    'strings_vars.js',
                    'm_config_instrumentos.js',
                    'm_seccion_instrumentos.js',
                    'grid_instrumentos.js',
                    'main.js',
                    'template.js',
                ),
            ),
        ),
        'filters' => array(
            'd.js' => array(
                array(
                    // Note: You will need to require the classes used for the filters yourself.
                    'filter' => 'JSMin', // Allowed format is Filtername[Filter]. Can also be FQCN
                ),
            ),
        ),
        'caching' => array(
            'style.css' => array(
                'cache' => 'Assetic\\Cache\\FilesystemCache',
                'options' => array(
                    'dir' => __DIR__ . '/../../../data/cache', // path/to/cache
                ),
            ),
            'd.js' => array(
                'cache' => 'Assetic\\Cache\\FilesystemCache',
                'options' => array(
                    'dir' => __DIR__ . '/../../../data/cache', // path/to/cache
                ),
            ),
        ),
    ),
);
