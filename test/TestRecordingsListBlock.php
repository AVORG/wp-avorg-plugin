<?php

use Avorg\Block\RecordingsList;
use natlib\Stub;

final class TestRecordingsListBlock extends Avorg\TestCase
{
    /** @var RecordingsList $block */
    protected $block;

    /**
     * @throws ReflectionException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->block = $this->factory->secure("Avorg\\Block\\RecordingsList");
    }

    public function testInitRegistersBlockType()
    {
        $this->block->init();

        $this->mockWordPress->assertMethodCalledWith("register_block_type",
            'avorg/block-list', [
                'editor_script' => 'Avorg_Script_Editor',
                'render_callback' => [$this->block, "render"]
            ]);
    }

    public function testRendersTemplate()
    {
        $this->block->render([], '');

        $this->mockTwig->assertTwigTemplateRendered("block-list.twig");
    }

    public function testGetsRecordings()
    {
        $this->block->render([], '');

        $this->mockAvorgApi->assertMethodCalled("getRecordings");
    }

    public function testUsesListType()
    {
        $this->block->render([
            "type" => "popular"
        ], '');

        $this->mockAvorgApi->assertMethodCalledWith("getRecordings", "popular");
    }

    public function testReturnsPresentations()
    {
        $this->mockAvorgApi->loadRecordings([]);

        $this->block->render([], '');

        $data = $this->mockTwig->getRenderedData();

        $this->assertCount(1, $data['recordings']);
    }
}