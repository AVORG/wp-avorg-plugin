<?php

final class TestAvorgApi extends Avorg\TestCase
{
	public function testHasRenderFunction()
	{
		$t = new Avorg\AvorgApi;
		$this->assertTrue( method_exists( $t, "getPresentation" ) );
	}
}