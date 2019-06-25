<?php

namespace Avorg;

use function defined;

if (!defined('ABSPATH')) exit;

class Topic
{
	/** @var Router $router */
	private $router;

	private $data;

	public function __construct(Router $router)
	{
		$this->router = $router;
	}

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

	public function getUrl()
	{
		return $this->router->buildUrl("Avorg\Page\Topic\Detail", [
			"entity_id" => $this->data->id,
			"slug" => $this->router->formatStringForUrl($this->data->title) . ".html"
		]);
	}
}