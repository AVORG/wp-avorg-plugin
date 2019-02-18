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
		$endpoint = $this->endpointFactory->getEndpoint("RssEndpoint\\RssLatest");

		$this->assertInstanceOf("Avorg\\Endpoint\\RssEndpoint\\RssLatest", $endpoint);
	}

	public function testIgnoresInvalidEndpointNames()
	{
		$result = $this->endpointFactory->getEndpoint("FakeEndpoint");

		$this->assertNull($result);
	}
}