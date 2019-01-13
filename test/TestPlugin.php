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
	
	public function testActivatesRouterOnPluginActivate()
	{
		$this->plugin->activate();
		
		$this->mockWordPress->assertWordPressFunctionCalled("flush_rewrite_rules");
	}
	
	public function testInitInitsContentBits()
	{
		$contentBits = $this->factory->get("ContentBits");
		
		$this->plugin->init();

		$this->mockWordPress->assertWordPressFunctionCalledWith(
			"add_shortcode",
			"avorg-bits",
			[$contentBits, "renderShortcode"]
		);
	}
	
	public function testInitInitsRouter()
	{
		$this->plugin->init();
		
		$this->mockWordPress->assertWordPressFunctionCalled("add_rewrite_rule");
	}
	
	public function testEnqueueScripts()
	{
		$this->plugin->enqueueScripts();
		
		$this->mockWordPress->assertWordPressFunctionCalled("wp_enqueue_style");
	}
	
	public function testEnqueueScriptsGetsStylesheetUrl()
	{
		$this->plugin->enqueueScripts();
		
		$this->mockWordPress->assertWordPressFunctionCalled("plugins_url");
	}
	
	public function testEnqueueScriptsUsesPathWhenEnqueuingStyle()
	{
		$this->mockWordPress->setReturnValue("call", "path");
		
		$this->plugin->enqueueScripts();
		
		$this->assertCalledWith(
			$this->mockWordPress,
			"call",
			"wp_enqueue_style",
			"avorgStyle",
			"path"
		);
	}
	
	public function testInitsListShortcode()
	{
		$listShortcode = $this->factory->get("ListShortcode");
		
		$this->plugin->init();
		
		$this->mockWordPress->assertWordPressFunctionCalledWith(
			"add_shortcode",
			"avorg-list",
			[$listShortcode, "renderShortcode"]
		);
	}
	
	public function testEnqueuesVideoJsStyles()
	{
		$this->plugin->enqueueScripts();
		
		$this->mockWordPress->assertWordPressFunctionCalledWith(
			"wp_enqueue_style",
			"avorgVideoJsStyle",
			"//vjs.zencdn.net/7.0/video-js.min.css"
		);
	}
	
	public function testEnqueuesVideoJsScript()
	{
		$this->plugin->enqueueScripts();
		
		$this->mockWordPress->assertWordPressFunctionCalledWith(
			"wp_enqueue_script",
			"avorgVideoJsScript",
			"//vjs.zencdn.net/7.0/video.min.js"
		);
	}
	
	public function testEnqueuesVideoJsHlsScript()
	{
		$this->plugin->enqueueScripts();
		
		$this->mockWordPress->assertWordPressFunctionCalledWith(
			"wp_enqueue_script",
			"avorgVideoJsHlsScript",
			"https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-hls/5.14.1/videojs-contrib-hls.min.js"
		);
	}
	
	public function testSubscribesToAdminNoticeActionUsingAppropriateCallBackMethod()
	{
		$this->mockWordPress->assertWordPressFunctionCalledWith(
			"add_action",
			"admin_notices",
			[$this->plugin, "renderAdminNotices"]
		);
	}
	
	public function testRenderAdminNoticesOutputsDefaultNotices()
	{
		$this->plugin->renderAdminNotices();
		
		$this->mockWordPress->assertWordPressFunctionCalled("settings_errors");
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
		
		$this->mockWordPress->assertWordPressFunctionCalledWith("get_option", "permalink_structure");
	}
	
	public function testGetsAvorgApiUser()
	{
		$this->plugin->renderAdminNotices();
		
		$this->mockWordPress->assertWordPressFunctionCalledWith("get_option", "avorgApiUser");
	}
	
	public function testGetsAvorgApiPass()
	{
		$this->plugin->renderAdminNotices();
		
		$this->mockWordPress->assertWordPressFunctionCalledWith("get_option", "avorgApiPass");
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
		$pageObject = $this->factory->get("Page\\$pageName");

		$this->mockWordPress->assertWordPressFunctionCalledWith(
			"add_filter",
			"the_content",
			[$pageObject, "addUi"]
		);
	}

	public function pageNameProvider()
	{
		return [
			"Media Page" => ["Media"],
			"Topic Page" => ["Topic"]
		];
	}

	public function testRegistersPwaCallbacks()
	{
		$pwa = $this->factory->get("Pwa");

		$this->mockWordPress->assertMethodCalledWith(
			"call",
			"add_action",
			"wp_front_service_worker",
			[$pwa, "registerServiceWorker"]
		);
	}
}