<?php

namespace Main\PostProcessor;

class Image extends AbstractPostProcessor
{
	public function process()
	{
		$result = $this->_vars['image'];

		$this->_response->setContent($result);

		$headers = $this->_response->headers();
		$headers->addHeaderLine('Content-Type', 'image/' . $this->_vars['type']);
		$this->_response->setHeaders($headers);
	}
}

