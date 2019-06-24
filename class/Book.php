<?php

namespace Avorg;


if (!defined('ABSPATH')) exit;

class Book
{
	private $data;

	/**
	 * @param mixed $data
	 * @return Book
	 */
	public function setData($data)
	{
		$this->data = $data;
		return $this;
	}

	public function __isset($name)
	{
		return isset($this->data->$name);
	}

	public function __get($name)
	{
		if (!$this->__isset($name)) return null;

		return $this->data->$name;
	}
}