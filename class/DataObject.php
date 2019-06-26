<?php

namespace Avorg;

use function defined;

if (!defined('ABSPATH')) exit;

abstract class DataObject implements iEntity
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
		return $this->data->$name;
	}

	public function toJson()
	{
		return json_encode($this->toArray());
	}

	public function toArray()
	{
		return $this->data;
	}

	public function getUrl()
	{
		return $this->router->buildUrl($this->detailClass, [
			"entity_id" => $this->getId(),
			"slug" => $this->getSlug()
		]);
	}

	public function getId()
	{
		return intval($this->__get("id"));
	}

	abstract protected function getSlug();
}