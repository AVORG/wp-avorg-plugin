<?php

namespace Avorg;

use function defined;
use Exception;
use natlib\Factory;

if (!defined('ABSPATH')) exit;

class PresenterRepository
{
	/** @var AvorgApi $api */
	private $api;

	/** @var Factory $factory */
	private $factory;

	public function __construct(AvorgApi $api, Factory $factory)
	{
		$this->api = $api;
		$this->factory = $factory;
	}

	/**
	 * @param $id
	 * @return Presenter|null
	 * @throws Exception
	 */
	public function getPresenter($id)
	{
		$rawPresenter = $this->api->getPresenter($id);

		return $rawPresenter ? $this->factory->make("Avorg\\Presenter") : null;
	}
}