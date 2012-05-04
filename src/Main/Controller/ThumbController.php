<?php

namespace Main\Controller;

use Zend\Mvc\Controller\RestfulController;

/**
 *
 */
class ThumbController extends RestfulController
{
	/**
	 * Return list of resources
	 *
	 * @return mixed
	 */
	public function getList() {}

	/**
	 * Return single resource
	 *
	 * @param mixed $id
	 * @return array
	 */
	public function get($id)
	{
		$data = array();

		for ($i = 0; $i < 20; $i++) {
			$iid = (($id * 10) + $i);
			$data[] = array(
				'id' => $iid,
				'title' => 'Image-' . $iid,
				'position' => $iid,
				'filename' => 'some-filename-' . $iid . '.jpg',
			);
		}

		return $data;
	}

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
