<?php

final class TestRouter extends Avorg\TestCase
{
	/** @var \Avorg\Router $router */
	protected $router;

	public function setUp()
	{
		parent::setUp();

		$this->router = $this->factory->secure("Avorg\\Router");
	}

	public function testAssignsHighPriority()
	{
		$this->router->activate();

		$addRewriteCalls = $this->mockWordPress->getCalls("add_rewrite_rule");
		$priority = $addRewriteCalls[0][2];

		$this->assertEquals("top", $priority);
	}

	/**
	 * @dataProvider pageRouteProvider
	 * @param $inputUrl
	 * @param $outputUrl
	 */
	public function testPageRoutes($inputUrl, $outputUrl)
	{
		$inputUrl = ltrim($inputUrl, "/");
		$addRewriteCalls = $this->getRewriteRules();

		$results = array_map(function ($call) use ($inputUrl) {
			$regex = $call[0];
			$redirect = $call[1];

			preg_match("/$regex/", $inputUrl, $matches);

			return eval("return \"$redirect\";");
		}, $addRewriteCalls);

		$this->assertRewrittenUrlMatchesExpectedUrl($inputUrl, $outputUrl, $results, $addRewriteCalls);
	}

	public function pageRouteProvider()
	{
		return [
			[
				"english/sermons/recordings/316/parents-how.html",
				"index.php?page_id=MEDIA_PAGE_ID&language=english&entity_id=316&slug=parents-how.html"
			],
			[
				"english/sermons/recordings/2913/generation-of-youth-for-christ.html",
				"index.php?page_id=MEDIA_PAGE_ID&language=english&entity_id=2913&slug=generation-of-youth-for-christ.html"
			],
			[
				"english/sermons/recordings/3914/killing-the-fat-king.html",
				"index.php?page_id=MEDIA_PAGE_ID&language=english&entity_id=3914&slug=killing-the-fat-king.html"
			],
			[
				"english/sermons/recordings/17663/2-new-theology--halfhearted-christians.html",
				"index.php?page_id=MEDIA_PAGE_ID&language=english&entity_id=17663&slug=2-new-theology--halfhearted-christians.html"
			],
			[
				"english/sermons/recordings/17831/the-last-attack.html",
				"index.php?page_id=MEDIA_PAGE_ID&language=english&entity_id=17831&slug=the-last-attack.html"
			],
			[
				"english/sermons/recordings/17833/single-and-satisfied.html",
				"index.php?page_id=MEDIA_PAGE_ID&language=english&entity_id=17833&slug=single-and-satisfied.html"
			],
			[
				"english/sermons/recordings/316/parents-how.html/",
				"index.php?page_id=MEDIA_PAGE_ID&language=english&entity_id=316&slug=parents-how.html"
			],
			[
				"english/sermons/recordings/2913/generation-of-youth-for-christ.html/",
				"index.php?page_id=MEDIA_PAGE_ID&language=english&entity_id=2913&slug=generation-of-youth-for-christ.html"
			],
			[
				"english/sermons/recordings/3914/killing-the-fat-king.html/",
				"index.php?page_id=MEDIA_PAGE_ID&language=english&entity_id=3914&slug=killing-the-fat-king.html"
			],
			[
				"english/sermons/recordings/17663/2-new-theology--halfhearted-christians.html/",
				"index.php?page_id=MEDIA_PAGE_ID&language=english&entity_id=17663&slug=2-new-theology--halfhearted-christians.html"
			],
			[
				"english/sermons/recordings/17831/the-last-attack.html/",
				"index.php?page_id=MEDIA_PAGE_ID&language=english&entity_id=17831&slug=the-last-attack.html"
			],
			[
				"english/sermons/recordings/17833/single-and-satisfied.html/",
				"index.php?page_id=MEDIA_PAGE_ID&language=english&entity_id=17833&slug=single-and-satisfied.html"
			],
			[
				"espanol/sermones/grabaciones/17283/saludismo.html",
				"index.php?page_id=MEDIA_PAGE_ID&language=espanol&entity_id=17283&slug=saludismo.html"
			],
			[
				"francais/predications/enregistrements/3839/jesus-sur-le-mont-des-oliviers.html",
				"index.php?page_id=MEDIA_PAGE_ID&language=francais&entity_id=3839&slug=jesus-sur-le-mont-des-oliviers.html"
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
				"index.php?page_id=TOPIC_DETAIL_PAGE_ID&language=english&entity_id=102&slug=great-controversy.html"
			],
			[
				"english/playlists/lists/14/how-to-be-saved.html",
				"index.php?page_id=PLAYLIST_DETAIL_PAGE_ID&language=english&entity_id=14&slug=how-to-be-saved.html"
			],
			[
				"english/topics",
				"index.php?page_id=TOPIC_LISTING_PAGE_ID&language=english"
			],
			[
				"english/audiobibles/volumes",
				"index.php?page_id=BIBLE_LISTING_PAGE_ID&language=english"
			],
			[
				"english/audiobibles/books/ENGKJV/1",
				"index.php?page_id=BIBLE_DETAIL_PAGE_ID&language=english&version=ENGKJV&drama=1"
			],
			[
				"english/topics/887/agriculture.html",
				"index.php?page_id=TOPIC_DETAIL_PAGE_ID&language=english&entity_id=887&slug=agriculture.html"
			],
			[
				"/english/audiobooks/stories",
				"index.php?page_id=STORY_LISTING_PAGE_ID&language=english"
			],
			[
				"/english/audiobooks/stories/1167/acts-of-the-apostles.html",
				"index.php?page_id=STORY_DETAIL_PAGE_ID&language=english&entity_id=1167&slug=acts-of-the-apostles.html"
			],
			[
				"/english/sermons/conferences",
				"index.php?page_id=CONFERENCE_LISTING_PAGE_ID&language=english"
			],
			[
				"/english/sermons/conferences/293/acf-institute-2017-never-alone.html",
				"index.php?page_id=CONFERENCE_DETAIL_PAGE_ID&language=english&entity_id=293&slug=acf-institute-2017-never-alone.html"
			],
			[
				"/english/sponsors",
				"index.php?page_id=SPONSOR_LISTING_PAGE_ID&language=english"
			],
			[
				"/english/sermons/series",
				"index.php?page_id=SERIES_LISTING_PAGE_ID&language=english"
			],
			[
				"english/audiobibles/books/ENGKJV/O/Josh/1",
				"index.php?page_id=BIBLEBOOK_DETAIL_PAGE_ID&language=english&bible_id=ENGKJV&testament_id=O&book_id=Josh&chapter_id=1"
			]
		];
	}

	/**
	 * @param $inputUrl
	 * @param $outputUrl
	 * @dataProvider outputRouteProvider
	 */
	public function testEndpointRoutes($inputUrl, $outputUrl)
	{
		$inputUrl = ltrim($inputUrl, "/");
		$addRewriteCalls = $this->getRewriteRules();

		$results = array_map(function ($call) use ($inputUrl) {
			$regex = $call[0];
			$redirect = $call[1];

			return preg_replace("/$regex/", $redirect, $inputUrl);
		}, $addRewriteCalls);

		$this->assertRewrittenUrlMatchesExpectedUrl($inputUrl, $outputUrl, $results, $addRewriteCalls);
	}

	public function outputRouteProvider()
	{
		return [
			[
				"english/sermons/presenters/podcast/134/latest/david-shin.xml",
				"endpoint.php?endpoint_id=Avorg_Endpoint_RssEndpoint_Speaker&language=english&entity_id=134&slug=david-shin.xml"
			],
			[
				"api/presentation/123",
				"endpoint.php?endpoint_id=Avorg_Endpoint_Recording&entity_id=123"
			],
			[
				"english/podcasts/latest",
				"endpoint.php?endpoint_id=Avorg_Endpoint_RssEndpoint_Latest&language=english"
			],
			[
				"english/podcasts/trending",
				"endpoint.php?endpoint_id=Avorg_Endpoint_RssEndpoint_Trending&language=english"
			],
			[
				"english/topics/podcast/887/agriculture.xml",
				"endpoint.php?endpoint_id=Avorg_Endpoint_RssEndpoint_Topic&language=english&entity_id=887&slug=agriculture.xml"
			],
			[
				"/english/sponsors/podcast/49/latest/a-loud-and-clear-call-ministries.xml",
				"endpoint.php?endpoint_id=Avorg_Endpoint_RssEndpoint_Sponsor&language=english&entity_id=49&slug=a-loud-and-clear-call-ministries.xml"
			]
		];
	}

	public function testUsesSavedMediaPageId()
	{
		$this->mockWordPress->setReturnValue("get_option", 3);

		$this->router->activate();

		$addRewriteCalls = $this->mockWordPress->getCalls("add_rewrite_rule");
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
	 * @param $requestUri
	 * @param $shouldAllowRedirect
	 */
	public function testRedirectFiltering($requestUri, $shouldAllowRedirect)
	{
		$_SERVER["HTTP_HOST"] = "localhost:8080";
		$_SERVER["REQUEST_URI"] = $requestUri;
		$path = parse_url($requestUri, PHP_URL_PATH);
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
			"entity_id" => [ "%entity_id%", "([0-9]+)" ],
			"endpoint_id" => [ "%endpoint_id%", "([\w-\.]+)" ]
		];
	}

	public function testBuildUrlIncludesHost()
	{
		$result = $this->router->buildUrl("Avorg\Page\Playlist\Listing");

		$this->assertStringStartsWith("http://localhost:8080", $result);
	}

	public function testBuildUrlIncludesLanguage()
	{
		$result = $this->router->buildUrl("Avorg\Page\Playlist\Listing");

		$this->assertStringEndsWith("english/playlists/lists", $result);
	}

	public function testBuildUrlIncludesVariables()
	{
		$result = $this->router->buildUrl("Avorg\Page\Topic\Detail", [
			"entity_id" => 3,
			"slug" => "my-slug.html"
		]);

		$this->assertStringEndsWith("english/topics/3/my-slug.html", $result);
	}

	public function testLocalizesUrls()
	{
		$this->mockWordPress->setReturnValue("get_locale", "es_ES");

		$result = $this->router->buildUrl("Avorg\Page\Presenter\Listing", [
			"letter" => "D"
		]);

		$this->assertStringEndsWith("espanol/sermones/presenters/D", $result);
	}

	/**
	 * @return array
	 */
	private function getRewriteRules()
	{
		$this->mockWordPress->setMappedReturnValues("get_option", [
			["page_on_front", "HOME_PAGE_ID"]
		]);

		$this->mockWordPress->setReturnCallback("get_option", function (...$args) {
			$optionId = $args[0];
			$pageIdOptionPrefix = "avorg_page_id_avorg_page_";
			$isPageIdOption = strstr($optionId, $pageIdOptionPrefix) !== false;

			if (!$isPageIdOption) return STUB_NULL;

			$pageName = str_replace($pageIdOptionPrefix, "", $optionId);

			return strtoupper($pageName . "_PAGE_ID");
		});

		$this->router->activate();

		return $this->mockWordPress->getCalls("add_rewrite_rule");
	}

	/**
	 * @param $inputUrl
	 * @param $outputUrl
	 * @param $results
	 * @param $rewriteRules
	 */
	private function assertRewrittenUrlMatchesExpectedUrl($inputUrl, $outputUrl, $results, $rewriteRules)
	{
		$resultsExport = var_export($results, true);
		$rewriteRulesExport = var_export($rewriteRules, true);
		$getOptionCalls = var_export($this->mockWordPress->getCalls("get_option"), true);
		$errorMessage = <<<EOM
Input: $inputUrl

Expected Output: $outputUrl

Haystack:
$resultsExport

Rewrite Rules:
$rewriteRulesExport

Calls to wp->get_option():
$getOptionCalls
EOM;

		$this->assertContains(
			$outputUrl,
			$results,
			$errorMessage
		);
	}
}