<?php
return array(
	'di' => array(
		'instance' => array(
			'alias' => array(
				'json-pp'  => 'Main\PostProcessor\Json',
				'image-pp' => 'Main\PostProcessor\Image',
			)
		)
	),
	'controller' => array(
		'classes' => array(
			'info'     => 'Main\Controller\InfoController',
			'category' => 'Main\Controller\CategoryController',
			'shrink'   => 'Main\Controller\ShrinkController',
		)
	),
	'router' => array(
		'routes' => array(
			'restful' => array(
				'type'    => 'Zend\Mvc\Router\Http\Segment',
				'options' => array(
					'route'       => '/rest/:controller[.:formatter][/:id]',
					'constraints' => array(
						'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'formatter'  => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'         => '[a-zA-Z0-9_-]*'
					),
				),
			),
		),
	),
);