<?php

namespace Avorg;

class Language {
	private $baseRoute;
	private $wpCode;
	private $dbCode;
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

    /**
     * @param mixed $dbCode
     * @return Language
     */
    public function setDbCode($dbCode)
    {
        $this->dbCode = $dbCode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDbCode()
    {
        return $this->dbCode;
    }

    private function translateUrlFragment($fragment)
	{
		return key_exists($fragment, $this->urlFragments) ? $this->urlFragments[$fragment] : $fragment;
	}

	public function getBaseRoute()
	{
		return $this->baseRoute;
	}

	public function getWpCode()
	{
		return $this->wpCode;
	}

	public function setWpCode($langCode)
	{
		$this->wpCode = $langCode;
		return $this;
	}
}