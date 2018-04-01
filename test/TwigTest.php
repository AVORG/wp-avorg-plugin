<?php
use PHPUnit\Framework\TestCase;

final class TwigTest extends TestCase
{
	protected function setUp()
	{
		parent::setUp();
		define( "ABSPATH", "/" );
	}
	
	public function testHasRenderFunction()
	{
		$t = new avorg\Twig;
		$this->assertTrue( method_exists( $t, "render" ) );
	}
}