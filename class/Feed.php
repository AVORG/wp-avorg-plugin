<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class Feed
{
	/** @var Renderer $renderer */
	private $renderer;

	private $title;
	private $recordings;
	private $link;
	private $language;
	private $image;

	public function __construct(Renderer $renderer)
	{
		$this->renderer = $renderer;
	}

	public function toXml()
	{
		$xml = $this->renderer->render(
			"page-feed.twig",
			[
				"title" => $this->title,
				"recordings" => $this->recordings,
				"link" => $this->link,
				"language" => $this->language,
				"image" => $this->image
			],
			TRUE
		);

		return $xml ?: "";
	}

	public function setTitle($title)
	{
		$this->title = $title;
	}

	public function setRecordings($recordings)
	{
		$this->recordings = $recordings;
	}

	public function setLink($url)
	{
		$this->link = $url;
	}

	public function setLanguage($language)
	{
		$this->language = $language;
	}

	public function setImage($image)
	{
		$this->image = $image;
	}
}