<?php
return array(
	'display_exceptions' => true,
	'di'                 => array(
		'instance'       => array(
			'alias' => array(
				'info'     => 'Main\Controller\InfoController',
				'category' => 'Main\Controller\CategoryController',
				'thumb'    => 'Main\Controller\ThumbController',
				'json-pp'  => 'Main\PostProcessor\Json',
				'image-pp' => 'Main\PostProcessor\Image',
			),
		),
	),
	'routes' => array(
		'restful' => array(
			'type'    => 'Zend\Mvc\Router\Http\Segment',
			'options' => array(
				'route'       => '/:controller[.:formatter][/:id]',
				'constraints' => array(
					'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
					'formatter'  => '[a-zA-Z][a-zA-Z0-9_-]*',
					'id'         => '[a-zA-Z0-9_-]*'
				),
			),
		),
	),
);
