<?php

namespace Avorg;

require_once(dirname(__FILE__) . '/MockFactory.php');

abstract class TestCase extends \PHPUnit\Framework\TestCase {
	/* Mock Objects */
	
	/** @var AvorgApi $mockAvorgApi */
	protected $mockAvorgApi;
	
	/** @var Php $mockPhp */
	protected $mockPhp;
	
	/** @var Twig $mockTwig */
	protected $mockTwig;
	
	/** @var WordPress $mockWordPress */
	protected $mockWordPress;
	
	/* Helper Fields */
	
	/** @var MockFactory $objectMocker */
	protected $objectMocker;
	
	/** @var Factory $factory */
	protected $factory;
	
	protected function setUp()
	{
		define( "ABSPATH", "/" );
		
		$_POST = array();
		$_GET  = array();
		
		$this->objectMocker = new MockFactory();
		
		$this->resetMocks();
		
		$this->factory = new Factory(
			$this->mockAvorgApi,
			$this->mockPhp,
			$this->mockTwig,
			$this->mockWordPress
		);
	}
	
	private function resetMocks() {
		$this->mockAvorgApi = $this->objectMocker->buildMock("AvorgApi");
		$this->mockPhp = $this->objectMocker->buildMock("Php");
		$this->mockTwig = $this->objectMocker->buildMock("Twig");
		$this->mockWordPress = $this->objectMocker->buildMock("WordPress");
	}
	
	protected function output( $data ) {
		fwrite(STDERR, print_r("\n" . var_export($data, true) . "\n", TRUE));
	}
	
	/* Assertions */
	protected function assertCalled( $mock, $method ) {
		$mockName = get_class( $mock );
		$error = "Failed asserting that $mockName->$method() was called.";
		$this->assertNotEmpty( $mock->getCalls( $method ), $error );
	}
	
	protected function assertNotCalled( $mock, $method ) {
		$mockName = get_class( $mock );
		$error = "Failed asserting that $mockName->$method() was not called.";
		$this->assertEmpty( $mock->getCalls( $method ), $error );
	}
	
	protected function assertCalledWith( $mock, $method, ...$arguments ) {
		$calls = $mock->getCalls( $method );
		$mockName = get_class( $mock );
		
		$this->assertMethodExists( $mockName, $method, $calls );
		
		$error = $this->makeHaystackError( $mockName, $method, $arguments, $calls, "was" );
		
		$this->assertTrue( in_array( $arguments, $calls, TRUE ), $error );
	}
	
	protected function assertNotCalledWith( $mock, $method, ...$arguments ) {
		$calls = $mock->getCalls( $method );
		$mockName = get_class( $mock );
		
		$this->assertMethodExists( $mockName, $method, $calls );
		
		$error = $this->makeHaystackError( $mockName, $method, $arguments, $calls, "was not" );
		
		$this->assertFalse( in_array( $arguments, $calls, TRUE ), $error );
	}
	
	private function assertMethodExists( $mockName, $method, $calls ) {
		$nullError = "$mockName->$method() does not exist.";
		$this->assertNotNull( $calls, $nullError );
	}
	
	private function makeHaystackError( $mockName, $method, $arguments, $calls, $wasOrWasNot ) {
		$failureMessage = "Failed asserting that $mockName->$method() $wasOrWasNot called with specified args.";
		
		try {
			$errorLines = [
				$failureMessage,
				"Needle:",
				var_export( $arguments, TRUE ),
				"Haystack:",
				var_export( $calls, TRUE )
			];
		} catch ( \Exception $e ) {
			$errorLines = [
				$failureMessage,
				"Failed to export needle and haystack:",
				$e->getMessage()
			];
		}
		
		return implode( "\r\n\r\n", $errorLines );
	}
	
	protected function assertWordPressFunctionCalled($function)
	{
		$calls = $this->mockWordPress->getCalls("call");
		
		$wasCalled = array_reduce($calls, function ($carry, $call) use ($function) {
			return $carry || $call[0] === $function;
		}, false);
		
		$this->assertTrue($wasCalled, "Failed to assert $function was called using the WordPress wrapper");
	}
	
	protected function assertWordPressFunctionCalledWith($function, ...$arguments)
	{
		$needle = array_merge( [$function], $arguments );
		$calls = $this->mockWordPress->getCalls("call");
		
		$wasCalled = array_reduce($calls, function ($carry, $call) use ($needle) {
			return $carry || $call === $needle;
		}, false);
		
		$this->assertTrue($wasCalled, "Failed to assert $function was called using specified arguments");
	}
	
	protected function assertTwigTemplateRendered($template)
	{
		$message = "Failed to assert that $template was rendered";
		
		$this->assertAnyCallMatches($this->mockTwig, "render", function($carry, $call) use($template) {
			$callTemplate = $call[0];
			
			return $carry || $callTemplate === $template;
		}, $message);
	}
	
	protected function assertTwigTemplateRenderedWithData($template, $data)
	{
		$message = "Failed to assert that $template was rendered with specified data";
		
		$this->assertAnyCallMatches($this->mockTwig, "render", function($carry, $call) use($template, $data) {
			$callTemplate = $call[0];
			$callGlobal = $call[1]["avorg"];
			
			$hasData = array_reduce(array_keys($data), function($carry, $key) use($callGlobal, $data) {
				return $carry && $callGlobal->$key === $data[$key];
			}, true);
			
			return $carry || ($callTemplate === $template && $hasData);
		}, $message);
	}
	
	protected function assertAnyCallMatches($mock, $method, $callback, $errorMessage = null ) {
		$calls = $mock->getCalls( $method );
		
		$result = array_reduce( $calls, $callback, FALSE );
		
		$this->assertTrue( $result );
	}
}