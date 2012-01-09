<?php

namespace Main\PostProcessor;

abstract class AbstractPostProcessor
{
	protected $_vars = null;

	/**
	 * @var null|\Zend\Http\Response
	 */
	protected $_response = null;

	public function __construct(array $vars, \Zend\Http\Response $response)
	{
		$this->_vars = $vars;
		$this->_response = $response;
	}

	abstract public function process();
}
