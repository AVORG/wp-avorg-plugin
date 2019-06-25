<?php

namespace Avorg;

use function defined;

if (!defined('ABSPATH')) exit;

class Presenter
{
	/** @var RecordingRepository $recordingRepository */
	private $recordingRepository;

	/** @var Router $router */
	private $router;

	private $apiPresenter;

	public function __construct(
		RecordingRepository $recordingRepository,
		Router $router
	)
	{
		$this->recordingRepository = $recordingRepository;
		$this->router = $router;
	}

	public function setPresenter($apiPresenter)
	{
		$this->apiPresenter = $apiPresenter;
		return $this;
	}

	public function getRecordings()
	{
		return $this->recordingRepository->getPresenterRecordings($this->getId());
	}

	public function getName()
	{
		return trim(implode(" ", [
			$this->__get("givenName"),
			$this->__get("surname"),
			$this->__get("suffix"),
		]));
	}

	private function getId()
	{
		return intval($this->apiPresenter->id);
	}

	public function __get($property)
	{
		return $this->__isset($property) ? $this->apiPresenter->$property : null;
	}

	public function __isset($property)
	{
		return property_exists($this->apiPresenter, $property);
	}

	public function getUrl()
	{
		return $this->router->buildUrl("Avorg\Page\Presenter\Detail", [
			"entity_id" => $this->apiPresenter->id,
			"slug" => $this->router->formatStringForUrl($this->getName()) . ".html"
		]);
	}
}