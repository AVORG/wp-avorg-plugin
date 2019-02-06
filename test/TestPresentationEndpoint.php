<?php

final class TestPresentationEndpoint extends Avorg\TestCase
{
	/** @var \Avorg\Endpoint\PresentationEndpoint $presentationEndpoint */
	protected $presentationEndpoint;

	protected function setUp()
	{
		parent::setUp();

		$this->presentationEndpoint = $this->factory->get("Endpoint\\PresentationEndpoint");
	}

	public function testEndpoint()
	{
		$this->mockAvorgApi->loadPresentation(["title" => "My Recording"]);

		$output = $this->presentationEndpoint->getOutput();

		$object = json_decode($output);

		$this->assertEquals("My Recording", $object->title);
	}

	public function testGetsEntityId()
	{
		$this->presentationEndpoint->getOutput();

		$this->mockWordPress->assertMethodCalledWith("get_query_var", "entity_id");
	}

	public function testUsesQueryId()
	{
		$this->mockWordPress->setReturnValue("get_query_var", 7);

		$this->presentationEndpoint->getOutput();

		$this->mockAvorgApi->assertMethodCalledWith("getPresentation", 7);
	}
}