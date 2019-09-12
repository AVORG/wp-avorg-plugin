<?php

use Avorg\Block\RelatedSermons;

final class TestPlaceholderBlock extends Avorg\TestCase
{
    /** @var RelatedSermons $block */
    protected $block;

    /**
     * @throws ReflectionException
     */
    protected function setUp()
    {
        parent::setUp();

        $this->block = $this->factory->secure("Avorg\\Block\\Placeholder");
    }

    public function testInitRegistersBlockType()
    {
        $this->block->init();

        $this->mockWordPress->assertMethodCalledWith("register_block_type",
            'avorg/block-placeholder', [
                'editor_script' => 'Avorg_Script_Editor',
                'render_callback' => [$this->block, "render"]
            ]);
    }
}