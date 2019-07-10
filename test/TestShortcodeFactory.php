<?php

use Avorg\ShortcodeFactory;

final class TestShortcodeFactory extends Avorg\TestCase
{
	/** @var ShortcodeFactory $shortcodeFactory */
	protected $shortcodeFactory;

	/**
	 * @throws ReflectionException
	 */
	public function setUp()
	{
		parent::setUp();

		$this->shortcodeFactory = $this->factory->secure("Avorg\\ShortcodeFactory");
	}

	public function testGetsClasses()
	{
		$this->shortcodeFactory->getShortcodes();

		$this->mockFilesystem->assertMethodCalledWith("getClassesRecursively", "class/Shortcode");
	}

	public function testReturnsObjects()
	{
		$result = $this->shortcodeFactory->getShortcodes();

		$this->assertInstanceOf("Avorg\\Shortcode", $result[0]);
	}
}