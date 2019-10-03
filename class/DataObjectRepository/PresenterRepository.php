<?php

namespace Avorg\DataObjectRepository;

use Avorg\DataObject;
use Avorg\DataObjectRepository;
use function defined;
use Exception;

if (!defined('ABSPATH')) exit;

class PresenterRepository extends DataObjectRepository
{
	protected $dataObjectClass = "Avorg\\DataObject\\Presenter";

	/**
	 * @param $id
	 * @return DataObject|null
	 * @throws Exception
	 */
	public function getPresenter($id)
	{
		$rawPresenter = $this->api->getPresenter($id);

		if (!$rawPresenter) return null;

		return $this->makeDataObject($rawPresenter);
	}

	/**
	 * @param null $search
	 * @return array
	 * @throws Exception
	 */
	public function getPresenters($search = null)
	{
		$rawPresenters = $this->api->getPresenters($search) ?: [];

		return $this->makeDataObjects($rawPresenters);
	}
}