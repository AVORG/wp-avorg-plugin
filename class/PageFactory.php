<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class PageFactory
{
	/** @var Factory $factory */
	private $factory;

	private $pageNames = [
		"Media",
		"Topic",
		"Playlist"
	];

	public function __construct(Factory $factory)
	{
		$this->factory = $factory;
	}

	/**
	 * @return array
	 */
	public function getPages()
	{
		return array_map(function($pageName) {
			return $this->factory->get("Page\\$pageName");
		}, $this->pageNames);
	}
}