<?php

final class TestTwig extends Avorg\TestCase
{
	public function testHasRenderFunction()
	{
		$t = new Avorg\Twig;
		$this->assertTrue( method_exists( $t, "render" ) );
	}
}