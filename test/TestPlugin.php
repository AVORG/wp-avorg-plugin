<?php

final class TestPlugin extends Avorg\TestCase
{
	/** @var \Avorg\Plugin $plugin */
	protected $plugin;
	
	protected function setUp()
	{
		parent::setUp();
		
		$this->mockWordPress->setReturnValue("call", 5);
		$this->plugin = $this->factory->secure("Avorg\\Plugin");
	}
	
	public function testInitInitsContentBits()
	{
		$contentBits = $this->factory->secure("Avorg\\ContentBits");
		
		$this->plugin->init();

		$this->mockWordPress->assertMethodCalledWith(
			"add_shortcode",
			"avorg-bits",
			[$contentBits, "renderShortcode"]
		);
	}
	
	public function testInitInitsRouter()
	{
		$this->plugin->init();
		
		$this->mockWordPress->assertMethodCalled("add_rewrite_rule");
	}
	
	public function testEnqueueScripts()
	{
		$this->plugin->enqueueScripts();
		
		$this->mockWordPress->assertMethodCalled("wp_enqueue_style");
	}
	
	public function testEnqueueScriptsGetsStylesheetUrl()
	{
		$this->plugin->enqueueScripts();
		
		$this->mockWordPress->assertMethodCalled("plugins_url");
	}
	
	public function testEnqueueScriptsUsesPathWhenEnqueuingStyle()
	{
		$this->mockWordPress->setReturnValue("plugins_url", "path");
		
		$this->plugin->enqueueScripts();
		
		$this->mockWordPress->assertMethodCalledWith(
			"wp_enqueue_style",
			"avorgStyle",
			"path"
		);
	}
	
	public function testInitsListShortcode()
	{
		$listShortcode = $this->factory->secure("Avorg\\ListShortcode");
		
		$this->plugin->init();
		
		$this->mockWordPress->assertMethodCalledWith(
			"add_shortcode",
			"avorg-list",
			[$listShortcode, "renderShortcode"]
		);
	}
	
	public function testEnqueuesVideoJsStyles()
	{
		$this->plugin->enqueueScripts();
		
		$this->mockWordPress->assertMethodCalledWith(
			"wp_enqueue_style",
			"avorgVideoJsStyle",
			"//vjs.zencdn.net/7.0/video-js.min.css"
		);
	}
	
	public function testRenderAdminNoticesOutputsDefaultNotices()
	{
		$this->plugin->renderAdminNotices();
		
		$this->mockWordPress->assertMethodCalled("settings_errors");
	}
	
	public function testErrorNoticePostedWhenPermalinksTurnedOff()
	{
		$this->mockWordPress->setReturnValue("call", false);
		
		$this->plugin->renderAdminNotices();
		
		$this->mockTwig->assertErrorRenderedWithMessage("AVORG Warning: Permalinks turned off!");
	}
	
	public function testChecksPermalinkStructure()
	{
		$this->plugin->renderAdminNotices();
		
		$this->mockWordPress->assertMethodCalledWith("get_option", "permalink_structure");
	}
	
	public function testGetsAvorgApiUser()
	{
		$this->plugin->renderAdminNotices();
		
		$this->mockWordPress->assertMethodCalledWith("get_option", "avorgApiUser");
	}
	
	public function testGetsAvorgApiPass()
	{
		$this->plugin->renderAdminNotices();
		
		$this->mockWordPress->assertMethodCalledWith("get_option", "avorgApiPass");
	}
	
	public function testErrorNoticePostedWhenNoAvorgApiUser()
	{
		$this->mockWordPress->setReturnValue("call", false);
		
		$this->plugin->renderAdminNotices();
		
		$this->mockTwig->assertErrorRenderedWithMessage("AVORG Warning: Missing API username!");
	}
	
	public function testErrorNoticePostedWhenNoAvorgApiPass()
	{
		$this->mockWordPress->setReturnValue("call", false);
		
		$this->plugin->renderAdminNotices();
		
		$this->mockTwig->assertErrorRenderedWithMessage("AVORG Warning: Missing API password!");
	}

	/**
	 * @dataProvider pageNameProvider
	 * @param $pageName
	 * @throws ReflectionException
	 */
	public function testRegistersPageCallbacks($pageName)
	{
		$this->factory->make("Avorg\\Plugin");

		$this->mockWordPress->assertPageRegistered($pageName);
	}

	public function pageNameProvider()
	{
		$pages = [
			"Media",
			"Topic\\Detail",
			"Playlist\\Detail"
		];

		$data = array_map(function($page) { return [$page]; }, $pages);

		return array_combine($pages, $data);
	}

	/**
	 * @param $action
	 * @param $callbackClass
	 * @param $callbackMethod
	 * @throws ReflectionException
	 * @dataProvider actionCallbackProvider
	 */
	public function testActionCallbacksRegistered($action, $callbackClass, $callbackMethod)
	{
		$this->mockWordPress->assertActionAdded($action, [
			$this->factory->secure("Avorg\\$callbackClass"),
			$callbackMethod
		]);
	}

	public function actionCallbackProvider()
	{
		return [
			[
				"wp_ajax_Avorg_AjaxAction_Recording",
				"AjaxAction\\Recording",
				"run"
			],
			[
				"init",
				"Localization",
				"loadLanguages"
			],
			[
				"wp_front_service_worker",
				"Pwa",
				"registerServiceWorker"
			],
			[
				"admin_notices",
				"Plugin",
				"renderAdminNotices"
			],
			[
				"add_meta_boxes",
				"ContentBits",
				"addMetaBoxes"
			],
			[
				"init",
				"Plugin",
				"init"
			],
			[
				"save_post",
				"ContentBits",
				"saveIdentifierMetaBox"
			],
			[
				"wp_enqueue_scripts",
				"Plugin",
				"enqueueScripts"
			]
		];
	}

	/**
	 * @dataProvider scriptPathProvider
	 * @param $path
	 * @param bool $shouldRegister
	 * @param bool $isRelative
	 */
	public function testRegistersScripts($path, $shouldRegister = true, $isRelative = false)
	{
		$this->mockWordPress->runActions("wp", "wp_enqueue_scripts");

		$fullPath = $isRelative ? "AVORG_BASE_URL/$path" : $path;

		$args = [
			"wp_enqueue_script",
			"Avorg_Script_" . sha1($fullPath),
			$fullPath
		];

		if ($shouldRegister) {
			$this->mockWordPress->assertMethodCalledWith(...$args);
		} else {
			$this->mockWordPress->assertMethodNotCalledWith(...$args);
		}
	}

	public function scriptPathProvider()
	{
		return [
			"video js" => ["//vjs.zencdn.net/7.0/video.min.js"],
			"video js hls" => ["https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-hls/5.14.1/videojs-contrib-hls.min.js"],
			"don't init playlist.js on other pages" => ["script/playlist.js", false, true],
			"polyfill.io" => ["https://polyfill.io/v3/polyfill.min.js?features=default"]
		];
	}

	/**
	 * @param $filter
	 * @param $callbackClass
	 * @param $callbackMethod
	 * @throws ReflectionException
	 * @dataProvider filterCallbackProvider
	 */
	public function testFilterCallbacksRegistered($filter, $callbackClass, $callbackMethod)
	{
		$this->mockWordPress->assertFilterAdded($filter, [
			$this->factory->secure("Avorg\\$callbackClass"),
			$callbackMethod
		]);
	}

	public function filterCallbackProvider()
	{
		return [
			[
				"locale",
				"Router",
				"setLocale"
			],
			[
				"redirect_canonical",
				"Router",
				"filterRedirect"
			]
		];
	}
}