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
	
	public function testInsertsMediaDetailsPage()
	{
		$this->mockWordPress->setReturnValue("call", false);
		
		$this->plugin->activate();
		
		$this->assertCalledWith($this->mockWordPress, "call", ...$this->mediaPageInsertCall);
	}
	
	public function testDoesNotInsertPageTwice()
	{
		$this->mockWordPress->setReturnValue("call", ["post"]);
		
		$this->plugin->activate();
		
		$this->assertNotCalledWith($this->mockWordPress, "call", ...$this->mediaPageInsertCall);
	}
	
	public function testInsertsMediaDetailsPageOnInit()
	{
		$this->mockWordPress->setReturnValue("call", false);
		
		$this->plugin->init();
		
		$this->assertCalledWith($this->mockWordPress, "call", ...$this->mediaPageInsertCall);
	}
	
	public function testActivatesRouterOnPluginActivate()
	{
		$plugin = $this->factory->getPlugin();
		
		$plugin->activate();
		
		$this->assertWordPressFunctionCalled("flush_rewrite_rules");
	}
	
	public function testInitInitsContentBits() ######
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
		
		$this->assertTwigTemplateRenderedWithData("molecule-notice.twig", [
			"type" => "error",
			"message" => "AVORG Warning: Permalinks turned off!"
		]);
	}
	
	public function testChecksPermalinkStructure()
	{
		$this->plugin->renderAdminNotices();
		
		$this->assertWordPressFunctionCalledWith("get_option", "permalink_structure");
	}
}