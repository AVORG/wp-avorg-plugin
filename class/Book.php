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
		return array_key_exists($name, $this->data);
	}

	public function __get($name)
	{
		return $this->data[$name];
	}
}