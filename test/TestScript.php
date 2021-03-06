<?php

use Avorg\Script;

final class TestScript extends Avorg\TestCase
{
	/** @var Script $script */
	protected $script;

	protected function setUp(): void
	{
		parent::setUp();

		$this->script = $this->factory->make("Avorg\\Script");
	}

	public function testTheScript()
	{
		$this->script->setPath("//the_path");

		$this->script->enqueue();

		$this->mockWordPress->assertMethodCalledWith(
			"wp_enqueue_script",
			"Avorg_Script_" . sha1("//the_path"),
			"//the_path",
            [],
            null,
            false
		);
	}

	public function testRegistersCallback()
	{
	    $this->script->setActions('wp_enqueue_scripts');

		$this->script->registerCallbacks();

		$this->mockWordPress->assertMethodCalledWith(
			"add_action",
			"wp_enqueue_scripts",
			[$this->script, "enqueue"]
		);
	}

	public function testThrowsExceptionIfNoPath()
	{
		$this->expectException(Exception::class);

		$this->script->enqueue();
	}

	public function testGetsAdminAjaxUrl()
	{
		$this->script->setPath("the_path");

		$this->script->enqueue();

		$this->mockWordPress->assertMethodCalledWith("admin_url", "admin-ajax.php");
	}

    public function testIncludesQueryData()
    {
        $this->mockWordPress->setReturnValue('get_all_query_vars', 'query_vars');

        $this->script->setPath("the_path");

        $this->script->enqueue();

        $this->mockWordPress->assertAnyCallMatches('wp_localize_script', function($call) {
            return $call[2]['query'] === 'query_vars';
        });
    }

    public function testIncludesPostId()
    {
        $this->mockWordPress->setReturnValue('get_the_ID', '7');

        $this->script->setPath("the_path");

        $this->script->enqueue();

        $this->mockWordPress->assertAnyCallMatches('wp_localize_script', function($call) {
            return $call[2]['post_id'] === 7;
        });
    }
}
