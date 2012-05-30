<?php

namespace Main;

use Zend\Mvc\MvcEvent,
	\Zend\ModuleManager\ModuleManager;

/**
 *
 */
class Module
{
	/**
	 * @param ModuleManager $moduleManager
	 */
	public function init(ModuleManager $moduleManager)
	{
		$identity = 'Zend\Mvc\Controller\RestfulController';

		$events       = $moduleManager->events();
		$sharedEvents = $events->getSharedManager();

		$sharedEvents->attach($identity, MvcEvent::EVENT_DISPATCH, array($this, 'postProcess'), -100);
		$sharedEvents->attach($identity, MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'errorProcess'), 999);
	}

	/**
	 * return array
	 */
	public function getAutoloaderConfig()
	{
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

	/**
	 * @return array
	 */
	public function getConfig()
	{
		return include __DIR__ . '/configs/module.config.php';
	}

	/**
	 * @param MvcEvent $e
	 * @return null|\Zend\Http\PhpEnvironment\Response
	 */
	public function postProcess(MvcEvent $e)
	{
		$routeMatch = $e->getRouteMatch();
		$formatter  = $routeMatch->getParam('formatter', false);

		/** @var \Zend\Di\Di $di */
		$di = $e->getTarget()->getServiceLocator()->get('di');

		if ($formatter !== false) {
			/** @var PostProcessor\AbstractPostProcessor $postProcessor */
			$postProcessor = $di->get($formatter . '-pp', array(
				'vars'     => (is_array($e->getResult()) ? $e->getResult() : $e->getResult()->getVariables()),
				'response' => $e->getResponse()
			));

			$postProcessor->process();

			return $postProcessor->getResponse();
		}

		return null;
	}

	/**
	 * @param MvcEvent $e
	 * @return null|\Zend\Http\PhpEnvironment\Response
	 */
	public function errorProcess(MvcEvent $e)
	{
		/** @var \Zend\Di\Di $di */
		$di = $e->getTarget()->getServiceLocator()->get('di');

		/** @var PostProcessor\Json $jsonPostProcessor */
		$jsonPostProcessor = $di->get('json-pp', array(
			'vars'     => (array) $e->getError(),
			'response' => $e->getResponse()
		));

		$jsonPostProcessor->process();

		return $jsonPostProcessor->getResponse();
	}
}
