<?php

use Avorg\Pwa;

final class TestPwa extends Avorg\TestCase
{
	/** @var Pwa $pwa */
	protected $pwa;

	protected function setUp(): void
	{
		parent::setUp();

		$this->pwa = $this->factory->secure("Avorg\\Pwa");
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