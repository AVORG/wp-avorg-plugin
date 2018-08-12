<?php

final class TestLocalization extends Avorg\TestCase
{
	/** @var \Avorg\Localization $localization */
	protected $localization;
	
	protected function setUp()
	{
		parent::setUp();
		
		$this->localization = $this->factory->getLocalization();
	}
	
	public function testExists()
	{
		$this->assertTrue(class_exists("\\Avorg\\Localization"));
	}
	
	public function test__iFunctionExsits()
	{
		$this->assertTrue(method_exists($this->localization, "i__"));
	}
	
	public function testRegistersLanguageAdditionMethod()
	{
		$this->assertWordPressFunctionCalledWith(
			"add_action",
			"plugins_loaded",
			[$this->localization, "loadLanguages"]
		);
	}
	
	public function testLoadLanguagesCallsTextDomainLoadingFunction()
	{
		$this->localization->loadLanguages();
		
		$this->assertWordPressFunctionCalledWith(
			"load_plugin_textdomain",
			false,
			"languages"
		);
	}
}