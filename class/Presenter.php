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

	private $apiPresenter;

	public function __construct(
		$apiPresenter,
		LanguageFactory $languageFactory,
		PresentationRepository $presentationRepository
	)
	{
		$this->apiPresenter = $apiPresenter;
		$this->languageFactory = $languageFactory;
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

	public function getUrl()
	{
		$language = $this->languageFactory->getLanguageByLangCode($this->apiPresenter->lang);

		if (!$language) return null;

		$presentationId = $this->apiPresenter->id;
		$tail = $language->formatStringForUrl($this->getName()) . ".html";
		$path = "sermons/presenters/$presentationId/$tail";

		return $language->getTranslatedUrl($path);
	}
}