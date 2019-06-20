<?php

namespace Avorg\Route;

use Avorg\Route;

if (!\defined('ABSPATH')) exit;

class PageRoute extends Route
{

	function getBaseRoute()
	{
		return "index.php?page_id=$this->id";
	}

	public function getUrl($langCode, $variables)
	{
		$language = $this->languageFactory->getLanguageByLangCode($langCode);

		array_walk($this->routeTree, function(RouteFragment $fragment) use($variables) {
			$fragment->setVariables($variables);
		});

		return $language->getBaseUrl() . "/" . $this->getUrlFromFragments();
	}

	private function getUrlFromFragments()
	{
		return array_reduce((array) $this->routeTree, function ($carry, RouteFragment $trunk) {
			return $carry . $trunk->getUrlFragment();
		}, "");
	}
}