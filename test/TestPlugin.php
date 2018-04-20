<?php

final class TestPlugin extends Avorg\TestCase
{
	private $mediaPageInsertCall = array("wp_insert_post", array(
		"post_content" => "Media Detail",
		"post_title" => "Media Detail",
		"post_status" => "publish",
		"post_type" => "page"
	), true);
	
	protected function setUp()
	{
		parent::setUp();
		$this->mockWordPress->setReturnValue("call", "Media Detail");
	}
	
	public function testInsertsMediaDetailsPage()
	{
		$this->mockWordPress->setReturnValue("call", null);
		
		$this->mockedPlugin->activate();
		
		$this->assertCalledWith($this->mockWordPress, "call", ...$this->mediaPageInsertCall);
	}
	
	public function testDoesNotInsertPageTwice()
	{
		$this->mockWordPress->setReturnValue("call", ["post"]);
		
		$this->mockedPlugin->activate();
		
		$this->assertNotCalledWith($this->mockWordPress, "call", ...$this->mediaPageInsertCall);
	}
	
	public function testAddsMediaPageUI()
	{
		$this->mockTwig->setReturnValue("render", "playerUI");
		
		$haystack = $this->mockedPlugin->addMediaPageUI("");
		
		$this->assertContains("playerUI", $haystack);
	}
	
	public function testPassesPageContent()
	{
		$haystack = $this->mockedPlugin->addMediaPageUI("content");
		
		$this->assertContains("content", $haystack);
	}
	
	public function testUsesTwig()
	{
		$this->mockedPlugin->addMediaPageUI("content");
		
		$this->assertCalledWith($this->mockTwig, "render", "mediaPageUI.twig", ["presentation" => null], true);
	}
	
	public function testOnlyOutputsMediaPageUIOnMediaPage()
	{
		$this->mockWordPress->setReturnValue("call", "NOT THE MEDIA PAGE");
		$this->mockTwig->setReturnValue("render", "playerUI");
		
		$haystack = $this->mockedPlugin->addMediaPageUI("content");
		
		$this->assertNotContains("playerUI", $haystack);
	}
	
	public function testGetsPresentation()
	{
		$_GET = ["presentation_id" => "12345"];
		
		$this->mockedPlugin->addMediaPageUI("content");
		
		$this->assertCalledWith($this->mockAvorgApi, "getPresentation", "12345");
	}
	
	public function testPassesPresentationToTwig()
	{
		$_GET = ["presentation_id" => "12345"];
		
		$this->mockWordPress->setReturnValue("call", "Media Detail");
		$this->mockAvorgApi->setReturnValue("getPresentation", "presentation");
		
		$this->mockedPlugin->addMediaPageUI("content");
		
		$this->assertCalledWith($this->mockTwig, "render", "mediaPageUI.twig", ["presentation" => "presentation"], true);
	}
}