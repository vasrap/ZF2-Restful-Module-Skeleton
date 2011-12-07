<?php
return array(
	'layout' => 'layouts/layout.phtml',
	'display_exceptions' => true,
	'di' => array(
		'instance' => array(
			'alias' => array(
				'info' => 'Main\Controller\InfoController',
				'category' => 'Main\Controller\CategoryController',
				'thumb' => 'Main\Controller\ThumbController',
				'index' => 'Main\Controller\IndexController',
				'error' => 'Main\Controller\ErrorController',
				'view' => 'Zend\View\PhpRenderer',
			),
			'Zend\View\PhpRenderer' => array(
				'parameters' => array(
					'resolver' => 'Zend\View\TemplatePathStack',
					'options' => array(
						'script_paths' => array(
							'main' => __DIR__ . '/../views',
						),
					),
				),
			),
		),
	),
	'routes' => array(
		'restful' => array(
			'type' => 'Zend\Mvc\Router\Http\Segment',
			'options' => array(
				'route' => '/:controller[.:formatter][/:id]',
				'constraints' => array(
					'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
					'formatter' => '[a-zA-Z][a-zA-Z0-9_-]*',
					'id' => '[a-zA-Z][a-zA-Z0-9_-]*'
				),
			),
		),
		'action' => array(
			'type' => 'Zend\Mvc\Router\Http\Segment',
			'options' => array(
				'route' => '/[:controller[/:action]]',
				'constraints' => array(
					'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
				),
				'defaults' => array(
					'controller' => 'index',
					'action' => 'index',
				)
			),
		),
	),
);
