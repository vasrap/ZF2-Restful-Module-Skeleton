<?php

namespace Main\Controller;

use Zend\Mvc\Controller\ActionController;

class IndexController extends ActionController {

	public function indexAction() {

		return array('message' => 'Test!');
	}
}
