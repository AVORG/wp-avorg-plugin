<?php

namespace Avorg;

use function defined;

if (!defined('ABSPATH')) exit;

class Topic
{
	private $data;

	/**
	 * @param mixed $data
	 * @return Topic
	 */
	public function setData($data)
	{
		$this->data = $data;
		return $this;
	}

	public function __isset($name)
	{
		return property_exists($this->data, $name);
	}

	public function __get($name)
	{
		return $this->data->$name;
	}
}