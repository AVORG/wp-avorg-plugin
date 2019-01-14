<?php

final class TestContentBits extends Avorg\TestCase
{
	/** @var \Avorg\ContentBits $contentBits */
	protected $contentBits;
	
	public function setUp()
	{
		parent::setUp();
		
		$this->contentBits = $this->factory->get("ContentBits");
	}
	
	public function testInitMethodRegistersPostType()
	{
		$this->contentBits->init();
		
		$this->mockWordPress->assertMethodCalled("register_post_type");
	}
	
	public function testInitRegistersTaxonomy()
	{
		$this->contentBits->init();
		
		$this->mockWordPress->assertMethodCalled("register_taxonomy");
	}
	
	public function testAddMetaBoxMethod()
	{
		$this->contentBits->addIdentifierMetaBox();
		
		$this->mockWordPress->assertMethodCalled("add_meta_box");
	}
	
	public function testGetsSavedIdentifier()
	{
		$this->mockWordPress->setReturnValue("get_the_ID", 7);
		
		$this->contentBits->renderIdentifierMetaBox();
		
		$this->mockWordPress->assertMethodCalledWith( "get_post_meta", 7, "_avorgBitIdentifier", true);
	}
	
	public function testPassesSavedValueToTwig()
	{
		$this->mockWordPress->setReturnValue("get_post_meta", "saved_value");
		
		$this->contentBits->renderIdentifierMetaBox();
		
		$this->mockTwig->assertTwigTemplateRenderedWithData("identifierMetaBox.twig", ["savedIdentifier" => "saved_value"]);
		
	}
	
	public function testSavesIdentifier()
	{
		$_POST["avorgBitIdentifier"] = "new_identifier";
		
		$this->mockWordPress->setReturnValue("get_the_ID", 7);
		
		$this->contentBits->saveIdentifierMetaBox();
		
		$this->mockWordPress->assertMethodCalledWith(
			"update_post_meta",
			7,
			"_avorgBitIdentifier",
			"new_identifier"
		);
	}
	
	public function testDoesntSaveIfValueNotPassed()
	{
		$this->contentBits->saveIdentifierMetaBox();

		$this->mockWordPress->assertMethodNotCalled("update_post_meta");
	}
	
	public function testAddsShortcode()
	{
		$this->contentBits->init();
		
		$this->mockWordPress->assertMethodCalledWith(
			"add_shortcode", "avorg-bits", [$this->contentBits, "renderShortcode"]);
	}
	
	public function testRenderShortcodeMethodExists()
	{
		$this->assertTrue(method_exists($this->contentBits, "renderShortcode"));
	}
	
	public function testRenderShortcodeGetsPosts()
	{
		$this->contentBits->renderShortcode([]);
		
		$this->mockWordPress->assertMethodCalled("get_posts");
	}
	
	public function testRenderShortcodeUsesIdAttribute()
	{
		$this->contentBits->renderShortcode(['id' => 'passedId']);
		
		$this->mockWordPress->assertAnyCallMatches( "get_posts", function ($carry, $call) {
			if (!isset($call[0]['meta_query'][0]['value'])) return $carry;
			
			return $carry || $call[0]['meta_query'][0]['value'] === "passedId";
		});
	}
	
	public function testGetsRandomPost()
	{
		$posts = ['item 1', 'item 2', 'item 3'];
		$this->mockWordPress->setReturnValue("get_posts", $posts);
		
		$this->contentBits->renderShortcode([]);
		
		$this->mockPhp->assertMethodCalledWith( "array_rand", $posts);
	}
	
	public function testReturnsRandomPostContent()
	{
		$post = new stdClass();
		$post->post_content = "hello world";
		
		$this->mockWordPress->setReturnValue("get_posts", [$post]);
		$this->mockPhp->setReturnValue("array_rand", 0);
		
		$result = $this->contentBits->renderShortcode([]);
		
		$this->assertEquals("hello world", $result);
	}
	
	public function testTriesToFilterByRecordingId()
	{
		$this->mockWordPress->setReturnValue("get_query_var", '111');
		
		$this->contentBits->renderShortcode([]);
		
		$this->mockWordPress->assertAnyCallMatches( "get_posts", function ($carry, $call) {
			if (!isset($call[0]['tax_query'][0]['terms'])) return $carry;
			
			return $carry || $call[0]['tax_query'][0]['terms'] === "111";
		});
	}
	
	public function testFallsBackToRandomPost()
	{
		$post = new stdClass();
		$post->post_content = "hello world";
		$posts = [$post];

		$this->mockWordPress->setReturnValue("get_query_var", "111");
		$this->mockWordPress->setReturnValues("get_posts", [], $posts);
		
		$this->contentBits->renderShortcode([]);
		
		$this->mockPhp->assertMethodCalledWith( "array_rand", $posts);
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
		
		$this->mockWordPress->assertAnyCallMatches( "get_posts", function($call) {
			return $call[0]['tax_query']['terms'] === null;
		}, "Failed asserting that media targeting is exclusive");
	}
	
	public function testDoesNotTryToSelectRandomItemInEmptyArray()
	{
		$this->mockWordPress->setReturnValues("call", ['111', [], []]);
		
		$this->contentBits->renderShortcode([]);

		$this->mockPhp->assertMethodNotCalled("array_rand");
	}
}