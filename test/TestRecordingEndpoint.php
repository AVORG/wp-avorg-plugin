<?php

final class TestRecordingEndpoint extends Avorg\TestCase
{
	/** @var \Avorg\Endpoint\Recording $Recording */
	protected $Recording;

	protected function setUp()
	{
		parent::setUp();

		$this->Recording = $this->factory->secure("Avorg\\Endpoint\\Recording");
	}

	public function testEndpoint()
	{
		$this->mockAvorgApi->loadRecording(["title" => "My Recording"]);

		$output = $this->Recording->getOutput();

		$object = json_decode($output);

		$this->assertEquals("My Recording", $object->title);
	}

	public function testGetsEntityId()
	{
		$this->Recording->getOutput();

		$this->mockWordPress->assertMethodCalledWith("get_query_var", "entity_id");
	}

	public function testUsesQueryId()
	{
		$this->mockWordPress->setReturnValue("get_query_var", 7);

		$this->Recording->getOutput();

		$this->mockAvorgApi->assertMethodCalledWith("getRecording", 7);
	}
}