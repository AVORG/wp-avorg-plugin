<?php

use Avorg\Plugin;

final class TestPlugin extends Avorg\TestCase
{
	/** @var Plugin $plugin */
	protected $plugin;

	protected function setUp(): void
	{
		parent::setUp();

		$this->mockWordPress->setReturnValue("call", 5);
		$this->plugin = $this->factory->secure("Avorg\\Plugin");
	}

	public function testEnqueueScripts()
	{
		$this->plugin->enqueueStyles();

		$this->mockWordPress->assertMethodCalled("wp_enqueue_style");
	}

	public function testEnqueueScriptsUsesPathWhenEnqueuingStyle()
	{
		$this->plugin->enqueueStyles();

		$this->mockWordPress->assertMethodCalledWith(
			"wp_enqueue_style",
			"avorgStyle",
			AVORG_BASE_URL . "/style/style.css"
		);
	}

	public function testEnqueuesEditorStyles()
	{
		$this->plugin->enqueueStyles();

		$this->mockWordPress->assertMethodCalledWith(
			"wp_enqueue_style",
			"avorgEditorStyle",
			AVORG_BASE_URL . "/style/editor.css"
		);
	}

	public function testEnqueuesVideoJsStyles()
	{
		$this->plugin->enqueueStyles();

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
				"enqueueStyles"
			],
			[
				"save_post",
				"ContentBits",
				"saveIdentifierMetaBox"
			],
			[
				"admin_menu",
				'AdminPanel',
				'register'
			],
            [
                'init',
                'Block\\RelatedSermons',
                'init'
            ],
            [
                'init',
                'Block\\Placeholder',
                'init'
            ]
		];
	}

    /**
     * @dataProvider scriptPathProvider
     * @param $path
     * @param array $options
     */
	public function testRegistersScripts(
		$path,
		$options = []
	)
	{
	    $shouldRegister = $this->arrSafe('should_register', $options, true);
	    $isRelative = $this->arrSafe('is_relative', $options, false);
	    $action = $this->arrSafe('action', $options, "wp_enqueue_scripts");
	    $deps = $this->arrSafe('deps', $options, null);
	    $in_footer = $options['in_footer'] ?? false;

        $fullPath = $isRelative ? "AVORG_BASE_URL/$path" : $path;

        $defaultHandle = "Avorg_Script_" . sha1($fullPath);
        $handle = $this->arrSafe("handle", $options, $defaultHandle);

		$this->plugin->registerCallbacks();

		$this->mockWordPress->runActions($action);

		$args = [
			"wp_enqueue_script",
			$handle,
			$fullPath,
            $deps,
            null,
            $in_footer
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
			"don't init playlist.js on other pages" => ["script/playlist.js", [
			    'should_register' => false,
                'is_relative' => true
            ]],
            "frontend" => ["dist/frontend.js", [
                'is_relative' => true,
                'handle' => 'Avorg_Script_Frontend',
                'in_footer' => true,
                'deps' => ['wp-element']
            ]],
            "editor" => ["dist/editor.js", [
                'is_relative' => true,
                'action' => 'enqueue_block_editor_assets',
                'deps' => ['wp-element', 'wp-blocks', 'wp-components', 'wp-i18n'],
                'handle' => 'Avorg_Script_Editor'
            ]],
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
}