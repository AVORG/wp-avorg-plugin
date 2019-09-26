<?php

use Avorg\Block\RelatedSermons;
use Avorg\DataObjectRepository\PresentationRepository;

final class TestRelatedSermonsBlock extends Avorg\TestCase
{
	/** @var RelatedSermons $block */
	protected $block;

	/**
	 * @throws ReflectionException
	 */
	protected function setUp(): void
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
        $this->block->render([], '');

        $this->mockTwig->assertMethodCalled('render');
    }

    public function testRendersTemplate()
    {
        $this->block->render([], '');

        $this->mockTwig->assertTwigTemplateRendered("block-relatedSermons.twig");
    }

    public function testReturnsHtml()
    {
        $this->mockTwig->setReturnValue("render", "html");

        $result = $this->block->render([], '');

        $this->assertEquals("html", $result);
    }

    public function testGetsEntityId()
    {
        $this->block->render([], '');

        $this->mockWordPress->assertMethodCalledWith("get_query_var", "entity_id");
    }

    public function testIncludesRecordings() {
        $this->block->render([], '');

        $data = $this->mockTwig->getRenderedData("block-relatedSermons.twig");

        $this->assertArrayHasKey("recordings", $data);
    }

    public function testGetsPresentation()
    {
        $this->mockWordPress->setReturnValue("get_query_var", 7);

        $this->block->render([], '');

        $this->mockAvorgApi->assertMethodCalledWith("getRecording", 7);
    }

    public function testReturnsPresentations()
    {
        $this->mockAvorgApi->loadRecording(["presenters" => [
            $this->arrayToObject([])
        ]]);
        $this->mockAvorgApi->loadConferenceRecordings(["id" => "1"]);
        $this->mockAvorgApi->loadSeriesRecordings(["id" => "2"]);
        $this->mockAvorgApi->loadSponsorRecordings(["id" => "3"]);
        $this->mockAvorgApi->loadPresenterRecordings(["id" => "4"]);

        $this->block->render([], '');

        $data = $this->mockTwig->getRenderedData("block-relatedSermons.twig");

        $this->assertEquals("1", $data["recordings"][0]->id);
    }

    public function testRequestsRandomSubset()
    {
        $this->mockAvorgApi->loadRecording(["presenters" => [
            $this->arrayToObject([])
        ]]);
        $this->mockAvorgApi->loadConferenceRecordings(
            ["id" => "1"],
            ["id" => "2"],
            ["id" => "3"],
            ["id" => "4"]
        );

        $this->block->render([], '');

        $this->mockTwig->getRenderedData("block-relatedSermons.twig");

        /** @var PresentationRepository $presentationRepository */
        $presentationRepository = $this->factory->make(
            "Avorg\\DataObjectRepository\\PresentationRepository");
        $presentations = $presentationRepository->getRelatedPresentations(0);

        $this->mockPhp->assertMethodCalledWith("arrayRand", $presentations, 3);
    }
}