<?php

namespace Avorg;


use function defined;

if (!defined('ABSPATH')) exit;

abstract class Endpoint implements iRoutable
{
	/** @var WordPress $wp */
	private $wp;

	public function __construct(WordPress $wp)
	{
		$this->wp = $wp;
	}

	abstract public function getOutput();

	/**
	 * @return string
	 */
	public function getRouteId()
	{
		return str_replace("\\", "_", get_class($this));
	}

	/**
	 * @return mixed
	 */
	protected function getEntityId()
	{
		return $this->wp->get_query_var("entity_id");
	}
}