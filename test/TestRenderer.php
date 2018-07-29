<?php

final class TestRenderer extends Avorg\TestCase
{
	public function testHasRenderFunction()
	{
		$t = $this->factory->getRenderer();
		$this->assertTrue( method_exists( $t, "render" ) );
	}
	
	public function testUsesTwigToRender()
	{
		$t = $this->factory->getRenderer();
		$t->render("admin.twig");
		
		$this->assertTwigTemplateRendered("admin.twig");
	}
}