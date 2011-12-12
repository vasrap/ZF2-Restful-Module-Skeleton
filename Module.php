<?php

namespace Main;

use Zend\Module\Manager,
	Zend\EventManager\StaticEventManager,
	Zend\Module\Consumer\AutoloaderProvider;

class Module implements AutoloaderProvider {

	protected $view;
	protected $viewListener;

	public function init(Manager $moduleManager) {

		$events = StaticEventManager::getInstance();

		$ident = 'Zend\Mvc\Controller\RestfulController';
		$handler = $events->attach($ident, 'dispatch', array($this, 'postProcess'), -100);
	}

	public function getAutoloaderConfig() {

		return array(
			'Zend\Loader\ClassMapAutoloader' => array(
				__DIR__ . '/autoload_classmap.php',
			),
			'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
				),
			),
		);
	}

	public function getConfig($env = null) {

		return include __DIR__ . '/config/module.config.php';
	}

	public function postProcess(\Zend\Mvc\MvcEvent $e) {

		$routeMatch = $e->getRouteMatch();
		$formatter = $routeMatch->getParam('formatter', false);

		$di = $e->getTarget()->getLocator();

		if ($formatter !== false) {
			$postProcessor = $di->get($formatter . '-pp', array(
				'vars' => $e->getResult(),
				'response' => $e->getResponse()
			));

			$postProcessor->process();
		}
	}
}
