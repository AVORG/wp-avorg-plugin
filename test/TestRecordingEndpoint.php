<?php

use Avorg\Endpoint\Recording;

final class TestRecordingEndpoint extends Avorg\TestCase
{
	/** @var Recording $endpoint */
	protected $endpoint;

	protected function setUp()
	{
		parent::setUp();

		$this->endpoint = $this->factory->secure("Avorg\\Endpoint\\Recording");
	}

	public function testEndpoint()
	{
		$this->mockAvorgApi->loadRecording(["title" => "My Recording"]);

		$output = $this->endpoint->getOutput();

		$object = json_decode($output);

		$this->assertEquals("My Recording", $object->title);
	}

	public function testGetsEntityId()
	{
		$this->endpoint->getOutput();

		$this->mockWordPress->assertMethodCalledWith("get_query_var", "entity_id");
	}

	public function testUsesQueryId()
	{
		$this->mockWordPress->setReturnValue("get_query_var", 7);

		$this->endpoint->getOutput();

		$this->mockAvorgApi->assertMethodCalledWith("getRecording", 7);
	}
}