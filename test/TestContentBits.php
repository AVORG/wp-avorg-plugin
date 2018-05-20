<?php

final class TestContentBits extends Avorg\TestCase
{
	public function testExists()
	{
		$this->assertTrue(is_object($this->mockedContentBits));
	}
	
	public function testInitMethodRegistersPostType()
	{
		$this->mockedContentBits->init();
		
		$calls = $this->mockWordPress->getCalls( "call" );
		
		$this->assertEquals( $calls[0][0], "register_post_type" );
	}
}