<?php

final class TestBible extends Avorg\TestCase
{

	/**
	 * @throws ReflectionException
	 */
	public function testGetUrl()
	{
		$bible = $this->makeBible([
            "dam_id" => "ENGESV",
            "drama" => 2
		]);

		$this->assertEquals(
			"http://${_SERVER['HTTP_HOST']}/english/audiobibles/books/ENGESV/2",
			$bible->getUrl()
		);
	}
}