<?php

final class TestContentBits extends Avorg\TestCase
{
	/** @var \Avorg\ContentBits $contentBits */
	protected $contentBits;
	
	public function setUp()
	{
		parent::setUp();
		
		$this->contentBits = $this->factory->getContentBits();
	}
	
	public function testInitMethodRegistersPostType()
	{
		$this->contentBits->init();
		
		$this->assertWordPressFunctionCalled("register_post_type");
	}
	
	public function testInitRegistersTaxonomy()
	{
		$this->contentBits->init();
		
		$this->assertWordPressFunctionCalled("register_taxonomy");
	}
	
	public function testAddMetaBoxMethod()
	{
		$this->contentBits->addIdentifierMetaBox();
		
		$this->assertWordPressFunctionCalled("add_meta_box");
	}
	
	public function testGetsSavedIdentifier()
	{
		$this->mockWordPress->setReturnValue("call", 7);
		
		$this->contentBits->renderIdentifierMetaBox();
		
		$this->assertCalledWith($this->mockWordPress, "call", "get_post_meta", 7, "_avorgBitIdentifier", true);
	}
	
	public function testPassesSavedValueToTwig()
	{
		$this->mockWordPress->setReturnValue("call", "saved_value");
		
		$this->contentBits->renderIdentifierMetaBox();
		
		$this->assertTwigTemplateRenderedWithData("identifierMetaBox.twig", ["savedIdentifier" => "saved_value"]);
		
	}
	
	public function testSavesIdentifier()
	{
		$_POST["avorgBitIdentifier"] = "new_identifier";
		
		$this->mockWordPress->setReturnValue("call", 7);
		
		$this->contentBits->saveIdentifierMetaBox();
		
		$this->assertCalledWith(
			$this->mockWordPress,
			"call",
			"update_post_meta",
			7,
			"_avorgBitIdentifier",
			"new_identifier"
		);
	}
	
	public function testDoesntSaveIfValueNotPassed()
	{
		$this->mockWordPress->setReturnValue("call", 7);
		
		$this->contentBits->saveIdentifierMetaBox();
		
		$this->assertNotCalled($this->mockWordPress, "call");
	}
	
	public function testAddsShortcode()
	{
		$this->contentBits->init();
		
		$this->assertCalledWith($this->mockWordPress, "call",
			"add_shortcode", "avorg-bits", [$this->contentBits, "renderShortcode"]);
	}
	
	public function testRenderShortcodeMethodExists()
	{
		$this->assertTrue(method_exists($this->contentBits, "renderShortcode"));
	}
	
	public function testRenderShortcodeGetsPosts()
	{
		$this->contentBits->renderShortcode([]);
		
		$this->assertWordPressFunctionCalled("get_posts");
	}
	
	public function testRenderShortcodeUsesIdAttribute()
	{
		$this->contentBits->renderShortcode(['id' => 'passedId']);
		
		$this->assertAnyCallMatches($this->mockWordPress, "call", function ($carry, $call) {
			if (!isset($call[1]['meta_query'][0]['value'])) return $carry;
			
			return $carry || $call[1]['meta_query'][0]['value'] === "passedId";
		});
	}
	
	public function testGetsRandomPost()
	{
		$posts = ['item 1', 'item 2', 'item 3'];
		$this->mockWordPress->setReturnValue("call", $posts);
		
		$this->contentBits->renderShortcode([]);
		
		$this->assertCalledWith($this->mockPhp, "array_rand", $posts);
	}
	
	public function testReturnsRandomPostContent()
	{
		$post = new stdClass();
		$post->post_content = "hello world";
		
		$this->mockWordPress->setReturnValue("call", [$post]);
		$this->mockPhp->setReturnValue("array_rand", 0);
		
		$result = $this->contentBits->renderShortcode([]);
		
		$this->assertEquals("hello world", $result);
	}
	
	public function testTriesToFilterByRecordingId()
	{
		$this->mockWordPress->setReturnValue("call", '111');
		
		$this->contentBits->renderShortcode([]);
		
		$this->assertAnyCallMatches($this->mockWordPress, "call", function ($carry, $call) {
			if (!isset($call[1]['tax_query'][0]['terms'])) return $carry;
			
			return $carry || $call[1]['tax_query'][0]['terms'] === "111";
		});
	}
	
	public function testFallsBackToRandomPost()
	{
		$post = new stdClass();
		$post->post_content = "hello world";
		$posts = [$post];
		$this->mockWordPress->setReturnValues("call", '111', [], $posts);
		
		$this->contentBits->renderShortcode([]);
		
		$this->assertCalledWith($this->mockPhp, "array_rand", $posts);
	}
	
	public function testSecondCallDoesntIncludePresentationId()
	{
		$post = new stdClass();
		$post->post_content = "hello world";
		$posts = [$post];
		
		$this->mockWordPress->setReturnValues("call", ['111', [], $posts]);
		
		$this->contentBits->renderShortcode([]);
		
		$calls = $this->mockWordPress->getCalls("call");
		
		$this->assertNull( $calls[2][1]['tax_query']['terms'] );
	}
	
	public function testMediaTargetingIsExclusive()
	{
		$this->contentBits->renderShortcode([]);
		
		$this->assertAnyCallMatches($this->mockWordPress, "call", function($call) {
			return $call[1]['tax_query']['terms'] === null;
		}, "Failed asserting that media targeting is exclusive");
	}
	
	public function testDoesNotTryToSelectRandomItemInEmptyArray()
	{
		$this->mockWordPress->setReturnValues("call", ['111', [], []]);
		
		$result = $this->contentBits->renderShortcode([]);
		
		$this->assertNotCalled($this->mockPhp, "array_rand");
	}
}