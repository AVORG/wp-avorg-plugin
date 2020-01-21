<?php

namespace Avorg\DataObjectRepository;

use Avorg\DataObject;
use Avorg\DataObjectRepository;
use natlib\Stub;
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
     * @param null $start
     * @return array
     * @throws Exception
     */
	public function getDataObjects($search = null, $start = null)
	{
		$rawPresenters = $this->api->getPresenters($search, $start) ?: [];

        return $this->makePresenters($rawPresenters);
    }

    /**
     * @param array $rawPresenters
     * @return array
     */
    public function makePresenters(array $rawPresenters): array
    {
        return $this->makeDataObjects($rawPresenters);
    }
}