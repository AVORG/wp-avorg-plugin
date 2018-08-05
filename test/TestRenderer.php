<?php

final class TestRenderer extends Avorg\TestCase
{
	/** @var \Avorg\Renderer $renderer */
	protected $renderer;
	
	protected function setUp()
	{
		parent::setUp();
		
		$this->renderer = $this->factory->getRenderer();
	}
	
	public function testUsesTwigToRender()
	{
		$this->renderer->render("admin.twig");
		
		$this->assertTwigTemplateRendered("admin.twig");
	}
	
	public function testRenderFunctionPassesAvorgGlobalObject()
	{
		$this->renderer->render("admin.twig");
		
		$calls = $this->mockTwig->getCalls("render");
		$call = $calls[0];
		$arguments = $call[1];
		$avorg = $arguments["avorg"];
		
		$this->assertInstanceOf("\Avorg\TwigGlobal", $avorg);
	}
	
	public function testRenderNotice()
	{
		$this->renderer->renderNotice("error", "message");
		
		$this->assertTwigTemplateRenderedWithData(
			"molecule-notice.twig",
			[
				"message" => "message",
				"type" => "error"
			]
		);
	}
}