<?php

final class TestMediaPage extends Avorg\TestCase
{
	/** @var \Avorg\MediaPage $mediaPage */
	protected $mediaPage;
	
	private $mediaPageInsertCall = array("wp_insert_post", array(
		"post_content" => "Media Detail",
		"post_title" => "Media Detail",
		"post_status" => "publish",
		"post_type" => "page"
	), true);
	
	private function assertPlayerUiInjected()
	{
		$haystack = $this->makePlayerUiHaystack();
		
		$this->assertContains("playerUI", $haystack);
	}
	
	private function makePlayerUiHaystack()
	{
		$this->mockTwig->setReturnValue("render", "playerUI");
		
		return $this->mediaPage->addMediaPageUI("");
	}
	
	protected function setUp()
	{
		parent::setUp();
		
		$this->mockWordPress->setReturnValue("call", 5);
		$this->mediaPage = $this->factory->getMediaPage();
	}
	
	public function testSavesMediaPageId()
	{
		$this->mockWordPress->setReturnValues("call", [false, false, 7]);
		
		$this->mediaPage->createMediaPage();
		
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
		$this->mediaPage->createMediaPage();
		
		$this->assertCalledWith($this->mockWordPress, "call", "get_option", "avorgMediaPageId");
	}
	
	public function testCreatesPageIfNoPageStatus()
	{
		$this->mockWordPress->setReturnValues("call", [7, false]);
		
		$this->mediaPage->createMediaPage();
		
		$this->assertCalledWith($this->mockWordPress, "call", ...$this->mediaPageInsertCall);
	}
	
	public function testChecksPostStatus()
	{
		$this->mockWordPress->setReturnValue("call", 7);
		
		$this->mediaPage->createMediaPage();
		
		$this->assertCalledWith($this->mockWordPress, "call", "get_post_status", 7);
	}
	
	public function testUntrashesMediaPage()
	{
		$this->mockWordPress->setReturnValues("call", [7, "trash"]);
		
		$this->mediaPage->createMediaPage();
		
		$this->assertCalledWith($this->mockWordPress, "call", "wp_publish_post", 7);
	}
	
	public function testAddsMediaPageUI()
	{
		$this->assertPlayerUiInjected();
	}
	
	public function testPassesPageContent()
	{
		$haystack = $this->mediaPage->addMediaPageUI("content");
		
		$this->assertContains("content", $haystack);
	}
	
	public function testUsesTwig()
	{
		$this->mediaPage->addMediaPageUI("content");
		
		$this->assertCalledWith($this->mockTwig, "render", "organism-recording.twig", ["presentation" => null], true);
	}
	
	public function testOnlyOutputsMediaPageUIOnMediaPage()
	{
		$this->mockWordPress->setReturnValues("call", [1, 10]);
		
		$haystack = $this->makePlayerUiHaystack();
		
		$this->assertNotContains("playerUI", $haystack);
	}
	
	public function testPassesPresentationToTwig()
	{
		$this->mockAvorgApi->setReturnValue("getPresentation", "presentation");
		
		$this->mediaPage->addMediaPageUI("content");
		
		$this->assertCalledWith($this->mockTwig, "render", "organism-recording.twig", ["presentation" => "presentation"], true);
	}
	
	public function testGetsQueryVar()
	{
		$this->mediaPage->addMediaPageUI("content");
		
		$this->assertCalledWith($this->mockWordPress, "call", "get_query_var", "presentation_id");
	}
	
	public function testGetsPresentation()
	{
		$this->mockWordPress->setReturnValues("call", [7, 7, "54321"]);
		
		$this->mediaPage->addMediaPageUI("content");
		
		$this->assertCalledWith($this->mockAvorgApi, "getPresentation", "54321");
	}
	
	public function testConvertsMediaPageIdStringToNumber()
	{
		$this->mockWordPress->setReturnValues("call", ["5", 5]);
		
		$this->assertPlayerUiInjected();
	}
}