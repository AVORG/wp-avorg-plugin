<?php

use Avorg\Endpoint\Recording;

final class TestPresentationsEndpoint extends Avorg\TestCase
{
	/** @var Recording $endpoint */
	protected $endpoint;

	/**
	 * @throws ReflectionException
	 */
	protected function setUp(): void
	{
		parent::setUp();

		$this->endpoint = $this->factory->secure("Avorg\\Endpoint\\Presentations");
	}

	public function testReturnsJson()
	{
		$output = $this->endpoint->getOutput();

		$this->assertJson($output);
	}

	public function testGetsPresentations()
	{
		$this->endpoint->getOutput();

		$this->mockAvorgApi->assertMethodCalled("getRecordings");
	}

	public function testGetsListName()
	{
		$this->endpoint->getOutput();

		$this->mockWordPress->assertMethodCalledWith("get_query_var", "entity_id");
	}

	public function testGetsPresentationsWithList()
	{
		$this->mockWordPress->setReturnValue("get_query_var", "featured");

		$this->endpoint->getOutput();

		$this->mockAvorgApi->assertMethodCalledWith("getRecordings", "featured");
	}

	public function testReturnsJsonEncodedPresentations()
	{
		$this->mockAvorgApi->loadRecordings(['id' => 5]);

		$output = $this->endpoint->getOutput();

		$this->assertEquals(5, json_decode($output)[0]->id);
	}

	public function testRejectsInvalidList()
	{
		$this->mockWordPress->setReturnValue("get_query_var", "invalid");

		$this->endpoint->getOutput();

		$this->mockAvorgApi->assertMethodCalledWith("getRecordings", "");
	}

	public function testStandardizesCase()
	{
		$this->mockWordPress->setReturnValue("get_query_var", "FEATURED");

		$this->endpoint->getOutput();

		$this->mockAvorgApi->assertMethodCalledWith("getRecordings", "featured");
	}
}