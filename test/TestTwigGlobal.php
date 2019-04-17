<?php

final class TestTwigGlobal extends Avorg\TestCase
{
	/** @var \Avorg\TwigGlobal $global */
	private $global;

	protected function setUp()
	{
		parent::setUp();

		$this->global = $this->factory->make("TwigGlobal");
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
		$this->global->loadData(["foo" => "bar"]);
		
		$result = $this->global->foo;
		
		$this->assertEquals("bar", $result);
	}
	
	public function testUpdateData()
	{
		$this->global->loadData(["foo" => "bar"]);
		$this->global->loadData(["foo" => "baz"]);
		
		$result = $this->global->foo;
		
		$this->assertEquals("baz", $result);
	}
	
	public function testAddData()
	{
		$this->global->loadData(["foo" => "bar"]);
		$this->global->loadData(["wibble" => "wobble"]);
		
		$result = $this->global->foo;
		
		$this->assertEquals("bar", $result);
	}
	
	public function testCanCheckIfLoadedDataIsset()
	{
		$this->global->loadData(["foo" => "bar"]);
		
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
}