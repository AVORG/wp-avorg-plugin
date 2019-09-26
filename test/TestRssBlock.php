<?php

use Avorg\Block\RecordingsList;
use natlib\Stub;

final class TestRssBlock extends Avorg\TestCase
{
    /** @var Rss $block */
    protected $block;

    /**
     * @throws ReflectionException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->block = $this->factory->secure("Avorg\\Block\\Rss");
    }

    public function testInitRegistersBlockType()
    {
        $this->block->init();

        $this->mockWordPress->assertMethodCalledWith("register_block_type",
            'avorg/block-rss', [
                'editor_script' => 'Avorg_Script_Editor',
                'render_callback' => [$this->block, "render"]
            ]);
    }

    public function testRendersTemplate()
    {
        $this->block->render([], '');

        $this->mockTwig->assertTwigTemplateRendered("block-rss.twig");
    }

    public function testProvidesUrl()
    {
        $this->block->render([
            "feed" => "Avorg\Endpoint\RssEndpoint\Speaker"
        ], '');

        $this->mockWordPress->assertMethodCalledWith('get_query_var', 'entity_id');
    }

    public function testReturnsUrl()
    {
        $this->mockWordPress->setReturnValue('get_query_var', '7');
        $this->mockWordPress->setMappedReturnValues('get_query_var', [
           ['entity_id', 7],
           ['slug', 'the_slug']
        ]);

        $this->block->render([
            "feed" => "Avorg\Endpoint\RssEndpoint\Speaker"
        ], '');

        $data = $this->mockTwig->getRenderedData();

        $this->assertEquals(
            "http://${_SERVER['HTTP_HOST']}/english/sermons/presenters/podcast/7/latest/the_slug",
            $data['url']
        );
    }
}