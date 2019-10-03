<?php

use Avorg\Route\EndpointRoute;

final class TestEndpointRoute extends Avorg\TestCase
{
	/** @var EndpointRoute $feed */
	protected $fileRoute;

	public function setUp(): void
	{
		parent::setUp();

		$this->fileRoute = $this->factory->make("Avorg\\Route\\EndpointRoute");
	}

	public function testExists()
	{
		$this->assertInstanceOf("\\Avorg\\Route\\EndpointRoute", $this->fileRoute);
	}

	public function testSetId()
	{
		$this->mockWordPress->setReturnValue("plugin_dir_url", "http://localhost:8080/plugin/dir/url/");

		$pairs = $this->fileRoute->setFormat("my/route")
			->setId("myId")
			->getRewriteRules();

		$redirect = $pairs["English"]["redirect"];

		$this->assertEquals("plugin/dir/url/endpoint.php?endpoint_id=myId", $redirect);
	}

	public function testGetsPluginDirUrlOfBasedir()
	{
		$this->fileRoute->setFormat("my/route")
			->setId("myId")
			->getRewriteRules();

		$this->mockWordPress->assertMethodCalledWith("plugin_dir_url", AVORG_BASE_PATH . "/endpoint.php");
	}

	public function testEndpointRouteUsesHtaccessStylePlaceholders()
	{
		$this->mockWordPress->setReturnValue("plugin_dir_url", "http://localhost:8080/plugin/dir/url/");

		$pairs = $this->fileRoute->setFormat("my/{route}")
			->setId("myId")
			->getRewriteRules();

		$redirect = $pairs["English"]["redirect"];

		$this->assertEquals("plugin/dir/url/endpoint.php?endpoint_id=myId&route=$1", $redirect);
	}
}