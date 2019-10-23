<?php

use Avorg\Page\Playlist\UserPlaylists;

final class TestUserPlaylistsPage extends Avorg\TestCase
{
    /** @var UserPlaylists $page */
    private $page;

    /**
     * @throws ReflectionException
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->mockWordPress->passCurrentPageCheck();

        $this->page = $this->factory->make("Avorg\\Page\\Playlist\\UserPlaylists");
    }

    public function testRendersCorrectView()
    {
        $this->page->addUi('');

        $this->mockTwig->assertTwigTemplateRendered('page-userPlaylists.twig');
    }

    public function testUsesDefaultTitle()
    {
        $this->mockWordPress->setReturnValue("get_option", false);

        $this->page->createPage();

        $this->mockWordPress->assertPageCreated('Your Playlists');
    }
}