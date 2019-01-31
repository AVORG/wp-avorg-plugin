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
	 * @return mixed
	 */
	public function getBaseRoute()
	{
		return $this->baseRoute;
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

	public function translateUrlFragment($fragment)
	{
		return $this->urlFragments[$fragment];
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