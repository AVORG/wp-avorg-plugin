<?php

final class TestEndpointFactory extends Avorg\TestCase
{
	/** @var \Avorg\EndpointFactory $endpointFactory */
	protected $endpointFactory;

	public function setUp()
	{
		parent::setUp();

		$this->endpointFactory = $this->factory->secure("Avorg\\EndpointFactory");
	}

	public function testGetEndpointById()
	{
		$endpoint = $this->endpointFactory->getEndpointById("Avorg_Endpoint_RssEndpoint_Latest");

		$this->assertInstanceOf("Avorg\\Endpoint\\RssEndpoint\\Latest", $endpoint);
	}

	/**
	 * @param $badId
	 * @dataProvider badIdProvider
	 */
	public function testIgnoresInvalidEndpointNames($badId)
	{
		$result = $this->endpointFactory->getEndpointById($badId);

		$this->assertNull($result);
	}

	public function badIdProvider()
	{
		return [
			["FakeEndpoint"],
			["Avorg_Endpoint_NotHere"]
		];
	}
}