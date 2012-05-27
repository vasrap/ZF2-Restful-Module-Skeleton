<?php

namespace Main;

/**
 *
 */
class Module
{
	/**
	 * @param \Zend\ModuleManager\ModuleManager $moduleManager
	 */
	public function init(\Zend\ModuleManager\ModuleManager $moduleManager)
	{
		$ident   = 'Zend\Mvc\Controller\RestfulController';

		$events       = $moduleManager->events();
		$sharedEvents = $events->getSharedManager();
		$sharedEvents->attach($ident, 'dispatch', array($this, 'postProcess'), -100);
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
	 * @param \Zend\Mvc\MvcEvent $e
	 * @return null|\Zend\Http\PhpEnvironment\Response
	 */
	public function postProcess(\Zend\Mvc\MvcEvent $e)
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
}
