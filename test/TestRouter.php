<?php

final class TestRouter extends Avorg\TestCase
{
	private $testCases = [
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
		]
	];
	
	private function getAddRewriteCalls()
	{
		$calls = $this->mockWordPress->getCalls("call");
		return array_slice($calls, 2);
	}
	
	public function testCallsWordPress()
	{
		$this->mockedRouter->activate();
		
		$this->assertCalled($this->mockWordPress, "call");
	}
	
	public function testAssignsHighPriority()
	{
		$this->mockedRouter->activate();
		
		$addRewriteCalls = $this->getAddRewriteCalls();
		$priority = $addRewriteCalls[0][3];
		
		$this->assertEquals("top", $priority);
	}
	
	public function testRewriteRuleRewritesCorrectly()
	{
		$this->mockWordPress->setReturnValue("call", 10);
		
		$this->mockedRouter->activate();
		
		$addRewriteCalls = $this->getAddRewriteCalls();
		
		foreach ($this->testCases as $case) {
			$inputUrl = $case[0];
			$outputUrl = $case[1];
			
			$doesMatch = array_reduce($addRewriteCalls, function ($carry, $call) use ($inputUrl, $outputUrl) {
				$regex = $call[1];
				$redirect = $call[2];
				
				preg_match("/$regex/", $inputUrl, $matches);
				$result = eval("return \"$redirect\";");
				
				return $carry || $outputUrl === $result;
			}, false);
			
			$this->assertTrue($doesMatch, $inputUrl);
		}
	}
	
	public function testFlushesRewireRules()
	{
		$this->mockedRouter->activate();
		
		$this->assertCalledWith($this->mockWordPress, "call", "flush_rewrite_rules");
	}
	
	public function testRegistersPresParam()
	{
		$this->mockedRouter->activate();
		
		$this->assertCalledWith($this->mockWordPress, "call", "add_rewrite_tag", "%presentation_id%", "(\d+)");
	}
	
	public function testUsesSavedMedaPageId()
	{
		$this->mockWordPress->setReturnValue("call", 3);
		
		$this->mockedRouter->activate();
		
		$addRewriteCalls = $this->getAddRewriteCalls();
		$redirect = $addRewriteCalls[0][2];
		
		$this->assertContains("page_id=3", $redirect);
	}
}