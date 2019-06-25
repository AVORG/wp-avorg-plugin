<?php

final class TestTopic extends Avorg\TestCase
{
	public function testIsset()
	{
		/** @var \Avorg\Topic $topic */
		$topic = $this->factory->make("Avorg\\Topic");
		$topic->setData((object) ["title" => "my_title"]);

		$this->assertTrue($topic->__isset("title"));
	}
}