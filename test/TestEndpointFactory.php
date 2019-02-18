<?php

final class TestEndpointFactory extends Avorg\TestCase
{
	/** @var \Avorg\EndpointFactory $endpointFactory */
	protected $endpointFactory;

	public function setUp()
	{
		parent::setUp();

		$this->endpointFactory = $this->factory->get("EndpointFactory");
	}

	public function testGetEndpointById()
	{
		$endpoint = $this->endpointFactory->getEndpoint("Avorg_Endpoint_RssEndpoint_RssLatest");

		$this->assertInstanceOf("Avorg\\Endpoint\\RssEndpoint\\RssLatest", $endpoint);
	}

	/**
	 * @param $badId
	 * @dataProvider badIdProvider
	 */
	public function testIgnoresInvalidEndpointNames($badId)
	{
		$result = $this->endpointFactory->getEndpoint($badId);

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