<?php

namespace Main\Controller;

use Zend\Mvc\Controller\RestfulController;

class CategoryController extends RestfulController
{
	/**
	 * Return list of resources
	 *
	 * @return array
	 */
	public function getList()
	{
		$data = array();

		for ($i = 0; $i < 4; $i++) {
			$data[] = array(
				'id' => $i,
				'title' => 'CATEGORY-' . $i,
				'position' => $i,
				'thumbs' => array(),
			);
		}

		return $data;
	}

	/**
	 * Return single resource
	 *
	 * @param mixed $id
	 * @return mixed
	 */
	public function get($id) {}

	/**
	 * Create a new resource
	 *
	 * @param mixed $data
	 * @return mixed
	 */
	public function create($data) {}

	/**
	 * Update an existing resource
	 *
	 * @param mixed $id
	 * @param mixed $data
	 * @return mixed
	 */
	public function update($id, $data) {}

	/**
	 * Delete an existing resource
	 *
	 * @param  mixed $id
	 * @return mixed
	 */
	public function delete($id) {}
}
