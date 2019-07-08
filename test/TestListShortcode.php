<?php

use Avorg\DataObject\Recording;
use Avorg\ListShortcode;
use Avorg\TwigGlobal;

final class TestListShortcode extends Avorg\TestCase
{
	/** @var ListShortcode $listShortcode */
	protected $listShortcode;

	/**
	 * @throws ReflectionException
	 */
	public function setUp()
	{
		parent::setUp();
		
		$this->listShortcode = $this->factory->secure("Avorg\\ListShortcode");
	}
	
	// helper functions

	/**
	 * @param $listType
	 * @throws Exception
	 */
	private function assertSupportsListType($listType)
	{
		$this->listShortcode->renderShortcode(["list" => $listType]);
		
		$this->mockAvorgApi->assertMethodCalledWith( "getRecordings", strtolower($listType));
	}
	
	// tests
	
	public function testExists()
	{
		$this->assertTrue(is_object($this->listShortcode));
	}
	
	public function testAddsShortcode()
	{
		$this->listShortcode->addShortcode();
		
		$this->mockWordPress->assertMethodCalledWith(
			"add_shortcode",
			"avorg-list",
			[$this->listShortcode, "renderShortcode"]
		);
	}

	/**
	 * @throws Exception
	 */
	public function testRenderFunction()
	{
		$entry = ["title" => "Recording Title"];
		$this->mockAvorgApi->loadRecordings($entry, $entry, $entry);
		
		$this->listShortcode->renderShortcode("");

		$this->mockTwig->assertTwigTemplateRenderedWithDataMatching("shortcode-list.twig", function($data) {
			return $data->recordings[2] instanceof Recording;
		});
	}

	/**
	 * @throws Exception
	 */
	public function testRenderFunctionReturnsRenderedView()
	{
		$this->mockTwig->setReturnValue("render", "output");
		
		$result = $this->listShortcode->renderShortcode("");
		
		$this->assertEquals("output", $result);
	}

	/**
	 * @throws Exception
	 */
	public function testRenderFunctionDoesNotPassAlongNonsenseListName()
	{
		$this->listShortcode->renderShortcode( [ "list" => "nonsense" ] );
		
		$this->mockAvorgApi->assertMethodCalledWith( "getRecordings", null );
	}

	/**
	 * @throws Exception
	 */
	public function testRenderFunctionGetsFeaturedMessages()
	{
		$this->assertSupportsListType("featured");
	}

	/**
	 * @throws Exception
	 */
	public function testRenderFunctionGetsPopularMessages()
	{
		$this->assertSupportsListType("popular");
	}

	/**
	 * @throws Exception
	 */
	public function testIncludesPodcastKey()
	{
		$this->listShortcode->renderShortcode("");

		$this->mockTwig->assertTwigTemplateRenderedWithDataMatching("shortcode-list.twig", function(TwigGlobal $data) {
			return $data->__isset("rss") === True;
		});
	}

	public function testIncludesRecentPodcastUrl()
	{
		$this->listShortcode->renderShortcode( [ "list" => "recent" ] );

		$this->mockTwig->assertTwigTemplateRenderedWithDataMatching("shortcode-list.twig", function(TwigGlobal $data) {
			return $data->rss === "http://localhost:8080/english/podcasts/latest";
		});
	}

	public function testIncludesPopularPodcastUrl()
	{
		$this->listShortcode->renderShortcode( [ "list" => "popular" ] );

		$this->mockTwig->assertTwigTemplateRenderedWithDataMatching("shortcode-list.twig", function(TwigGlobal $data) {
			return $data->rss === "http://localhost:8080/english/podcasts/trending";
		});
	}

	public function testCaseInsensitive()
	{
		$this->assertSupportsListType("POPULAR");
	}
}