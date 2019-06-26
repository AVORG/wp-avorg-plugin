<?php

use Avorg\Topic;

final class TestTopic extends Avorg\TestCase
{
	/**
	 * @param array $data
	 * @return Topic
	 * @throws ReflectionException
	 */
	private function makeTopic(array $data)
	{
		/** @var Topic $topic */
		$topic = $this->factory->make("Avorg\\Topic");
		$topic->setData((object)$data);
		return $topic;
	}

	public function testIsset()
	{
		$topic = $this->makeTopic(["title" => "my_title"]);

		$this->assertTrue($topic->__isset("title"));
	}

	public function testGetUrl()
	{
		$topic = $this->makeTopic([
			"id" => "887",
			"title" => "Agriculture"
		]);

		$this->assertEquals(
			"http://${_SERVER['HTTP_HOST']}/english/topics/887/agriculture.html",
			$topic->getUrl()
		);
	}
}