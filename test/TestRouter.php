<?php

final class TestRouter extends Avorg\TestCase
{
	/** @var \Avorg\Router $router */
	protected $router;
	
	public function setUp()
	{
		parent::setUp();
		
		$this->router = $this->factory->getRouter();
	}
	
	private function getAddRewriteCalls()
	{
		$calls = $this->mockWordPress->getCalls("call");
		
		return array_reduce($calls, function($carry, $call) {
			$isAddRewriteCall = $call[0] === "add_rewrite_rule";
			
			return $isAddRewriteCall ? array_merge($carry, [$call]) : $carry;
		}, []);
	}
	
	public function testCallsWordPress()
	{
		$this->router->activate();
		
		$this->assertCalled($this->mockWordPress, "call");
	}
	
	public function testAssignsHighPriority()
	{
		$this->router->activate();
		
		$addRewriteCalls = $this->getAddRewriteCalls();
		$priority = $addRewriteCalls[0][3];
		
		$this->assertEquals("top", $priority);
	}
	
	/**
	 * @dataProvider rewriteInputOutputProvider
	 * @param $inputUrl
	 * @param $outputUrl
	 */
	public function testRewriteRuleRewritesCorrectly($inputUrl, $outputUrl)
	{
		$this->mockWordPress->setReturnValue("call", 10);
		
		$this->router->activate();
		
		$addRewriteCalls = $this->getAddRewriteCalls();
		
		$doesMatch = array_reduce($addRewriteCalls, function ($carry, $call) use ($inputUrl, $outputUrl) {
			$regex = $call[1];
			$redirect = $call[2];
			
			preg_match("/$regex/", $inputUrl, $matches);
			$result = eval("return \"$redirect\";");
			
			return $carry || $outputUrl === $result;
		}, false);
		
		$this->assertTrue($doesMatch, "Input: $inputUrl\r\nExpected Output: $outputUrl");
	}
	
	public function rewriteInputOutputProvider()
	{
		return [
			[
				"english/sermons/recordings/316/parents-how.html",
				"index.php?page_id=10&presentation_id=316"
			],
			[
				"english/sermons/recordings/2913/generation-of-youth-for-christ.html",
				"index.php?page_id=10&presentation_id=2913"
			],
			[
				"english/sermons/recordings/3914/killing-the-fat-king.html",
				"index.php?page_id=10&presentation_id=3914"
			],
			[
				"english/sermons/recordings/17663/2-new-theology--halfhearted-christians.html",
				"index.php?page_id=10&presentation_id=17663"
			],
			[
				"english/sermons/recordings/17831/the-last-attack.html",
				"index.php?page_id=10&presentation_id=17831"
			],
			[
				"english/sermons/recordings/17833/single-and-satisfied.html",
				"index.php?page_id=10&presentation_id=17833"
			],
			[
				"english/sermons/recordings/316/parents-how.html/",
				"index.php?page_id=10&presentation_id=316"
			],
			[
				"english/sermons/recordings/2913/generation-of-youth-for-christ.html/",
				"index.php?page_id=10&presentation_id=2913"
			],
			[
				"english/sermons/recordings/3914/killing-the-fat-king.html/",
				"index.php?page_id=10&presentation_id=3914"
			],
			[
				"english/sermons/recordings/17663/2-new-theology--halfhearted-christians.html/",
				"index.php?page_id=10&presentation_id=17663"
			],
			[
				"english/sermons/recordings/17831/the-last-attack.html/",
				"index.php?page_id=10&presentation_id=17831"
			],
			[
				"english/sermons/recordings/17833/single-and-satisfied.html/",
				"index.php?page_id=10&presentation_id=17833"
			],
			[
				"espanol/sermones/grabaciones/17283/saludismo.html",
				"index.php?page_id=10&presentation_id=17283"
			],
			[
				"francais/predications/enregistrements/3839/jesus-sur-le-mont-des-oliviers.html",
				"index.php?page_id=10&presentation_id=3839"
			],
			[
				"espanol",
				"index.php?page_id=10"
			],
			[
				"espanol/",
				"index.php?page_id=10"
			]
		];
	}
	
	public function testFlushesRewireRules()
	{
		$this->router->activate();
		
		$this->assertCalledWith($this->mockWordPress, "call", "flush_rewrite_rules");
	}
	
	public function testRegistersPresParam()
	{
		$this->router->activate();
		
		$this->assertCalledWith($this->mockWordPress, "call", "add_rewrite_tag", "%presentation_id%", "(\d+)");
	}
	
	public function testUsesSavedMediaPageId()
	{
		$this->mockWordPress->setReturnValue("call", 3);
		
		$this->router->activate();
		
		$addRewriteCalls = $this->getAddRewriteCalls();
		$redirect = $addRewriteCalls[0][2];
		
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
}