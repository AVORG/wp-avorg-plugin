<?php

namespace Avorg;

use function defined;

if (!defined('ABSPATH')) exit;

class Presenter
{
	/** @var PresentationRepository $presentationRepository */
	private $presentationRepository;

	private $apiPresenter;

	public function __construct($apiPresenter, PresentationRepository $presentationRepository)
	{
		$this->apiPresenter = $apiPresenter;
		$this->presentationRepository = $presentationRepository;
	}



	public function getPresentations()
	{
		return $this->presentationRepository->getPresenterPresentations($this->getId());
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
}