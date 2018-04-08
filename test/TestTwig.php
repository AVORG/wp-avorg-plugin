<?php

final class TestTwig extends avorg\TestCase
{
	public function testHasRenderFunction()
	{
		$t = new avorg\Twig;
		$this->assertTrue( method_exists( $t, "render" ) );
	}
}