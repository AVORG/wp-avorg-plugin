<?php

final class TestPwa extends Avorg\TestCase
{
	/** @var \Avorg\Pwa $pwa */
	protected $pwa;

	protected function setUp()
	{
		parent::setUp();

		$this->pwa = $this->factory->secure("Pwa");
	}

	public function testExists()
	{
		$this->assertInstanceOf("\\Avorg\\Pwa", $this->pwa);
	}

	public function testRegistersServiceWorkerCallback()
	{
		$this->pwa->registerCallbacks();

		$this->mockWordPress->assertMethodCalledWith(
			"add_action",
			"wp_front_service_worker",
			[$this->pwa, "registerServiceWorker"]
		);
	}
}