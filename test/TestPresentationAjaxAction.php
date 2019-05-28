<?php

final class TestPresentationAjaxAction extends Avorg\TestCase
{
	/** @var \Avorg\AjaxAction\Presentation $action */
	protected $action;

	protected function setUp()
	{
		parent::setUp();

		$this->action = $this->factory->secure("Avorg\\AjaxAction\\Presentation");
	}

	public function testActionReturnsSuccessFalseWhenFailsToRetrievePresentation()
	{
		$response = $this->getResponse();

		$this->assertFalse($response["success"]);
	}

	public function testActionChecksNonce()
	{
		$this->action->run();

		$this->mockWordPress->assertMethodCalledWith("check_ajax_referer", "Avorg_AjaxAction_Presentation");
	}

	public function testGetsPresentation()
	{
		$this->mockAvorgApi->loadPresentation(["title" => "My Recording"]);

		$presentation = $this->getResponsePresentation();

		$this->assertEquals("My Recording", $presentation["title"]);
	}

	public function testReturnsSuccessTrueOnSuccess()
	{
		$this->mockAvorgApi->loadPresentation(["title" => "My Recording"]);

		$response = $this->getResponse();

		$this->assertTrue($response["success"]);
	}

	public function testUsesPassedPresentationId()
	{
		$_POST["entity_id"] = 7;

		$this->action->run();

		$this->mockAvorgApi->assertMethodCalledWith("getPresentation", 7);
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
			"wp_ajax_Avorg_AjaxAction_Presentation",
			[$this->action, "run"]
		);

		$this->mockWordPress->assertMethodCalledWith(
			"add_action",
			"wp_ajax_nopriv_Avorg_AjaxAction_Presentation",
			[$this->action, "run"]
		);
	}

	/**
	 * @return mixed
	 */
	private function getResponsePresentation()
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

		return json_decode(ob_get_contents(), true);
	}
}