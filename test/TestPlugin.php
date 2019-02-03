<?php

final class TestPlugin extends Avorg\TestCase
{
	/** @var \Avorg\Plugin $plugin */
	protected $plugin;
	
	protected function setUp()
	{
		parent::setUp();
		
		$this->mockWordPress->setReturnValue("call", 5);
		$this->plugin = $this->factory->get("Plugin");
	}
	
	public function testInitInitsContentBits()
	{
		$contentBits = $this->factory->get("ContentBits");
		
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
		$listShortcode = $this->factory->get("ListShortcode");
		
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
	
	public function testEnqueuesVideoJsScript()
	{
		$this->plugin->enqueueScripts();
		
		$this->mockWordPress->assertMethodCalledWith(
			"wp_enqueue_script",
			"avorgVideoJsScript",
			"//vjs.zencdn.net/7.0/video.min.js"
		);
	}
	
	public function testEnqueuesVideoJsHlsScript()
	{
		$this->plugin->enqueueScripts();
		
		$this->mockWordPress->assertMethodCalledWith(
			"wp_enqueue_script",
			"avorgVideoJsHlsScript",
			"https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-hls/5.14.1/videojs-contrib-hls.min.js"
		);
	}
	
	public function testSubscribesToAdminNoticeActionUsingAppropriateCallBackMethod()
	{
		$this->mockWordPress->assertMethodCalledWith(
			"add_action",
			"admin_notices",
			[$this->plugin, "renderAdminNotices"]
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
		$this->mockWordPress->assertPageRegistered($pageName);
	}

	public function pageNameProvider()
	{
		return [
			"Media Page" => ["Media"],
			"Topic Page" => ["Topic"],
			"Playlist Page" => ["Playlist"]
		];
	}

	public function testRegistersPwaCallbacks()
	{
		$pwa = $this->factory->get("Pwa");

		$this->mockWordPress->assertMethodCalledWith(
			"add_action",
			"wp_front_service_worker",
			[$pwa, "registerServiceWorker"]
		);
	}

	public function testRegistersLocalizationCallbacks()
	{
		$localization = $this->factory->get("Localization");

		$this->mockWordPress->assertMethodCalledWith(
			"add_action",
			"init",
			[$localization, "loadLanguages"]
		);
	}
}