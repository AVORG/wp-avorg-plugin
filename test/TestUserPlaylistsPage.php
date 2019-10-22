<?php

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

    public function testExists()
    {
        $_SESSION = [
            'userId' => 'the_id',
            'sessionToken' => 'the_token'
        ];

        $this->page->addUi('');

        $this->mockAvorgApi->assertMethodCalledWith('getPlaylistsByUser', 'the_id', 'the_token');
    }

    public function testRendersViewWithPlaylists()
    {
        

        $this->page->addUi('');
    }
}