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
		]
	];
	
	private function getAddRewriteCall() {
		$calls = $this->mockWordPress->getCalls("call");
		return $calls[2];
	}
	
	public function testCallsWordPress()
	{
		$this->mockedRouter->activate();
		
		$this->assertCalled($this->mockWordPress, "call");
	}
	
	public function testRewriteRuleMatchesTestUrls()
	{
		$this->mockWordPress->setReturnValue( "call", 10 );
		
		$this->mockedRouter->activate();
		
		$addRewriteCall = $this->getAddRewriteCall();
		$regex = $addRewriteCall[1];
		
		foreach ($this->testCases as $case) {
			//$this->assertEquals(1, preg_match("/$regex/", $case[0]), $case[0]);
			$this->assertRegExp( "/$regex/", $case[0] );
		}
	}
	
	public function testAssignsHighPriority()
	{
		$this->mockedRouter->activate();
		
		$addRewriteCall = $this->getAddRewriteCall();
		$priority = $addRewriteCall[3];
		
		$this->assertEquals("top", $priority);
	}
	
	public function testRewriteRuleRewritesCorrectly()
	{
		$this->mockWordPress->setReturnValue( "call", 10 );
		
		$this->mockedRouter->activate();
		
		$addRewriteCall = $this->getAddRewriteCall();
		$regex = $addRewriteCall[1];
		$redirect = $addRewriteCall[2];
		
		foreach ($this->testCases as $case) {
			preg_match("/$regex/", $case[0], $matches);
			$result = eval( "return \"$redirect\";" );
			
			$this->assertEquals( $case[1], $result );
		}
	}
	
	public function testFlushesRewireRules() {
		$this->mockedRouter->activate();
		
		$this->assertCalledWith( $this->mockWordPress, "call", "flush_rewrite_rules" );
	}
	
	public function testRegistersPresParam() {
		$this->mockedRouter->activate();
		
		$this->assertCalledWith( $this->mockWordPress, "call", "add_rewrite_tag", "%presentation_id%", "(\d+)" );
	}
	
	public function testUsesSavedMedaPageId() {
		$this->mockWordPress->setReturnValue( "call", 3 );

		$this->mockedRouter->activate();
		
		$addRewriteCall = $this->getAddRewriteCall();
		$redirect = $addRewriteCall[2];
		
		$this->assertContains( "page_id=3", $redirect );
	}
}