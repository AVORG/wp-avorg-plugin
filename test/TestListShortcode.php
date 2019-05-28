<?php

final class TestListShortcode extends Avorg\TestCase
{
	/** @var \Avorg\ListShortcode $listShortcode */
	protected $listShortcode;
	
	public function setUp()
	{
		parent::setUp();
		
		$this->listShortcode = $this->factory->secure("Avorg\\ListShortcode");
	}
	
	// helper functions
	
	private function assertSupportsListType($listType)
	{
		$this->listShortcode->renderShortcode(["list" => $listType]);
		
		$this->mockAvorgApi->assertMethodCalledWith( "getPresentations", $listType);
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
	
	public function testRenderFunction()
	{
		$entry = ["title" => "Recording Title"];
		$this->mockAvorgApi->loadPresentations($entry, $entry, $entry);
		
		$this->listShortcode->renderShortcode("");

		$this->mockTwig->assertTwigTemplateRenderedWithDataMatching("shortcode-list.twig", function($data) {
			return $data->recordings[2] instanceof \Avorg\Presentation;
		});
	}
	
	public function testRenderFunctionReturnsRenderedView()
	{
		$this->mockTwig->setReturnValue("render", "output");
		
		$result = $this->listShortcode->renderShortcode("");
		
		$this->assertEquals("output", $result);
	}
	
	public function testRenderFunctionDoesNotPassAlongNonsenseListName()
	{
		$this->listShortcode->renderShortcode( [ "list" => "nonsense" ] );
		
		$this->mockAvorgApi->assertMethodCalledWith( "getPresentations", null );
	}
	
	public function testRenderFunctionGetsFeaturedMessages()
	{
		$this->assertSupportsListType("featured");
	}
	
	public function testRenderFunctionGetsPopularMessages()
	{
		$this->assertSupportsListType("popular");
	}
}