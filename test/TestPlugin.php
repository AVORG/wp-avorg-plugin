<?php

final class TestPlugin extends Avorg\TestCase
{
	/** @var \Avorg\Plugin $plugin */
	protected $plugin;
	
	private $mediaPageInsertCall = array("wp_insert_post", array(
		"post_content" => "Media Detail",
		"post_title" => "Media Detail",
		"post_status" => "publish",
		"post_type" => "page"
	), true);
	
	protected function setUp()
	{
		parent::setUp();
		
		$this->mockWordPress->setReturnValue("call", 5);
		$this->plugin = $this->factory->getPlugin();
	}
	
	public function testActivatesRouterOnPluginActivate()
	{
		$plugin = $this->factory->getPlugin();
		
		$plugin->activate();
		
		$this->assertWordPressFunctionCalled("flush_rewrite_rules");
	}
	
	public function testInitInitsContentBits()
	{
		$plugin = $this->factory->getPlugin();
		$contentBits = $this->factory->getContentBits();
		
		$plugin->init();

		$this->assertWordPressFunctionCalledWith(
			"add_shortcode",
			"avorg-bits",
			[$contentBits, "renderShortcode"]
		);
	}
	
	public function testInitInitsRouter()
	{
		$plugin = $this->factory->getPlugin();
		
		$plugin->init();
		
		$this->assertWordPressFunctionCalled("add_rewrite_rule");
	}
	
	public function testEnqueueScripts()
	{
		$this->plugin->enqueueScripts();
		
		$this->assertWordPressFunctionCalled("wp_enqueue_style");
	}
	
	public function testEnqueueScriptsGetsStylesheetUrl()
	{
		$this->plugin->enqueueScripts();
		
		$this->assertWordPressFunctionCalled("plugins_url");
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
		$plugin = $this->factory->getPlugin();
		$listShortcode = $this->factory->getListShortcode();
		
		$plugin->init();
		
		$this->assertWordPressFunctionCalledWith(
			"add_shortcode",
			"avorg-list",
			[$listShortcode, "renderShortcode"]
		);
	}
	
	public function testEnqueuesVideoJsStyles()
	{
		$this->plugin->enqueueScripts();
		
		$this->assertWordPressFunctionCalledWith(
			"wp_enqueue_style",
			"avorgVideoJsStyle",
			"//vjs.zencdn.net/7.0/video-js.min.css"
		);
	}
	
	public function testEnqueuesVideoJsScript()
	{
		$this->plugin->enqueueScripts();
		
		$this->assertWordPressFunctionCalledWith(
			"wp_enqueue_script",
			"avorgVideoJsScript",
			"//vjs.zencdn.net/7.0/video.min.js"
		);
	}
	
	public function testEnqueuesVideoJsHlsScript()
	{
		$this->plugin->enqueueScripts();
		
		$this->assertWordPressFunctionCalledWith(
			"wp_enqueue_script",
			"avorgVideoJsHlsScript",
			"https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-hls/5.14.1/videojs-contrib-hls.min.js"
		);
	}
	
	public function testSubscribesToAdminNoticeActionUsingAppropriateCallBackMethod()
	{
		$this->assertWordPressFunctionCalledWith(
			"add_action",
			"admin_notices",
			[$this->plugin, "renderAdminNotices"]
		);
	}
	
	public function testRenderAdminNoticesOutputsDefaultNotices()
	{
		$this->plugin->renderAdminNotices();
		
		$this->assertWordPressFunctionCalled("settings_errors");
	}
	
	public function testErrorNoticePostedWhenPermalinksTurnedOff()
	{
		$this->mockWordPress->setReturnValue("call", false);
		
		$this->plugin->renderAdminNotices();
		
		$this->assertErrorRenderedWithMessage("AVORG Warning: Permalinks turned off!");
	}
	
	public function testChecksPermalinkStructure()
	{
		$this->plugin->renderAdminNotices();
		
		$this->assertWordPressFunctionCalledWith("get_option", "permalink_structure");
	}
	
	public function testGetsAvorgApiUser()
	{
		$this->plugin->renderAdminNotices();
		
		$this->assertWordPressFunctionCalledWith("get_option", "avorgApiUser");
	}
	
	public function testGetsAvorgApiPass()
	{
		$this->plugin->renderAdminNotices();
		
		$this->assertWordPressFunctionCalledWith("get_option", "avorgApiPass");
	}
	
	public function testErrorNoticePostedWhenNoAvorgApiUser()
	{
		$this->mockWordPress->setReturnValue("call", false);
		
		$this->plugin->renderAdminNotices();
		
		$this->assertErrorRenderedWithMessage("AVORG Warning: Missing API username!");
	}
	
	public function testErrorNoticePostedWhenNoAvorgApiPass()
	{
		$this->mockWordPress->setReturnValue("call", false);
		
		$this->plugin->renderAdminNotices();
		
		$this->assertErrorRenderedWithMessage("AVORG Warning: Missing API password!");
	}

	public function testRegistersCallbacksOnMediaPage()
	{
		$this->assertWordPressFunctionCalledWith(
			"add_filter",
			"the_content",
			[$this->factory->getMediaPage(), "addUi"]
		);
	}
}