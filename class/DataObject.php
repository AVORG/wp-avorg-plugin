<?php

namespace Avorg;

use function defined;
use Exception;
use JsonSerializable;

if (!defined('ABSPATH')) exit;

abstract class DataObject implements JsonSerializable
{
	/** @var Router $router */
	protected $router;

	protected $data;

	protected $detailClass;

	public function __construct(Router $router)
	{
		$this->router = $router;
	}

	/**
	 * @param mixed $data
	 * @return DataObject
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
		$getter = "get" . ucfirst($name);

		return method_exists($this, $getter) ? $this->$getter() : $this->data->$name;
	}

	public function __toString()
	{
		return json_encode($this);
	}

	public function jsonSerialize()
	{
		return $this->toArray();
	}

	public function toArray()
	{
		return (array) $this->data;
	}

	/**
	 * @return string|null
	 * @throws Exception
	 */
	public function getUrl()
	{
		if (!class_exists($this->detailClass)) return null;

		return $this->router->buildUrl($this->detailClass, [
			"entity_id" => $this->getId(),
			"slug" => $this->getSlug()
		]);
	}

	public function getId()
	{
		return intval($this->data->id);
	}

	protected function getSlug() {
		return $this->router->formatStringForUrl($this->data->title) . ".html";
	}
}