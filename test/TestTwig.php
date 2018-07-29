<?php

final class TestTwig extends Avorg\TestCase
{
	public function testRender()
	{
		$t = new Avorg\Twig;
		$result = $t->render("admin.twig");
		
		$this->assertTrue(is_string($result));
	}
}