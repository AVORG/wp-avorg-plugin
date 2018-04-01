<?php
use PHPUnit\Framework\TestCase;

final class TwigTest extends TestCase
{
	protected function setUp()
	{
		parent::setUp();
		define( "ABSPATH", dirname(dirname(__FILE__)) . '/' );
	}
	
	public function testExists()
	{
		$this->assertTrue( class_exists( "avorg\\Twig" ) );
	}
}