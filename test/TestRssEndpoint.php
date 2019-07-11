<?php

use Avorg\Endpoint\RssEndpoint;

final class TestRssEndpoint extends Avorg\TestCase
{
	/** @var RssEndpoint $rssEndpoint */
	protected $rssEndpoint;

	/**
	 * @throws ReflectionException
	 */
	public function setUp()
	{
		parent::setUp();

		$this->rssEndpoint = $this->factory->secure("Avorg\\Endpoint\\RssEndpoint\\Latest");
	}

	public function testGetOutput()
	{
		$this->rssEndpoint->getOutput();

		$this->mockTwig->assertTwigTemplateRendered("page-feed.twig");
	}

	public function testReturnsOutput()
	{
		$this->mockTwig->setReturnValue("render", "rendered_template");

		$result = $this->rssEndpoint->getOutput();

		$this->assertEquals("rendered_template", $result);
	}

	public function testSetsHeader()
	{
		$this->rssEndpoint->getOutput();

		$this->mockPhp->assertMethodCalledWith(
			"header", 'Content-Type: application/rss+xml; charset=utf-8');
	}

	public function testSetsImage()
	{
		$this->rssEndpoint->getOutput();

		$this->mockTwig->assertTwigTemplateRenderedWithData("page-feed.twig", [
			"image" => AVORG_LOGO_URL
		]);
	}
}