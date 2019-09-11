<?php

use Avorg\Block\RelatedSermons;

final class TestRelatedSermonsBlock extends Avorg\TestCase
{
	/** @var RelatedSermons $block */
	protected $block;

	/**
	 * @throws ReflectionException
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->block = $this->factory->secure("Avorg\\Block\\RelatedSermons");
	}

	public function testInitRegistersBlockType()
	{
		$this->block->init();

		$this->mockWordPress->assertMethodCalledWith("register_block_type",
			'avorg/block-relatedsermons', [
				'editor_script' => 'Avorg_Script_Editor',
                'render_callback' => [$this->block, "render"]
			]);
	}

	public function testRender()
    {
        $this->block->render();

        $this->mockTwig->assertMethodCalled('render');
    }

    public function testRendersTemplate()
    {
        $this->block->render();

        $this->mockTwig->assertTwigTemplateRendered("block-relatedSermons.twig");
    }

    public function testReturnsHtml()
    {
        $this->mockTwig->setReturnValue("render", "html");

        $result = $this->block->render();

        $this->assertEquals("html", $result);
    }
}