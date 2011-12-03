<?php

namespace Main\View;

use ArrayAccess,
	Zend\Di\Locator,
	Zend\EventManager\EventCollection,
	Zend\EventManager\ListenerAggregate,
	Zend\EventManager\StaticEventCollection,
	Zend\Http\PhpEnvironment\Response,
	Zend\Mvc\Application,
	Zend\Mvc\MvcEvent,
	Zend\View\Renderer;

class Listener implements ListenerAggregate
{
	protected $layout;
	protected $listeners = array();
	protected $staticListeners = array();
	protected $view;
	protected $displayExceptions = false;

	public function __construct(Renderer $renderer, $layout = 'layout.phtml')
	{
		$this->view = $renderer;
		$this->layout = $layout;
	}

	public function setDisplayExceptionsFlag($flag)
	{
		$this->displayExceptions = (bool) $flag;
		return $this;
	}

	public function displayExceptions()
	{
		return $this->displayExceptions;
	}

	public function attach(EventCollection $events)
	{
		$this->listeners[] = $events->attach('dispatch.error', array($this, 'renderError'));
	}

	public function detach(EventCollection $events)
	{
		foreach ($this->listeners as $key => $listener) {
			$events->detach($listener);
			unset($this->listeners[$key]);
			unset($listener);
		}
	}

	public function registerStaticListeners(StaticEventCollection $events, $locator)
	{
		$ident = 'Zend\Mvc\Controller\RestfulController';
		$handler = $events->attach($ident, 'dispatch', array($this, 'renderRestfulView'), -50);
		$this->staticListeners[] = array($ident, $handler);

		$ident = 'Zend\Mvc\Controller\ActionController';
		$handler = $events->attach($ident, 'dispatch', array($this, 'renderActionView'), -50);
		$this->staticListeners[] = array($ident, $handler);
	}

	public function detachStaticListeners(StaticEventCollection $events)
	{
		foreach ($this->staticListeners as $i => $info) {
			list($id, $handler) = $info;
			$events->detach($id, $handler);
			unset($this->staticListeners[$i]);
		}
	}

	public function renderRestfulView(MvcEvent $e)
	{
		$response = $e->getResponse();
		if (!$response->isSuccess()) {
			return;
		}

		$routeMatch = $e->getRouteMatch();
		$formatter = $routeMatch->getParam('formatter', 'json');
		$script = $formatter . '.phtml';

		$vars = $e->getResult();
		if (is_scalar($vars)) {
			$vars = array('content' => $vars);
		} elseif (is_object($vars) && !$vars instanceof ArrayAccess) {
			$vars = (array) $vars;
		}

		$vars['response'] = $e->getResponse();
		$content = $this->view->render($script, $vars);

		$e->setResult($content);

		$response = $e->getResponse();
		if (!$response) {
			$response = new Response();
			$e->setResponse($response);
		}
		if ($response->isRedirect()) {
			return $response;
		}

		$response->setContent($e->getResult());
		return $response;
	}

	public function renderActionView(MvcEvent $e)
	{
		$response = $e->getResponse();
		if (!$response->isSuccess()) {
			return;
		}

		$routeMatch = $e->getRouteMatch();
		$controller = $routeMatch->getParam('controller', 'index');
		$action = $routeMatch->getParam('action', 'index');
		$script = $controller . '/' . $action . '.phtml';

		$vars = $e->getResult();
		if (is_scalar($vars)) {
			$vars = array('content' => $vars);
		} elseif (is_object($vars) && !$vars instanceof ArrayAccess) {
			$vars = (array) $vars;
		}

		$content = $this->view->render($script, $vars);

		$e->setResult($content);
		return $this->renderLayout($e);
	}

	public function renderLayout(MvcEvent $e)
	{
		$response = $e->getResponse();
		if (!$response) {
			$response = new Response();
			$e->setResponse($response);
		}
		if ($response->isRedirect()) {
			return $response;
		}

		$footer = $e->getParam('footer', false);
		$vars = array('footer' => $footer);

		if (false !== ($contentParam = $e->getParam('content', false))) {
			$vars['content'] = $contentParam;
		} else {
			$vars['content'] = $e->getResult();
		}

		$layout = $this->view->render($this->layout, $vars);
		$response->setContent($layout);
		return $response;
	}

	public function renderError(MvcEvent $e)
	{
		$error = $e->getError();
		$app = $e->getTarget();
		$response = $e->getResponse();
		if (!$response) {
			$response = new Response();
			$e->setResponse($response);
		}

		$script = 'error/index.phtml';

		switch ($error) {
			case Application::ERROR_CONTROLLER_NOT_FOUND:
			case Application::ERROR_CONTROLLER_INVALID:
				$vars = array(
					'message' => 'Page not found.',
					'exception' => $e->getParam('exception'),
					'display_exceptions' => $this->displayExceptions(),
				);
				$response->setStatusCode(404);
				$script = 'error/404.phtml';
				break;

			case Application::ERROR_EXCEPTION:
			default:
				$exception = $e->getParam('exception');
				$vars = array(
					'message' => 'An error occurred during execution; please try again later.',
					'exception' => $e->getParam('exception'),
					'display_exceptions' => $this->displayExceptions(),
				);
				$response->setStatusCode(500);
				break;
		}

		$content = $this->view->render($script, $vars);

		$e->setResult($content);
		return $this->renderLayout($e);
	}
}
