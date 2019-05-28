<?php

namespace Avorg;

use natlib\Factory;

class LanguageFactory {
	/** @var Factory $factory */
	private $factory;

	/** @var Filesystem $filesystem */
	private $filesystem;

	private $languages;

	public function __construct(
		Factory $factory,
		Filesystem $filesystem
	)
	{
		$this->factory = $factory;
		$this->filesystem = $filesystem;

		$this->languages = json_decode($this->filesystem->getFile("languages.json"), TRUE);
	}

	public function getLanguages()
	{
		return array_map(function($languageConfig) {
			return $this->buildLanguage($languageConfig);
		}, $this->languages);
	}

	public function getLanguageByLangCode($langCode)
	{
		return $this->getLanguageByPropertyValue("dbCode", $langCode);
	}

	public function getLanguageByBaseRoute($baseRoute)
	{
		return $this->getLanguageByPropertyValue("baseRoute", $baseRoute);
	}

	/**
	 * @param $name
	 * @param $value
	 * @return Language|null
	 */
	private function getLanguageByPropertyValue($name, $value)
	{
		$languageConfig = $this->getLanguageConfigByPropertyValue($name, $value);

		return $this->buildLanguage($languageConfig);
	}

	/**
	 * @param $name
	 * @param $value
	 * @return mixed
	 */
	private function getLanguageConfigByPropertyValue($name, $value)
	{
		$filteredLanguages = array_filter((array)$this->languages, function ($language) use ($name, $value) {
			return $language[$name] === $value;
		});

		return reset($filteredLanguages);
	}

	private function buildLanguage($languageConfig)
	{
		if (!$languageConfig) return null;

		/** @var Language $language */
		$language = $this->factory->obtain("Avorg\\Language");

		$language->setBaseRoute($languageConfig["baseRoute"]);
		$language->setUrlFragments($languageConfig["urlFragments"]);
		$language->setLangCode($languageConfig["wpLanguageCode"]);

		return $language;
	}
}