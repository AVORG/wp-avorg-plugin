<?php

use Avorg\Shortcode;
use Avorg\TwigGlobal;

final class TestRssShortcode extends Avorg\TestCase
{
	/** @var Shortcode $listShortcode */
	protected $listShortcode;

	/**
	 * @throws ReflectionException
	 */
	public function setUp()
	{
		parent::setUp();

		$this->listShortcode = $this->factory->secure("Avorg\\Shortcode\\Rss");
	}

	public function testAddsShortcode()
	{
		$this->listShortcode->init();

		$this->mockWordPress->assertMethodCalledWith(
			"add_shortcode",
			"avorg-rss",
			[$this->listShortcode, "renderShortcode"]
		);
	}

	/**
	 * @throws Exception
	 */
	public function testRendersTemplate()
	{
		$this->listShortcode->renderShortcode([]);

		$this->mockTwig->assertTwigTemplateRendered("shortcode-rss.twig");
	}

	public function testGetsUrl()
	{
		$this->listShortcode->renderShortcode(["id" => "trending"]);

		$this->mockTwig->assertTwigTemplateRenderedWithDataMatching("shortcode-rss.twig", function(TwigGlobal $data) {
			return $data->url === "http://localhost:8080/english/podcasts/trending";
		});
	}

	public function testCaseInsensitive()
	{
		$this->listShortcode->renderShortcode(["id" => "TRENDING"]);

		$this->mockTwig->assertTwigTemplateRenderedWithDataMatching("shortcode-rss.twig", function(TwigGlobal $data) {
			return $data->url === "http://localhost:8080/english/podcasts/trending";
		});
	}
}