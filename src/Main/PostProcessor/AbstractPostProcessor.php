<?php

namespace Main\PostProcessor;

abstract class AbstractPostProcessor
{
	protected $_vars = null;

	/**
	 * @var null|\Zend\Http\Response
	 */
	protected $_response = null;

	/**
	 * @param array $vars
	 * @param \Zend\Http\Response $response
	 */
	public function __construct(array $vars, \Zend\Http\Response $response)
	{
		$this->_vars = $vars;
		$this->_response = $response;
	}

	/**
	 * @return null|\Zend\Http\Response
	 */
	public function getResponse()
	{
		return $this->_response;
	}

	/**
	 * @abstract
	 */
	abstract public function process();
}
