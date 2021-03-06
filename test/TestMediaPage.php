<?php

use Avorg\AvorgApi_exceptions;
use Avorg\Page\Detail;
use Avorg\Recording;
use Avorg\WP_Query;
use natlib\Factory;

final class TestMediaPage extends Avorg\TestCase
{
	/** @var Detail $mediaPage */
	protected $mediaPage;
	
	private function assertPlayerUiInjected()
	{
		$haystack = $this->makePlayerUiHaystack();
		
		$this->assertStringContainsString("playerUI", $haystack);
	}
	
	private function makePlayerUiHaystack()
	{
		$this->mockTwig->setReturnValue("render", "playerUI");
		
		return $this->mediaPage->addUi("");
	}
	
	protected function make404ThrowingMediaPage()
	{
		$avorgApi = new AvorgApi_exceptions();

		$factory = new Factory();

		$factory->injectObjects(
			$avorgApi,
			$this->mockPhp,
			$this->mockTwig,
			$this->mockWordPress
		);
		
		return $factory->secure("Avorg\\Page\\Presentation\\Detail");
	}
	
	protected function setUp(): void
	{
		parent::setUp();

		$this->mediaPage = $this->factory->secure("Avorg\\Page\\Presentation\\Detail");
	}
	
	public function testSavesMediaPageId()
	{
		$this->mockWordPress->setReturnValue("get_option", false);
		$this->mockWordPress->setReturnValue("get_post_status", false);
		$this->mockWordPress->setReturnValue("wp_insert_post", 7);
		
		$this->mediaPage->createPage();
		
		$this->mockWordPress->assertMethodCalledWith(
			"update_option",
			"avorg_page_id_avorg_page_presentation_detail",
			7
		);
	}
	
	public function testGetsMediaPageId()
	{
		$this->mediaPage->createPage();
		
		$this->mockWordPress->assertMethodCalledWith( "get_option", "avorg_page_id_avorg_page_presentation_detail");
	}
	
	public function testCreatesPageIfNoPageStatus()
	{
		$this->mockWordPress->setReturnValue("get_option", 7);
		$this->mockWordPress->setReturnValue("get_post_status", false);
		
		$this->mediaPage->createPage();

		$this->mockWordPress->assertPageCreated("Media Detail");
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
		$this->mockWordPress->passCurrentPageCheck();

		$this->assertPlayerUiInjected();
	}
	
	public function testPassesPageContent()
	{
		$haystack = $this->mediaPage->addUi("content");
		
		$this->assertStringContainsString("content", $haystack);
	}
	
	public function testUsesTwig()
	{
		$this->mockWordPress->passCurrentPageCheck();

		$this->mediaPage->addUi("content");
		
		$this->mockTwig->assertTwigTemplateRenderedWithData("page-presentation.twig", ["recording" => null]);
	}
	
	public function testOnlyOutputsMediaPageUIOnMediaPage()
	{
		$this->mockWordPress->setReturnValues("call", [1, 10]);
		
		$haystack = $this->makePlayerUiHaystack();
		
		$this->assertStringNotContainsString("playerUI", $haystack);
	}
	
	public function testPassesRecordingToTwig()
	{
		$this->mockAvorgApi->setReturnValue("getRecording", "recording");
		$this->mockWordPress->passCurrentPageCheck();
		
		$this->mediaPage->addUi("content");

		$this->mockTwig->assertAnyCallMatches( "render", function($call) {
            $callGlobal = $call[1]["avorg"];

		    return $callGlobal->recordings[0] instanceof \Avorg\DataObject\Recording;
        });
	}
	
	public function testGetsQueryVar()
	{
		$this->mockWordPress->passCurrentPageCheck();

		$this->mediaPage->addUi("content");
		
		$this->mockWordPress->assertMethodCalledWith( "get_query_var", "entity_id");
	}
	
	public function testGetsRecording()
	{
		$this->mockWordPress->passCurrentPageCheck();
		$this->mockWordPress->setReturnValues("get_query_var",  "54321");
		
		$this->mediaPage->addUi("content");
		
		$this->mockAvorgApi->assertMethodCalledWith( "getRecording", "54321");
	}
	
	public function testConvertsMediaPageIdStringToNumber()
	{
		$this->mockWordPress->setReturnValue("get_option", "7");
		$this->mockWordPress->setReturnValue("get_query_var", 7);
		
		$this->assertPlayerUiInjected();
	}
	
	public function testUsesRecordingIdToGetRecording()
	{
		$this->mockWordPress->passCurrentPageCheck();

		$wp_query = new WP_Query();

		$this->mockWordPress->setReturnValue("get_query_var", 42);
		
		$this->mediaPage->throw404($wp_query);
		
		$this->mockAvorgApi->assertMethodCalledWith( "getRecording", 42);
	}
	
	public function testDoesNotSet404IfRecordingExists()
	{
		$this->mockWordPress->passCurrentPageCheck();

		$wp_query = new WP_Query();
		$this->mockAvorgApi->setReturnValue("getRecording", new StdClass());
		
		$this->mediaPage->throw404($wp_query);
		
		$this->assertFalse($wp_query->was404set);
	}
	
	public function testHandlesExceptionAndThrows404()
	{
		$this->mockWordPress->passCurrentPageCheck();

		$mediaPage = $this->make404ThrowingMediaPage();
		$wp_query = new WP_Query();
		
		$mediaPage->throw404($wp_query);
		
		$this->assertTrue($wp_query->was404set);
		$this->mockWordPress->assertMethodCalledWith("status_header", 404);
	}

	public function testThrowing404UnsetsPageId()
	{
		$this->mockWordPress->passCurrentPageCheck();

		$mediaPage = $this->make404ThrowingMediaPage();
		$wp_query = new WP_Query();

		$wp_query->query_vars["page_id"] = 7;

		$mediaPage->throw404($wp_query);

		$this->assertArrayNotHasKey("page_id", $wp_query->query_vars);
	}

	public function testDoesNotThrow404IfNotCurrentPage()
	{
		$mediaPage = $this->make404ThrowingMediaPage();
		$wp_query = new WP_Query();

		$mediaPage->throw404($wp_query);

		$this->assertFalse($wp_query->was404set);
		$this->mockWordPress->assertMethodNotCalledWith("status_header", 404);
	}
	
	public function testSetTitleMethod()
	{
		$this->mockWordPress->passCurrentPageCheck();

		$recording = $this->arrayToObject([
			"title" => "Recording Title"
        ]);
		
		$this->mockAvorgApi->setReturnValue("getRecording", $recording);
		
		$result = $this->mediaPage->filterTitle("old title");
		
		$this->assertEquals("Recording Title - AudioVerse", $result);
	}
	
	public function testUsesRecordingIdQueryVar()
	{
		$this->mockWordPress->passCurrentPageCheck();
		$this->mockWordPress->setReturnValues("get_query_var",  7);
		
		$this->mediaPage->filterTitle("old title");
		
		$this->mockAvorgApi->assertMethodCalledWith( "getRecording", 7);
	}
	
	public function testSetTitleWorksWhenNoRecording()
	{
		$this->mockAvorgApi->setReturnValue("getRecording", null);
		
		$result = $this->mediaPage->filterTitle("old title");
		
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
				"filterTitle"
			],
			"Content Title" => [
				"add_filter",
				"the_title",
				"filterTitle"
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

		$this->mockWordPress->assertPageCreated("Media Detail");
	}

	public function testDoesNotInsertPageTwice()
	{
		$this->mockWordPress->setReturnValue("get_option", ["post"]);
		$this->mockWordPress->setReturnValue("get_post_status", ["post"]);

		$this->mediaPage->createPage();

		$this->mockWordPress->assertPageNotCreated("Media Detail", "Media Detail");
	}

	public function testFilterTitleTerminates()
	{
		$recording = $this->arrayToObject([
			"recordings" => [
				"title" => "Recording Title"
			]
		]);

		$this->mockAvorgApi->setReturnValue("getRecording", $recording);

		$result = $this->mediaPage->filterTitle("old title");

		$this->assertEquals("old title", $result);
	}

	/**
	 * @doesNotPerformAssertions
	 */
	public function testFilterTitleCatchesExceptions()
	{
		$this->mockWordPress->passCurrentPageCheck();

		$page = $this->make404ThrowingMediaPage();

		$page->filterTitle("old title");
	}
}