<?php

namespace Avorg;

use function defined;

if (!defined('ABSPATH')) exit;

class Presenter
{
	/** @var LanguageFactory $languageFactory */
	private $languageFactory;

	/** @var PresentationRepository $presentationRepository */
	private $presentationRepository;

	/** @var Router $router */
	private $router;

	private $apiPresenter;

	public function __construct(
		LanguageFactory $languageFactory,
		PresentationRepository $presentationRepository,
		Router $router
	)
	{
		$this->languageFactory = $languageFactory;
		$this->presentationRepository = $presentationRepository;
		$this->router = $router;
	}

	public function setPresenter($apiPresenter)
	{
		$this->apiPresenter = $apiPresenter;
		return $this;
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

	public function getUrl()
	{
		return $this->router->buildUrl("Avorg\Page\Presenter\Detail", [
			"entity_id" => $this->apiPresenter->id,
			"slug" => $this->router->formatStringForUrl($this->getName()) . ".html"
		]);
	}
}