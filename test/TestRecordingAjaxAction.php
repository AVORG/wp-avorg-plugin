<?php

use Avorg\AjaxAction\Recording;

final class TestRecordingAjaxAction extends Avorg\TestCase
{
	/** @var Recording $action */
	protected $action;

	protected function setUp(): void
	{
		parent::setUp();

		$this->action = $this->factory->secure("Avorg\\AjaxAction\\Recording");
	}

	public function testActionReturnsSuccessFalseWhenFailsToRetrieveRecording()
	{
		$response = $this->getResponse();

		$this->assertFalse($response["success"]);
	}

	public function testActionChecksNonce()
	{
		$this->action->run();

		$this->mockWordPress->assertMethodCalledWith("check_ajax_referer", "Avorg_AjaxAction_Recording");
	}

	public function testGetsRecording()
	{
		$this->mockAvorgApi->loadRecording(["title" => "My Recording"]);

		$recording = $this->getResponseRecording();

		$this->assertEquals("My Recording", $recording["title"]);
	}

	public function testReturnsSuccessTrueOnSuccess()
	{
		$this->mockAvorgApi->loadRecording(["title" => "My Recording"]);

		$response = $this->getResponse();

		$this->assertTrue($response["success"]);
	}

	public function testUsesPassedRecordingId()
	{
		$_POST["entity_id"] = 7;

		$this->action->run();

		$this->mockAvorgApi->assertMethodCalledWith("getRecording", 7);
	}

	public function testDiesOnCompletion()
	{
		$this->action->run();

		$this->mockPhp->assertMethodCalled("doDie");
	}

	public function testAddsActions()
	{
		$this->action->registerCallbacks();

		$this->mockWordPress->assertMethodCalledWith(
			"add_action",
			"wp_ajax_Avorg_AjaxAction_Recording",
			[$this->action, "run"]
		);

		$this->mockWordPress->assertMethodCalledWith(
			"add_action",
			"wp_ajax_nopriv_Avorg_AjaxAction_Recording",
			[$this->action, "run"]
		);
	}

	/**
	 * @return mixed
	 */
	private function getResponseRecording()
	{
		$response = $this->getResponse();

		return json_decode($response["data"], true);
	}

	/**
	 * @return mixed
	 */
	private function getResponse()
	{
		$this->action->run();

		$calls = $this->mockPhp->getCalls('doEcho');

		return json_decode($calls[0][0], true);
	}
}