<?php

final class TestFileRoute extends Avorg\TestCase
{
	/** @var \Avorg\Route\FileRoute $feed */
	protected $fileRoute;

	public function setUp()
	{
		parent::setUp();

		$this->fileRoute = $this->factory->make("Route\\FileRoute");
	}

	public function testExists()
	{
		$this->assertInstanceOf("\\Avorg\\Route\\FileRoute", $this->fileRoute);
	}

	public function testSetFile()
	{
		$this->mockWordPress->setReturnValue("plugin_dir_url", "plugin/dir/url/");

		$redirect = $this->fileRoute->setFormat("my/route")
			->setFile("path/to/file.php")
			->getRedirect();

		$this->assertEquals("plugin/dir/url/path/to/file.php", $redirect);
	}
}