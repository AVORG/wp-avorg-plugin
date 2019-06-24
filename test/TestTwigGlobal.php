<?php

use Avorg\Script;
use Avorg\TwigGlobal;

final class TestTwigGlobal extends Avorg\TestCase
{
	/** @var TwigGlobal $global */
	private $global;

	protected function setUp()
	{
		parent::setUp();

		$this->global = $this->factory->obtain("Avorg\\TwigGlobal");
	}

	public function test__Function()
	{
		$this->global->i__("string");
		
		$this->mockWordPress->assertMethodCalledWith("__", "string", $this->textDomain);
	}
	
	public function test__FunctionReturnsValue()
	{
		$this->mockWordPress->setReturnValue("__", "translation");
		
		$result = $this->global->i__("string");
		
		$this->assertEquals("translation", $result);
	}
	
	public function testLoadData()
	{
		$this->global->setData(["foo" => "bar"]);
		
		$result = $this->global->foo;
		
		$this->assertEquals("bar", $result);
	}
	
	public function testUpdateData()
	{
		$this->global->setData(["foo" => "bar"]);
		$this->global->setData(["foo" => "baz"]);
		
		$result = $this->global->foo;
		
		$this->assertEquals("baz", $result);
	}
	
	public function testAddData()
	{
		$this->global->setData(["foo" => "bar"]);
		$this->global->setData(["wibble" => "wobble"]);
		
		$result = $this->global->foo;
		
		$this->assertEquals("bar", $result);
	}
	
	public function testCanCheckIfLoadedDataIsset()
	{
		$this->global->setData(["foo" => "bar"]);
		
		$this->assertTrue(isset($this->global->foo));
	}
	
	public function test_nFunction()
	{
		$this->global->_n("single", "plural", 5);
		
		$this->mockWordPress->assertMethodCalledWith("_n", "single", "plural", 5, $this->textDomain);
	}
	
	public function test_nFunctionReturnsValue()
	{
		$this->mockWordPress->setReturnValue("_n", "translation");
		
		$result = $this->global->_n("single", "plural", 5);
		
		$this->assertEquals("translation", $result);
	}

	public function testGetLanguage()
	{
		$_SERVER["REQUEST_URI"] = "localhost:8080/espanol";

		$result = $this->global->getLanguage()->getLangCode();

		$this->assertEquals("es_ES", $result);
	}

	public function testGetRequestUri()
	{
		$_SERVER["HTTP_HOST"] = "localhost:8080";
		$_SERVER["REQUEST_URI"] = "localhost:8080/espanol";

		$result = $this->global->getRequestUri();

		$this->assertEquals("http://localhost:8080/espanol", $result);
	}

	public function testGetRequestPath()
	{
		$_SERVER["HTTP_HOST"] = "localhost:8080";
		$_SERVER["REQUEST_URI"] = "localhost:8080/espanol";

		$result = $this->global->getRequestPath();

		$this->assertEquals("/espanol", $result);
	}

	/**
	 * @throws Exception
	 */
	public function testLoadsScriptRelativeToScriptFolder()
	{
		$this->global->loadScript("script.js");

		$this->mockWordPress->assertAnyCallMatches("wp_enqueue_script", function($call) {
			return $call[1] === AVORG_BASE_URL . "/script/script.js";
		});
	}
}