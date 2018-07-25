<?php

final class TestTwigGlobal extends Avorg\TestCase
{
	public function testRegistersGlobal()
	{
		$global = $this->factory->getTwigGlobal();
		
		$this->assertCalledWith($this->mockTwig, "registerGlobal", "avorg", $global);
	}
	
	public function test__Function()
	{
		$global = $this->factory->getTwigGlobal();
		
		$global->i__("string");
		
		$this->assertWordPressFunctionCalledWith("__", "string");
	}
	
	public function test__FunctionReturnsValue()
	{
		$global = $this->factory->getTwigGlobal();
		
		$this->mockWordPress->setReturnValue("call", "translation");
		
		$result = $global->i__("string");
		
		$this->assertEquals("translation", $result);
	}
	
	public function testLoadData()
	{
		$global = $this->factory->getTwigGlobal();
		
		$global->loadData(["foo" => "bar"]);
		
		$result = $global->foo();
		
		$this->assertEquals("bar", $result);
	}
	
	public function testUpdateData()
	{
		$global = $this->factory->getTwigGlobal();
		
		$global->loadData(["foo" => "bar"]);
		$global->loadData(["foo" => "baz"]);
		
		$result = $global->foo();
		
		$this->assertEquals("baz", $result);
	}
	
	public function testAddData()
	{
		$global = $this->factory->getTwigGlobal();
		
		$global->loadData(["foo" => "bar"]);
		$global->loadData(["wibble" => "wobble"]);
		
		$result = $global->foo();
		
		$this->assertEquals("bar", $result);
	}
}