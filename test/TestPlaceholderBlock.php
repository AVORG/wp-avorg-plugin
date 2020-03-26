<?php

use Avorg\Block\RelatedSermons;
use natlib\Stub;

final class TestPlaceholderBlock extends Avorg\TestCase
{
    /** @var RelatedSermons $block */
    protected $block;

    /**
     * @throws ReflectionException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->block = $this->factory->secure("Avorg\\Block\\Placeholder");
    }

    private function makePost($avorgMediaIds = []) {
        return $this->arrayToObject([
            "avorgMediaIds" => $avorgMediaIds
        ]);
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

    public function testRendersTemplate()
    {
        $this->block->render([], '');

        $this->mockTwig->assertTwigTemplateRendered("block-placeholder.twig");
    }

    public function testGetItem()
    {
        $this->mockWordPress->setReturnValue("get_query_var", "media_id");

        $this->block->render([
            "id" => "the_id"
        ], '');

        $this->mockWordPress->assertMethodCalledWith('get_posts', [
            'posts_per_page' => -1,
            'post_type' => 'avorg-content-bits',
            'meta_query' => [
                [
                    'key' => 'avorgBitIdentifier',
                    'value' => 'the_id'
                ]
            ]
        ]);
    }

    public function testSelectsRandomPost()
    {
        $this->mockWordPress->setReturnValue("get_posts", [$this->makePost()]);

        $this->block->render([], '');

        $this->mockPhp->assertMethodCalledWith("arrayRand", [$this->makePost()]);
    }

    public function testRendersBlockWithRandomPost()
    {
        $post = $this->makePost();
        $this->mockPhp->setReturnValue("arrayRand", $post);

        $this->block->render([], '');

        $this->mockTwig->assertTwigTemplateRenderedWithData('block-placeholder.twig', [
            "content" => $post
        ]);
    }

    public function testFiltersPostsForMediaIdAssociation()
    {
        $this->mockWordPress->setReturnValue("get_query_var", 5);

        $match = $this->makePost([5]);

        $this->mockWordPress->setReturnValue("get_posts", [
            $match,
            $this->makePost()
        ]);

        $this->block->render([], '');

        $this->mockPhp->assertMethodCalledWith("arrayRand", [$match]);
    }

    public function testStopsFilteringIfNoMatches()
    {
        $this->mockWordPress->setReturnValue("get_query_var", 5);

        $post = $this->makePost();

        $this->mockWordPress->setReturnValue("get_posts", [
            $post
        ]);

        $this->block->render([], '');

        $this->mockPhp->assertMethodCalledWith("arrayRand", [$post]);
    }

    public function testDoesNotUseMismatchedPosts()
    {
        $this->mockWordPress->setReturnValue("get_query_var", 5);

        $post = $this->makePost();

        $this->mockWordPress->setReturnValue("get_posts", [
            $post,
            $this->makePost([3])
        ]);

        $this->block->render([], '');

        $this->mockPhp->assertMethodCalledWith("arrayRand", [$post]);
    }

    public function testNeverUsesContentForWrongMediaId()
    {
        $this->mockWordPress->setReturnValue("get_posts", [
            $this->makePost([3])
        ]);

        $this->block->render([], '');

        $this->mockPhp->assertMethodCalledWith("arrayRand", []);
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testPostWithStringInsteadOfArray()
    {
        $this->mockWordPress->setReturnValue("get_posts", [
            $this->makePost('')
        ]);

        $this->block->render([], '');
    }

}