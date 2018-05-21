<?php

final class TestContentBits extends Avorg\TestCase
{
	public function assertWordPressFunctionCalled($function)
	{
		$calls = $this->mockWordPress->getCalls("call");
		
		$wasCalled = array_reduce($calls, function ($carry, $call) use ($function) {
			return $carry || $call[0] === $function;
		}, false);
		
		$this->assertTrue($wasCalled, "Failed to assert $function was called using the WordPress wrapper");
	}
	
	public function testExists()
	{
		$this->assertTrue(is_object($this->mockedContentBits));
	}
	
	public function testInitMethodRegistersPostType()
	{
		$this->mockedContentBits->init();
		
		$this->assertWordPressFunctionCalled("register_post_type");
	}
	
	public function testInitRegistersTaxonomy()
	{
		$this->mockedContentBits->init();
		
		$this->assertWordPressFunctionCalled("register_taxonomy");
	}
	
	public function testAddMetaBoxMethod()
	{
		$this->mockedContentBits->addIdentifierMetaBox();
		
		$this->assertWordPressFunctionCalled("add_meta_box");
	}
	
	public function testGetsSavedIdentifier()
	{
		$this->mockWordPress->setReturnValue("call", 7);
		
		$this->mockedContentBits->renderIdentifierMetaBox();
		
		$this->assertCalledWith($this->mockWordPress, "call", "get_post_meta", 7, "_avorgBitIdentifier", true);
	}
	
	public function testPassesSavedValueToTwig()
	{
		$this->mockWordPress->setReturnValue("call", "saved_value");
		
		$this->mockedContentBits->renderIdentifierMetaBox();
		
		$this->assertCalledWith($this->mockTwig, "render", "identifierMetaBox.twig", ["savedIdentifier" => "saved_value"]);
	}
	
	public function testSavesIdentifier()
	{
		$_POST["avorgBitIdentifier"] = "new_identifier";
		
		$this->mockWordPress->setReturnValue("call", 7);
		
		$this->mockedContentBits->saveIdentifierMetaBox();
		
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
		
		$this->mockedContentBits->saveIdentifierMetaBox();
		
		$this->assertNotCalled($this->mockWordPress, "call");
	}
}