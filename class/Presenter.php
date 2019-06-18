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

	public function __isset($property)
	{
		return property_exists($this->apiPresenter, $property);
	}

	public function __get($property)
	{
		return $this->apiPresenter->$property;
	}

	public function getPresentations()
	{
		return $this->presentationRepository->getPresenterPresentations($this->getId());
	}

	public function getName()
	{
		return implode(" ", [
			$this->apiPresenter->givenName,
			$this->apiPresenter->surname,
			$this->apiPresenter->suffix
		]);
	}

	private function getId()
	{
		return intval($this->apiPresenter->id);
	}
}