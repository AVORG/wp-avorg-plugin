<?php

final class TestListShortcode extends Avorg\TestCase
{
	// helper functions
	
	private function assertSupportsListType($listType)
	{
		$this->mockedListShortcode->renderShortcode(["list" => $listType]);
		
		$this->assertCalledWith($this->mockAvorgApi, "getPresentations", $listType);
	}
	
	// tests
	
	public function testExists()
	{
		$this->assertTrue(is_object($this->mockedListShortcode));
	}
	
	public function testAddsShortcode()
	{
		$this->mockedListShortcode->addShortcode();
		
		$this->assertWordPressFunctionCalledWith(
			"add_shortcode",
			"avorg-list",
			[$this->mockedListShortcode, "renderShortcode"]
		);
	}
	
	public function testRenderFunction()
	{
		$entry = new stdClass();
		$entry->recordings = "item";
		$this->mockAvorgApi->setReturnValue("getPresentations", [$entry, $entry, $entry]);
		
		$this->mockedListShortcode->renderShortcode("");
		
		$this->assertCalledWith(
			$this->mockTwig,
			"render",
			"shortcode-list.twig",
			["recordings" => ["item", "item", "item"]],
			TRUE
		);
	}
	
	public function testRenderFunctionReturnsRenderedView()
	{
		$this->mockTwig->setReturnValue("render", "output");
		
		$result = $this->mockedListShortcode->renderShortcode("");
		
		$this->assertEquals("output", $result);
	}
	
	public function testRenderFunctionDoesNotPassAlongNonsenseListName()
	{
		$this->mockedListShortcode->renderShortcode( [ "list" => "nonsense" ] );
		
		$this->assertCalledWith( $this->mockAvorgApi, "getPresentations", null );
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