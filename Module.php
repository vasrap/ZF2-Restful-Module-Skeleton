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
		$events       = $moduleManager->events();
		$sharedEvents = $events->getSharedManager();

		$sharedEvents->attach('Zend\Mvc\Controller\RestfulController', MvcEvent::EVENT_DISPATCH, array($this, 'postProcess'), -100);
		$sharedEvents->attach('Zend\Mvc\Application', MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'errorProcess'), 999);
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

		$eventParams = $e->getParams();

		/** @var array $configuration */
		$configuration = $e->getApplication()->getConfiguration();

		/** @var \Exception $exception */
		$exception = $eventParams['exception'];

		$vars = array();
		if ($configuration['errors']['show_exceptions']['message']) {
			$vars['error-message'] = $exception->getMessage();
		}
		if ($configuration['errors']['show_exceptions']['trace']) {
			$vars['error-trace'] = $exception->getTrace();
		}

		if (empty($vars)) {
			$vars['error'] = 'Something went wrong';
		}

		/** @var PostProcessor\AbstractPostProcessor $postProcessor */
		$postProcessor = $di->get($configuration['errors']['post_processor'], array(
			'vars'     => array('exception: ' => $exception->getMessage()),
			'response' => $e->getResponse()
		));

		$postProcessor->process();

		$e->getResponse()->setStatusCode(\Zend\Http\PhpEnvironment\Response::STATUS_CODE_500);

		$e->stopPropagation();

		return $postProcessor->getResponse();
	}
}