<?php

final class TestRouter extends Avorg\TestCase
{
	/** @var \Avorg\Router $router */
	protected $router;

	public function setUp()
	{
		parent::setUp();

		$this->router = $this->factory->get("Router");
	}

	private function getAddRewriteCalls()
	{
		return $this->mockWordPress->getCalls("add_rewrite_rule");
	}

	public function testCallsWordPress()
	{
		$this->router->activate();

		$this->assertCalled($this->mockWordPress, "flush_rewrite_rules");
	}

	public function testAssignsHighPriority()
	{
		$this->router->activate();

		$addRewriteCalls = $this->getAddRewriteCalls();
		$priority = $addRewriteCalls[0][2];

		$this->assertEquals("top", $priority);
	}

	/**
	 * @dataProvider rewriteInputOutputProvider
	 * @param $inputUrl
	 * @param $outputUrl
	 */
	public function testRewriteRuleRewritesCorrectly($inputUrl, $outputUrl)
	{
		$this->mockWordPress->setMappedReturnValues("get_option", [
			["avorgMediaPageId", 10],
			["page_on_front", "HOME_PAGE_ID"],
			["avorgTopicPageId", "TOPIC_PAGE_ID"]
		]);

		$this->router->activate();

		$addRewriteCalls = $this->getAddRewriteCalls();

		$results = array_map(function ($call) use ($inputUrl) {
			$regex = $call[0];
			$redirect = $call[1];

			preg_match("/$regex/", $inputUrl, $matches);

			return eval("return \"$redirect\";");
		}, $addRewriteCalls);

		$resultsExport = var_export($results, true);
		$errorMessage = "Input: $inputUrl\r\nExpected Output: $outputUrl\r\nHaystack:\r\n$resultsExport";

		$this->assertContains(
			$outputUrl,
			$results,
			$errorMessage
		);
	}

	public function rewriteInputOutputProvider()
	{
		return [
			[
				"english/sermons/recordings/316/parents-how.html",
				"index.php?page_id=10&presentation_id=316"
			],
			[
				"english/sermons/recordings/2913/generation-of-youth-for-christ.html",
				"index.php?page_id=10&presentation_id=2913"
			],
			[
				"english/sermons/recordings/3914/killing-the-fat-king.html",
				"index.php?page_id=10&presentation_id=3914"
			],
			[
				"english/sermons/recordings/17663/2-new-theology--halfhearted-christians.html",
				"index.php?page_id=10&presentation_id=17663"
			],
			[
				"english/sermons/recordings/17831/the-last-attack.html",
				"index.php?page_id=10&presentation_id=17831"
			],
			[
				"english/sermons/recordings/17833/single-and-satisfied.html",
				"index.php?page_id=10&presentation_id=17833"
			],
			[
				"english/sermons/recordings/316/parents-how.html/",
				"index.php?page_id=10&presentation_id=316"
			],
			[
				"english/sermons/recordings/2913/generation-of-youth-for-christ.html/",
				"index.php?page_id=10&presentation_id=2913"
			],
			[
				"english/sermons/recordings/3914/killing-the-fat-king.html/",
				"index.php?page_id=10&presentation_id=3914"
			],
			[
				"english/sermons/recordings/17663/2-new-theology--halfhearted-christians.html/",
				"index.php?page_id=10&presentation_id=17663"
			],
			[
				"english/sermons/recordings/17831/the-last-attack.html/",
				"index.php?page_id=10&presentation_id=17831"
			],
			[
				"english/sermons/recordings/17833/single-and-satisfied.html/",
				"index.php?page_id=10&presentation_id=17833"
			],
			[
				"espanol/sermones/grabaciones/17283/saludismo.html",
				"index.php?page_id=10&presentation_id=17283"
			],
			[
				"francais/predications/enregistrements/3839/jesus-sur-le-mont-des-oliviers.html",
				"index.php?page_id=10&presentation_id=3839"
			],
			[
				"espanol",
				"index.php?page_id=HOME_PAGE_ID"
			],
			[
				"espanol/",
				"index.php?page_id=HOME_PAGE_ID"
			],
			[
				"english/topics/102/great-controversy.html",
				"index.php?page_id=TOPIC_PAGE_ID&topic_id=102"
			]
		];
	}

	public function testFlushesRewireRules()
	{
		$this->router->activate();

		$this->assertCalledWith($this->mockWordPress,  "flush_rewrite_rules");
	}

	public function testUsesSavedMediaPageId()
	{
		$this->mockWordPress->setReturnValue("get_option", 3);

		$this->router->activate();

		$addRewriteCalls = $this->getAddRewriteCalls();
		$redirect = $addRewriteCalls[0][1];

		$this->assertContains("page_id=3", $redirect);
	}

	public function testSetLocaleFunctionExists()
	{
		$this->assertTrue(method_exists($this->router, "setLocale"));
	}

	public function testSetLocaleFunctionReturnsPreviousLang()
	{
		$this->assertEquals("lang", $this->router->setLocale("lang"));
	}

	public function testSetsSpanishLocale()
	{
		$_SERVER["REQUEST_URI"] = "/espanol";

		$this->assertEquals("es_ES", $this->router->setLocale("lang"));
	}

	public function testUsesLanguageFile()
	{
		$_SERVER["REQUEST_URI"] = "/deutsch";

		$this->assertEquals("de_DE", $this->router->setLocale("lang"));
	}

	/**
	 * @dataProvider redirectFilteringProvider
	 * @param $requestUrl
	 * @param $shouldAllowRedirect
	 */
	public function testRedirectFiltering($requestUrl, $shouldAllowRedirect)
	{
		$_SERVER["HTTP_HOST"] = "localhost:8080";
		$_SERVER["REQUEST_URI"] = $requestUrl;
		$path = parse_url($requestUrl, PHP_URL_PATH);
		$fullRequestUrl = $_SERVER["HTTP_HOST"] . $path;
		$expected = $shouldAllowRedirect ? "redirect_url" : "http://" . $fullRequestUrl;
		$result = $this->router->filterRedirect("redirect_url");

		$this->assertEquals($expected, $result);
	}

	public function redirectFilteringProvider()
	{
		return [
			"Spanish Route" => ["localhost:8080/espanol", FALSE],
			"No Language Route" => ["localhost:8080/path", TRUE],
			"Complex Spanish Route" => ["localhost:8080/espanol/path/to/page", FALSE],
			"French Route" => ["localhost:8080/francais", FALSE],
			"Spanish Production Url" => ["https://audioverse.org/espanol", FALSE],
			"No Language Production Url" => ["https://audioverse.org/path/to/page", TRUE],
			"Spanish Path" => ["/espanol", FALSE],
			"No Language Path" => ["/path", TRUE],
			"Complex Spanish Path" => ["/espanol/path/to/page", FALSE],
			"French Path" => ["/francais", FALSE]
		];
	}

	public function testGetUrlForApiRecording()
	{
		$apiRecording = $this->convertArrayToObjectRecursively([
			"lang" => "en",
			"id" => "1836",
			"title" => 'E.P. Daniels and True Revival'
		]);

		$url = $this->router->getUrlForApiRecording($apiRecording);

		$this->assertEquals(
			"/english/sermons/recordings/1836/ep-daniels-and-true-revival.html",
			$url
		);
	}

	/**
	 * @dataProvider rewriteTagProvider
	 * @param $tag
	 * @param $regex
	 */
	public function testRegistersRewriteTags($tag, $regex)
	{
		$this->router->activate();

		$this->mockWordPress->assertMethodCalledWith(
			"add_rewrite_tag",
			$tag,
			$regex
		);
	}

	public function rewriteTagProvider()
	{
		return [
			"presentation_id" => [ "%presentation_id%", "(\d+)" ],
			"topic_id" => [ "%topic_id%", "(\d+)" ]
		];
	}
}