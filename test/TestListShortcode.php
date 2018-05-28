<?php

final class TestListShortcode extends Avorg\TestCase
{
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
		
		$this->mockedListShortcode->renderShortcode();
		
		$this->assertCalledWith(
			$this->mockTwig,
			"render",
			"shortcode/shortcode-list.twig",
			["recordings" => ["item", "item", "item"]],
			TRUE
		);
	}
	
	public function testRenderFunctionReturnsRenderedView()
	{
		$this->mockTwig->setReturnValue("render", "output");
		
		$result = $this->mockedListShortcode->renderShortcode();
		
		$this->assertEquals("output", $result);
	}
}