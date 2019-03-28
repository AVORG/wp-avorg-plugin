<?php

final class TestMediaPage extends Avorg\TestCase
{
	/** @var \Avorg\Page\Media $mediaPage */
	protected $mediaPage;
	
	private function assertPlayerUiInjected()
	{
		$haystack = $this->makePlayerUiHaystack();
		
		$this->assertContains("playerUI", $haystack);
	}
	
	private function makePlayerUiHaystack()
	{
		$this->mockTwig->setReturnValue("render", "playerUI");
		
		return $this->mediaPage->addUi("");
	}
	
	protected function make404ThrowingMediaPage()
	{
		$avorgApi = new \Avorg\AvorgApi_exceptions();

		$factory = new \Avorg\Factory();

		$factory->injectObjects(
			$avorgApi,
			$this->mockPhp,
			$this->mockTwig,
			$this->mockWordPress
		);
		
		return $factory->get("Page\\Media");
	}
	
	protected function setUp()
	{
		parent::setUp();

		$this->mediaPage = $this->factory->get("Page\\Media");
	}
	
	public function testSavesMediaPageId()
	{
		$this->mockWordPress->setReturnValue("get_option", false);
		$this->mockWordPress->setReturnValue("get_post_status", false);
		$this->mockWordPress->setReturnValue("wp_insert_post", 7);
		
		$this->mediaPage->createPage();
		
		$this->mockWordPress->assertMethodCalledWith(
			"update_option",
			"avorg_page_id_avorg_page_media",
			7
		);
	}
	
	public function testGetsMediaPageId()
	{
		$this->mediaPage->createPage();
		
		$this->mockWordPress->assertMethodCalledWith( "get_option", "avorg_page_id_avorg_page_media");
	}
	
	public function testCreatesPageIfNoPageStatus()
	{
		$this->mockWordPress->setReturnValue("get_option", 7);
		$this->mockWordPress->setReturnValue("get_post_status", false);
		
		$this->mediaPage->createPage();

		$this->mockWordPress->assertPageCreated("Media Detail", "Media Detail");
	}
	
	public function testChecksPostStatus()
	{
		$this->mockWordPress->setReturnValue("get_option", 7);
		
		$this->mediaPage->createPage();
		
		$this->mockWordPress->assertMethodCalledWith(  "get_post_status", 7);
	}
	
	public function testUntrashesMediaPage()
	{
		$this->mockWordPress->setReturnValue("get_option", 7);
		$this->mockWordPress->setReturnValue("get_post_status", "trash");
		
		$this->mediaPage->createPage();
		
		$this->mockWordPress->assertMethodCalledWith(  "wp_publish_post", 7);
	}
	
	public function testAddsMediaPageUI()
	{
		$this->mockWordPress->setReturnValue("get_option", 5);
		$this->mockWordPress->setReturnValue("get_the_ID", 5);

		$this->assertPlayerUiInjected();
	}
	
	public function testPassesPageContent()
	{
		$haystack = $this->mediaPage->addUi("content");
		
		$this->assertContains("content", $haystack);
	}
	
	public function testUsesTwig()
	{
		$this->mockWordPress->setReturnValue("get_option", 5);
		$this->mockWordPress->setReturnValue("get_the_ID", 5);

		$this->mediaPage->addUi("content");
		
		$this->mockTwig->assertTwigTemplateRenderedWithData("organism-recording.twig", ["presentation" => null]);
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
		$this->mockWordPress->setReturnValue("get_option", 5);
		$this->mockWordPress->setReturnValue("get_the_ID", 5);
		
		$this->mediaPage->addUi("content");

		$this->mockTwig->assertAnyCallMatches( "render", function($carry, $call) {
            $callGlobal = $call[1]["avorg"];

		    return $callGlobal->presentation instanceof \Avorg\Presentation || $carry;
        });
	}
	
	public function testGetsQueryVar()
	{
		$this->mockWordPress->setReturnValue("get_option", 5);
		$this->mockWordPress->setReturnValue("get_the_ID", 5);

		$this->mediaPage->addUi("content");
		
		$this->mockWordPress->assertMethodCalledWith( "get_query_var", "entity_id");
	}
	
	public function testGetsPresentation()
	{
		$this->mockWordPress->setReturnValue("get_option", 7);
		$this->mockWordPress->setReturnValue("get_the_ID", 7);
		$this->mockWordPress->setReturnValues("get_query_var",  "54321");
		
		$this->mediaPage->addUi("content");
		
		$this->mockAvorgApi->assertMethodCalledWith( "getPresentation", "54321");
	}
	
	public function testConvertsMediaPageIdStringToNumber()
	{
		$this->mockWordPress->setReturnValue("get_option", "7");
		$this->mockWordPress->setReturnValue("get_the_ID", 7);
		
		$this->assertPlayerUiInjected();
	}
	
	public function testUsesPresentationIdToGetPresentation()
	{
		$wp_query = new \Avorg\WP_Query();

		$this->mockWordPress->setReturnValue("get_query_var", 42);
		
		$this->mediaPage->throw404($wp_query);
		
		$this->mockAvorgApi->assertMethodCalledWith( "getPresentation", 42);
	}
	
	public function testDoesNotSet404IfPresentationExists()
	{
		$wp_query = new \Avorg\WP_Query();
		$this->mockAvorgApi->setReturnValue("getPresentation", new StdClass());
		
		$this->mediaPage->throw404($wp_query);
		
		$this->assertFalse($wp_query->was404set);
	}
	
	public function testHandlesExceptionAndThrows404()
	{
		$mediaPage = $this->make404ThrowingMediaPage();
		$wp_query = new \Avorg\WP_Query();
		
		$mediaPage->throw404($wp_query);
		
		$this->assertTrue($wp_query->was404set);
		$this->mockWordPress->assertMethodCalledWith("status_header", 404);
	}
	
	public function testThrowing404UnsetsPageId()
	{
		$mediaPage = $this->make404ThrowingMediaPage();
		$wp_query = new \Avorg\WP_Query();
		
		$wp_query->query_vars["page_id"] = 7;
		
		$mediaPage->throw404($wp_query);
		
		$this->assertArrayNotHasKey("page_id", $wp_query->query_vars);
	}
	
	public function testSetTitleMethod()
	{
		$presentation = $this->convertArrayToObjectRecursively([
		    "recordings" => [
		        "title" => "Presentation Title"
            ]
        ]);
		
		$this->mockAvorgApi->setReturnValue("getPresentation", $presentation);
		
		$result = $this->mediaPage->setTitle("old title");
		
		$this->assertEquals("Presentation Title - AudioVerse", $result);
	}
	
	public function testUsesPresentationIdQueryVar()
	{
		$this->mockWordPress->setReturnValue("get_option", 7);
		$this->mockWordPress->setReturnValue("get_the_ID", 7);
		$this->mockWordPress->setReturnValues("get_query_var",  7);
		
		$this->mediaPage->setTitle("old title");
		
		$this->mockAvorgApi->assertMethodCalledWith( "getPresentation", 7);
	}
	
	public function testSetTitleWorksWhenNoPresentation()
	{
		$this->mockAvorgApi->setReturnValue("getPresentation", null);
		
		$result = $this->mediaPage->setTitle("old title");
		
		$this->assertEquals("old title", $result);
	}

	/**
	 * @dataProvider getExpectedCallbacks
	 * @param $registrationMethod
	 * @param $hookId
	 * @param $callbackName
	 */
	public function testRegistersCallbacks($registrationMethod, $hookId, $callbackName)
	{
		$this->mediaPage->registerCallbacks();

		$this->mockWordPress->assertMethodCalledWith(
			$registrationMethod,
			$hookId,
			[$this->mediaPage, $callbackName]
		);
	}

	public function getExpectedCallbacks()
	{
		return [
			"Tab Title" => [
				"add_filter",
				"pre_get_document_title",
				"setTitle"
			],
			"Content Title" => [
				"add_filter",
				"the_title",
				"setTitle"
			],
			"Throw 404" => [
				"add_action",
				"parse_query",
				"throw404"
			],
			"Add UI" => [
				"add_filter",
				"the_content",
				"addUi"
			],
			"Create page on activation" => [
				"register_activation_hook",
				AVORG_BASE_PATH . "/wp-avorg-plugin.php",
				"createPage"
			],
			"Create page on init" => [
				"add_action",
				"init",
				"createPage"
			]
		];
	}

	public function testInsertsMediaDetailsPage()
	{
		$this->mockWordPress->setReturnValue("get_option", false);
		$this->mockWordPress->setReturnValue("get_post_status", false);

		$this->mediaPage->createPage();

		$this->mockWordPress->assertPageCreated("Media Detail", "Media Detail");
	}

	public function testDoesNotInsertPageTwice()
	{
		$this->mockWordPress->setReturnValue("get_option", ["post"]);
		$this->mockWordPress->setReturnValue("get_post_status", ["post"]);

		$this->mediaPage->createPage();

		$this->mockWordPress->assertPageNotCreated("Media Detail", "Media Detail");
	}
}