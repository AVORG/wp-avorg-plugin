<?php

use Avorg\AvorgApi;

final class TestAvorgApi extends Avorg\TestCase
{
    /** @var AvorgApi $api */
    private $api;

    protected function setUp(): void
    {
        parent::setUp();

        $this->api = $this->factory->make('Avorg\\AvorgApi');
    }

    public function testIsFavorited()
    {
        $raw = '{"result":{"recording":{"59888":[{"recordings":{"id":"7556"}}]}}}';

        $this->mockGuzzle->setReturnValue('handleOld', json_decode($raw));

        $isFavorited = $this->api->isFavorited(
            7556,
            'user_id',
            'session_token'
        );

        $this->assertTrue($isFavorited);
    }
}