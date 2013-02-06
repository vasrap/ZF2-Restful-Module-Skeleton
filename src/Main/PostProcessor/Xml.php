<?php

namespace Main\PostProcessor;

/**
 *
 */
class Xml extends AbstractPostProcessor
{
	public function process()
	{
    $xml = new \SimpleXMLElement("<?xml version=\"1.0\"?><response></response>");
    
    $this->createXmlNode($this->_vars, $xml);
    $result = $xml->asXML();

		$this->_response->setContent($result);

		$headers = $this->_response->getHeaders();
		$headers->addHeaderLine('Content-Type', 'application/xml');
		$this->_response->setHeaders($headers);
	}
	
	protected function createXmlNode($result, &$xml)
	{
    foreach($result as $key => $value) {
      if(is_array($value)) {
        if(!is_numeric($key)){
          $subnode = $xml->addChild("$key");
          $this->createXmlNode($value, $subnode);
        }
        else{
          $this->createXmlNode($value, $xml);
        }
      }
      else {
        $xml->addChild("$key","$value");
      }
    }
	}
}
