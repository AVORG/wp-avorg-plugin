<?php

final class TestScript extends Avorg\TestCase
{
	/** @var \Avorg\Script $script */
	protected $script;

	protected function setUp()
	{
		parent::setUp();

		$this->script = $this->factory->make("Script");
	}

	public function testTheScript()
	{
		$this->script->setPath("the_path");

		$this->script->enqueue();

		$this->mockWordPress->assertMethodCalledWith(
			"wp_enqueue_script",
			"Avorg_Script_" . sha1("the_path"),
			"the_path"
		);
	}

	public function testRegistersCallback()
	{
		$this->script->registerCallbacks();

		$this->mockWordPress->assertMethodCalledWith(
			"add_action",
			"wp_enqueue_scripts",
			[$this->script, "enqueue"]
		);
	}

	public function testAddsNonce()
	{
		$this->script->setPath("the_path");
		$this->script->setActions($this->factory->make("AjaxAction\\Presentation"));

		$this->script->enqueue();

		$this->mockWordPress->assertMethodCalledWith(
			"wp_create_nonce",
			"Avorg_AjaxAction_Presentation"
		);
	}

	public function testThrowsExceptionIfNoPath()
	{
		$this->expectException(Exception::class);

		$this->script->enqueue();
	}

	public function testLocalizesScriptWithNonces()
	{
		$this->mockWordPress
			->setReturnValue("wp_create_nonce", "the_nonce")
			->setReturnValue("admin_url", "ajax_url");

		$this->script
			->setPath("the_path")
			->setActions($this->factory->make("AjaxAction\\Presentation"))
			->enqueue();

		$this->mockWordPress->assertMethodCalledWith(
			"wp_localize_script",
			"Avorg_Script_" . sha1("the_path"),
			"avorg",
			[
				"nonces" => [
					"presentation" => "the_nonce"
				],
				"ajax_url" => "ajax_url"
			]
		);
	}

	public function testGetsAdminAjaxUrl()
	{
		$this->script->setPath("the_path");

		$this->script->enqueue();

		$this->mockWordPress->assertMethodCalledWith("admin_url", "admin-ajax.php");
	}
}
