<?php

namespace Avorg;

class Language {
	/** @var RouteFactory $routeFactory */
	private $routeFactory;

	/** @var WordPress $wp */
	protected $wp;

	private $baseRoute;
	private $langCode;
	private $urlFragments;

	public function __construct(RouteFactory $routeFactory, WordPress $wp)
	{
		$this->routeFactory = $routeFactory;
		$this->wp = $wp;
	}

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

	public function getTranslatedUrl($path)
	{
		$fragments = explode("/", $path);
		$translatedFragments = array_map([$this, "translateUrlFragment"], $fragments);
		$translatedPath = implode("/", $translatedFragments);

		return $this->getBaseUrl() . "/$translatedPath";
	}

	/**
	 * @return mixed
	 */
	public function getBaseUrl()
	{
		return "http://${_SERVER['HTTP_HOST']}/$this->baseRoute";
	}

	public function translateUrlFragment($fragment)
	{
		return key_exists($fragment, $this->urlFragments) ? $this->urlFragments[$fragment] : $fragment;
	}

	public function formatStringForUrl($string)
	{
		$stringLowerCase = strtolower($string);
		$stringNoPunctuation = preg_replace("/[^\w ]/", "", $stringLowerCase);
		$stringHyphenated = str_replace(" ", "-", $stringNoPunctuation);

		return $stringHyphenated;
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

	public function getRoute()
	{
		return $this->routeFactory->getPageRoute(
			$this->wp->get_option( "page_on_front"),
			$this->baseRoute
		);
	}
}