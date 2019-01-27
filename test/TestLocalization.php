<?php

final class TestLocalization extends Avorg\TestCase
{
	/** @var \Avorg\Localization $localization */
	protected $localization;
	
	protected function setUp()
	{
		parent::setUp();
		
		$this->localization = $this->factory->get("Localization");
	}
	
	public function test__iFunctionExists()
	{
		$this->assertTrue(method_exists($this->localization, "i__"));
	}
	
	public function testLoadLanguagesCallsTextDomainLoadingFunction()
	{
		$this->localization->loadLanguages();
		
		$this->mockWordPress->assertMethodCalledWith(
			"load_plugin_textdomain",
			$this->textDomain,
			false,
			"wp-avorg-plugin/languages"
		);
	}
	
	public function testCallsDoubleUnderscoresWithDomain()
	{
		$this->localization->i__("to translate");
		
		$this->mockWordPress->assertMethodCalledWith("__", "to translate", $this->textDomain);
	}
	
	public function testCallsUnderscoreEnWithDomain()
	{
		$this->localization->_n("cat","cats",3);
		
		$this->mockWordPress->assertMethodCalledWith("_n", "cat", "cats", 3, $this->textDomain);
	}
}