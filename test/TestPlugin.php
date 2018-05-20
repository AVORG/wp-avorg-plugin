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
		$this->mockWordPress->setReturnValue("call", false);
		
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
	
	public function testPassesPresentationToTwig()
	{
		$this->mockWordPress->setReturnValue("call", "Media Detail");
		$this->mockAvorgApi->setReturnValue("getPresentation", "presentation");
		
		$this->mockedPlugin->addMediaPageUI("content");
		
		$this->assertCalledWith($this->mockTwig, "render", "mediaPageUI.twig", ["presentation" => "presentation"], true);
	}
	
	public function testGetsQueryVar()
	{
		$this->mockedPlugin->addMediaPageUI("content");
		
		$this->assertCalledWith($this->mockWordPress, "call", "get_query_var", "presentation_id");
	}
	
	public function testGetsPresentation()
	{
		$this->mockWordPress->setReturnValues("call", ["Media Detail", "54321"]);
		
		$this->mockedPlugin->addMediaPageUI("content");
		
		$this->assertCalledWith($this->mockAvorgApi, "getPresentation", "54321");
	}
	
	public function testInsertsMediaDetailsPageOnInit()
	{
		$this->mockWordPress->setReturnValue("call", false);
		
		$this->mockedPlugin->init();
		
		$this->assertCalledWith($this->mockWordPress, "call", ...$this->mediaPageInsertCall);
	}
	
	public function testActivatesRouterOnPluginActivate()
	{
		$this->mockedPlugin->activate();
		
		$this->assertCalledWith($this->mockRouter, "activate");
	}
	
	public function testSavesMediaPageId()
	{
		$this->mockWordPress->setReturnValues("call", [false, false, 7]);
		
		$this->mockedPlugin->createMediaPage();
		
		$this->assertCalledWith(
			$this->mockWordPress,
			"call",
			"update_option",
			"avorgMediaPageId",
			7
		);
	}
	
	public function testGetsMediaPageId()
	{
		$this->mockedPlugin->createMediaPage();
		
		$this->assertCalledWith($this->mockWordPress, "call", "get_option", "avorgMediaPageId");
	}
	
	public function testCreatesPageIfNoPageStatus()
	{
		$this->mockWordPress->setReturnValues("call", [7, false]);
		
		$this->mockedPlugin->createMediaPage();
		
		$this->assertCalledWith($this->mockWordPress, "call", ...$this->mediaPageInsertCall);
	}
	
	public function testChecksPostStatus()
	{
		$this->mockWordPress->setReturnValue("call", 7);
		
		$this->mockedPlugin->createMediaPage();
		
		$this->assertCalledWith($this->mockWordPress, "call", "get_post_status", 7);
	}
	
	public function testUntrashesMediaPage()
	{
		$this->mockWordPress->setReturnValues("call", [7, "trash"]);
		
		$this->mockedPlugin->createMediaPage();
		
		$this->assertCalledWith($this->mockWordPress, "call", "wp_publish_post", 7);
	}
}