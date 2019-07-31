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

	/**
	 * @dataProvider shortcodeProvider
	 */
	public function testInitsShortcodes($handle, $class)
	{
		$shortcode = $this->factory->secure($class);

		$this->plugin->init();

		$this->mockWordPress->assertMethodCalledWith(
			"add_shortcode",
			$handle,
			[$shortcode, "renderShortcode"]
		);
	}

	public function shortcodeProvider()
	{
		return [
			["avorg-bits", "Avorg\\ContentBits"],
			["avorg-list", "Avorg\\Shortcode\\Recordings"],
			["avorg-rss", "Avorg\\Shortcode\\Rss"]
		];
	}

	public function testInitInitsRouter()
	{
		$this->plugin->init();

		$this->mockWordPress->assertMethodCalled("add_rewrite_rule");
	}

	public function testEnqueueScripts()
	{
		$this->plugin->init();

		$this->mockWordPress->assertMethodCalled("wp_enqueue_style");
	}

	public function testEnqueueScriptsUsesPathWhenEnqueuingStyle()
	{
		$this->plugin->init();

		$this->mockWordPress->assertMethodCalledWith(
			"wp_enqueue_style",
			"avorgStyle",
			AVORG_BASE_URL . "/style/style.css"
		);
	}

	public function testEnqueuesEditorStyles()
	{
		$this->plugin->init();

		$this->mockWordPress->assertMethodCalledWith(
			"wp_enqueue_style",
			"avorgEditorStyle",
			AVORG_BASE_URL . "/style/editor.css"
		);
	}

	public function testEnqueuesVideoJsStyles()
	{
		$this->plugin->init();

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

		$this->mockTwig->assertErrorRenderedWithMessage("AVORG Warning: Permalinks turned off!",
			"/wp-admin/options-permalink.php");
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

		$this->mockTwig->assertErrorRenderedWithMessage("AVORG Warning: Missing API username!",
			"/wp-admin/admin.php?page=avorg");
	}

	public function testErrorNoticePostedWhenNoAvorgApiPass()
	{
		$this->mockWordPress->setReturnValue("call", false);

		$this->plugin->renderAdminNotices();

		$this->mockTwig->assertErrorRenderedWithMessage("AVORG Warning: Missing API password!",
			"/wp-admin/admin.php?page=avorg");
	}

	/**
	 * @dataProvider pageNameProvider
	 * @param $pageName
	 * @throws ReflectionException
	 */
	public function testRegistersPageCallbacks($pageName)
	{
		$this->plugin->registerCallbacks();

		$this->mockWordPress->assertPageRegistered($pageName);
	}

	public function pageNameProvider()
	{
		$pages = [
			"Presentation\\Detail",
			"Topic\\Detail",
			"Playlist\\Detail"
		];

		$data = array_map(function ($page) {
			return [$page];
		}, $pages);

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
		$this->plugin->registerCallbacks();

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
				'enqueue_block_editor_assets',
				'BlockRepository',
				'enqueueBlockEditorAssets'
			],
			[
				"admin_menu",
				'AdminPanel',
				'register'
			],
			[
				'enqueue_block_assets',
				'BlockRepository',
				'enqueueBlockFrontendAssets'
			],
		];
	}

	/**
	 * @dataProvider scriptPathProvider
	 * @param $path
	 * @param bool $shouldRegister
	 * @param bool $isRelative
	 * @param string $action
	 */
	public function testRegistersScripts(
		$path,
		$shouldRegister = true,
		$isRelative = false,
		$action = "wp_enqueue_scripts"
	)
	{
		$this->plugin->registerCallbacks();

		$this->mockWordPress->runActions($action);

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
		$this->plugin->registerCallbacks();

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

	public function testChecksForXwpPwaPlugin()
	{
		$this->plugin->renderAdminNotices();

		$this->mockWordPress->assertMethodCalledWith("is_plugin_active", "pwa/pwa.php");
	}

	public function testRendersNoticeIfPwaPluginInactive()
	{
		$this->plugin->renderAdminNotices();

		$this->mockTwig->assertErrorRenderedWithMessage(
			"AVORG Warning: PWA plugin not active!",
			"/wp-admin/plugins.php"
		);
	}

	public function testDoesNotRenderNoticeIfPwaPluginActive()
	{
		$this->mockWordPress->setReturnValue("is_plugin_active", TRUE);

		$this->plugin->renderAdminNotices();

		$this->mockTwig->assertErrorNotRenderedWithMessage("AVORG Warning: PWA plugin not active!");
	}

	public function testRegistersActivationHook()
	{
		$this->plugin->registerCallbacks();

		$this->mockWordPress->assertMethodCalledWith('register_activation_hook', AVORG_PLUGIN_FILE,
			[$this->plugin, 'activate']);
	}
}