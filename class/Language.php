<?php

namespace Avorg;

class Language {
	private $baseRoute;
	private $langCode;
	private $urlFragments;

	/**
	 * @param mixed $baseRoute
	 * @return Language
	 */
	public function setBaseRoute($baseRoute)
	{
		$this->baseRoute = $baseRoute;
		return $this;
	}

	/**
	 * @param mixed $urlFragments
	 * @return Language
	 */
	public function setUrlFragments($urlFragments)
	{
		$this->urlFragments = $urlFragments;
		return $this;
	}

	public function getUrlFragments()
	{
		return $this->urlFragments;
	}

	public function translatePath($path)
	{
		$fragments = explode("/", $path);
		$translatedFragments = array_map([$this, "translateUrlFragment"], $fragments);

		return implode("/", $translatedFragments);
	}

	private function translateUrlFragment($fragment)
	{
		return key_exists($fragment, $this->urlFragments) ? $this->urlFragments[$fragment] : $fragment;
	}

	public function getBaseRoute()
	{
		return $this->baseRoute;
	}

	public function getLangCode()
	{
		return $this->langCode;
	}

	public function setLangCode($langCode)
	{
		$this->langCode = $langCode;
		return $this;
	}
}