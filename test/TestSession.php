<?php

use Avorg\Session;

final class TestSession extends Avorg\TestCase
{
    /** @var Session */
    private $session;

    protected function setUp(): void
    {
        parent::setUp();

        $this->session = $this->factory->make('Avorg\\Session');
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testLoadNullData()
    {
        $_SESSION = null;

        $this->session->loadData(null);
    }

    public function testLoadObject()
    {
        $this->session->loadData($this->arrayToObject([
            'test' => 'value'
        ]));

        $this->assertEquals('value', $this->session->test);
    }
}