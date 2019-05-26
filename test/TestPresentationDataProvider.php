<?php

final class TestPresentationDataProvider extends Avorg\TestCase
{
	public function testExists()
	{
		$query = $this->factory->secure("\\Avorg\\PresentationDataProvider");

		$this->assertInstanceOf("\\Avorg\\iDataProvider", $query);
	}
}