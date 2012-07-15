<?php

namespace Main\PostProcessor;

/**
 *
 */
class Image extends AbstractPostProcessor
{
	public function process()
	{
		$result = $this->_vars['image'];

		$this->_response->setContent($result);

		$headers = $this->_response->getHeaders();
		$headers->addHeaderLine('Content-Type', 'image/' . $this->_vars['type']);
		$headers->addHeaderLine('Cache-Control', 'max-age=86400');
		$this->_response->setHeaders($headers);
	}
}
