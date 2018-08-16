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
	
	protected function make404ThrowingMediaPage()
	{
		$avorgApi = new \Avorg\AvorgApi_exceptions();
		
		$factory = new \Avorg\Factory(
			$avorgApi,
			$this->mockPhp,
			$this->mockTwig,
			$this->mockWordPress
		);
		
		return $factory->getMediaPage();
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
		
		$this->assertTwigTemplateRenderedWithData("organism-recording.twig", ["presentation" => null]);
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
		
		$this->assertTwigTemplateRenderedWithData("organism-recording.twig", ["presentation" => "presentation"]);
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
	
	public function testUsesPresentationIdToGetPresentation()
	{
		$wp_query = new \Avorg\WP_Query();
		$wp_query->getReturnVal = 42;
		
		$this->mediaPage->throw404($wp_query);
		
		$this->assertCalledWith($this->mockAvorgApi, "getPresentation", 42);
	}
	
	public function testGetsPresentationIdFromQuery()
	{
		$wp_query = new \Avorg\WP_Query();
		
		$this->mediaPage->throw404($wp_query);
		
		$this->assertEquals(["presentation_id"], $wp_query->getCallArgs);
	}
	
	public function testDoesNotSet404IfPresentationExists()
	{
		$wp_query = new \Avorg\WP_Query();
		$this->mockAvorgApi->setReturnValue("getPresentation", new StdClass());
		
		$this->mediaPage->throw404($wp_query);
		
		$this->assertFalse($wp_query->was404set);
	}
	
	public function testRegistersThrow404Method()
	{
		$this->assertWordPressFunctionCalledWith(
			"add_action",
			"parse_query",
			[$this->mediaPage, "throw404"]
		);
	}
	
	public function testHandlesExceptionAndThrows404()
	{
		$mediaPage = $this->make404ThrowingMediaPage();
		$wp_query = new \Avorg\WP_Query();
		
		$mediaPage->throw404($wp_query);
		
		$this->assertTrue($wp_query->was404set);
		$this->assertWordPressFunctionCalledWith("status_header", 404);
	}
	
	public function testThrowing404UnsetsPageId()
	{
		$mediaPage = $this->make404ThrowingMediaPage();
		$wp_query = new \Avorg\WP_Query();
		
		$wp_query->query_vars["page_id"] = 7;
		
		$mediaPage->throw404($wp_query);
		
		$this->assertArrayNotHasKey("page_id", $wp_query->query_vars);
	}
	
	public function testRegistersSetTitleMethodForTabTitle()
	{
		$this->assertWordPressFunctionCalledWith(
			"add_filter",
			"pre_get_document_title",
			[$this->mediaPage, "setTitle"]
		);
	}
	
	public function testRegistersSetTitleMethodForContentTitle()
	{
		$this->assertWordPressFunctionCalledWith(
			"add_filter",
			"the_title",
			[$this->mediaPage, "setTitle"]
		);
	}
	
	public function testSetTitleMethod()
	{
		$presentation = new StdClass();
		$presentation->title = "Presentation Title";
		
		$this->mockAvorgApi->setReturnValue("getPresentation", $presentation);
		
		$result = $this->mediaPage->setTitle("old title");
		
		$this->assertEquals("Presentation Title - AudioVerse", $result);
	}
	
	public function testUsesPresentationIdQueryVar()
	{
		$this->mockWordPress->setReturnValue("call", 7);
		
		$this->mediaPage->setTitle("old title");
		
		$this->assertCalledWith($this->mockAvorgApi, "getPresentation", 7);
	}
}