<?php

namespace Avorg;

use function defined;
use Exception;
use natlib\Factory;
use ReflectionException;

if (!defined('ABSPATH')) exit;

class PresenterRepository
{
	/** @var AvorgApi $api */
	private $api;

	/** @var Factory $factory */
	private $factory;

	/**
	 * PresenterRepository constructor.
	 * @param AvorgApi $api
	 * @param Factory $factory
	 */
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

		if (!$rawPresenter) return null;

		return $this->buildPresenter($rawPresenter);
	}

	/**
	 * @param null $search
	 * @return array
	 * @throws Exception
	 */
	public function getPresenters($search = null)
	{
		$rawPresenters = $this->api->getPresenters($search) ?: [];

		return array_map([$this, "buildPresenter"], $rawPresenters);
	}

	/**
	 * @param $rawPresenter
	 * @return Presenter
	 * @throws ReflectionException
	 */
	private function buildPresenter($rawPresenter)
	{
		return $this->factory->make("Avorg\\DataObject\\Presenter")->setData($rawPresenter);
	}
}