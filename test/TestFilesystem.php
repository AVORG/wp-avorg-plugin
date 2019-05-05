<?php

final class TestFilesystem extends Avorg\TestCase
{
	/** @var \Avorg\Filesystem $filesystem */
	protected $filesystem;

	protected function setUp()
	{
		parent::setUp();

		$this->filesystem = new Avorg\Filesystem();
	}

	public function testExists()
	{
		$this->assertTrue(method_exists($this->filesystem, "getFile"));
	}

	public function testGetMatchingPathsRecursive()
	{
		$paths = $this->filesystem->getMatchingPathsRecursive("test/sandbox", "/\.txt/");

		$this->assertEquals([
			AVORG_BASE_PATH . "/test/sandbox/dummy.txt"
		], $paths);
	}

	public function testChecksPathInPluginFolder()
	{
		$paths = $this->filesystem->getMatchingPathsRecursive("../", "/\.php/");

		$this->assertNull($paths);
	}

	public function testGetFileContents()
	{
		$contents = $this->filesystem->getFile("test/sandbox/dummy.txt");

		$this->assertEquals("hello world", $contents);
	}

	public function testChecksPathInPluginFolderWhenGettingContents()
	{
		$contents = $this->filesystem->getFile("../.gitignore");

		$this->assertNull($contents);
	}
}