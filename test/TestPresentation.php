<?php

final class TestPresentation extends Avorg\TestCase
{
	/**
	 * @throws ReflectionException
	 */
	public function testIncludesPresenterName()
	{
		$recording = $this->makePresentation([
			"presenters" => [
				[
					"givenName" => "first_name",
					"surname" => "last_name",
					"suffix" => "suffix"
				]
			]
		]);

		$expected = [
			"first" => "first_name",
			"last" => "last_name",
			"suffix" => "suffix"
		];

		$this->assertEquals($expected, $recording->getPresenters()[0]["name"]);
	}

	/**
	 * @throws ReflectionException
	 */
	public function testIncludesTitle()
	{
		$recording = $this->makePresentation([
			"title" => "sermon_title"
		]);

		$this->assertEquals("sermon_title", $recording->title);
	}

	/**
	 * @throws ReflectionException
	 */
	public function testIncludesRecordings()
	{
		$recording = $this->makePresentation([
			"mediaFiles" => [[]]
		]);

		$this->assertInstanceOf("\\Avorg\\MediaFile", $recording->getAudioFiles()[0]);
	}

	/**
	 * @throws ReflectionException
	 */
	public function testPassesThroughStreamUrl()
	{
		$recording = $this->makePresentation([
			"mediaFiles" => [[
				"streamURL" => "stream_url"
			]]
		]);

		$this->assertEquals("stream_url", $recording->getAudioFiles()[0]->getStreamUrl());
	}

	/**
	 * @throws ReflectionException
	 */
	public function testPassesThroughStreamUrlForVideoFiles()
	{
		$recording = $this->makePresentation([
			"videoFiles" => [[
				"downloadURL" => "stream_url",
				"container" => "m3u8_ios"
			]]
		]);

		$this->assertEquals("stream_url", $recording->getVideoFiles()[0]->getStreamUrl());
	}

	/**
	 * @throws ReflectionException
	 */
	public function testUsesVideoFileClass()
	{
		$recording = $this->makePresentation([
			"videoFiles" => [[
				"container" => "m3u8_ios"
			]]
		]);

		$this->assertInstanceOf("\\Avorg\\MediaFile\\VideoFile", $recording->getVideoFiles()[0]);
	}

	/**
	 * @throws ReflectionException
	 */
	public function testOnlyReturnsM3u8Videos()
	{
		$recording = $this->makePresentation([
			"videoFiles" => [
				[
					"container" => "m3u8_ios"
				],
				[
					"container" => "something_else"
				]
			]
		]);

		$this->assertCount(1, $recording->getVideoFiles());
	}

	public function testGetLogUrl()
	{
		$recording = $this->makePresentation([
			"videoFiles" => [
				[
					"logURL" => "log_url",
					"container" => "m3u8_ios"
				],
				[
					"logURL" => null,
					"container" => "m3u8_ios"
				]
			]
		]);

		$this->assertEquals("log_url", $recording->getLogUrl());
	}

	public function testGetLogUrlWhenNoLogUrl()
	{
		$recording = $this->makePresentation([
			"videoFiles" => [
				[
					"container" => "m3u8_ios"
				]
			]
		]);

		$this->assertEquals(null, $recording->getLogUrl());
	}

	public function testIncludesPublishDate()
	{
		$recording = $this->makePresentation([
			"publishDate" => "2018-02-19 05:22:17"
		]);

		$this->assertEquals("2018-02-19 05:22:17", $recording->getDatePublished());
	}

	public function testGetUrl()
	{
		$apiRecording = $this->arrayToObject([
			"lang" => "en",
			"id" => "1836",
			"title" => 'E.P. Daniels and True Revival'
		]);

		$recording = $this->makePresentation($apiRecording);

		$this->assertEquals(
			"http://localhost:8080/english/sermons/recordings/1836/ep-daniels-and-true-revival.html",
			$recording->getUrl()
		);
	}

	public function testGetId()
	{
		$apiRecording = $this->arrayToObject([
			"id" => "1836"
		]);

		$recording = $this->makePresentation($apiRecording);

		$this->assertEquals(1836, $recording->getId());
	}

	/**
	 * @param $recordingArray
	 * @param $expectedKey
	 * @param $expectedValue
	 * @throws ReflectionException
	 * @dataProvider jsonTestProvider
	 */
	public function testToJson($recordingArray, $expectedKey, $expectedValue)
	{
		$apiRecording = $this->arrayToObject($recordingArray);
		$recording = $this->makePresentation($apiRecording);
		$json = json_encode($recording);
		$object = json_decode($json, true);

		$this->assertEquals($expectedValue, $object[$expectedKey]);
	}

	public function jsonTestProvider()
	{
		return [
			"id" => [
				["id" => "1836"],
				"id",
				1836
			],
			"title" => [
				["title" => 'E.P. Daniels and True Revival'],
				"title",
				"E.P. Daniels and True Revival"
			],
			"url" => [
				[
					"lang" => "en",
					"id" => "1836",
					"title" => 'E.P. Daniels and True Revival'
				],
				"url",
				"http://localhost:8080/english/sermons/recordings/1836/ep-daniels-and-true-revival.html"
			],
			"audio files" => [
				[
					"mediaFiles" => [[
						"streamURL" => "stream_url",
						"filename" => "audio.mp3"
					]]
				],
				"audioFiles",
				[[
					"streamUrl" => "stream_url",
					"type" => "audio/mp3"
				]]
			],
			"video files" => [
				[
					"videoFiles" => [[
						"downloadURL" => "stream_url",
						"filename" => "video.mp4",
						"container" => "m3u8_ios"
					]]
				],
				"videoFiles",
				[[
					"streamUrl" => "stream_url",
					"type" => "application/x-mpegURL"
				]]
			],
			"log url" => [
				[
					"videoFiles" => [
						[
							"logURL" => "log_url",
							"container" => "m3u8_ios"
						],
						[
							"logURL" => null,
							"container" => "m3u8_ios"
						]
					]
				],
				"logUrl",
				"log_url"
			],
			"date published" => [
				["publishDate" => "2018-02-19 05:22:17"],
				"datePublished",
				"2018-02-19 05:22:17"
			],
			"presenters" => [
				[
					"presenters" => [
						[
							"photo256" => "photo_url",
							"givenName" => "first_name",
							"surname" => "last_name",
							"suffix" => "suffix"
						]
					]
				],
				"presenters",
				[
					[
						"photo" => "photo_url",
						"name" => [
							"first" => "first_name",
							"last" => "last_name",
							"suffix" => "suffix"
						]
					]
				]
			],
			"presenters string" => [
				[
					"presenters" => [
						[
							"photo256" => "photo_url",
							"givenName" => "first_name",
							"surname" => "last_name",
							"suffix" => "suffix"
						]
					]
				],
				"presentersString",
				"first_name last_name suffix"
			],
			"image" => [
				[
					"photo86" => "photo_url"
				],
				"image",
				"photo_url"
			],
			"image fallback to presenter" => [
				[
					"presenters" => [
						[
							"photo256" => "photo_url",
							"givenName" => "first_name",
							"surname" => "last_name",
							"suffix" => "suffix"
						]
					]
				],
				"image",
				"photo_url"
			],
			"image fallback to AudioVerse logo" => [
				[],
				"image",
				"https://s.audioverse.org/english/gallery/sponsors/_/600/600/default-logo.png"
			],
			"no description" => [
				[
					"presenters" => [
						[
							"photo256" => "photo_url",
							"givenName" => "first_name",
							"surname" => "last_name",
							"suffix" => "suffix"
						],
						[
							"photo256" => "photo_url",
							"givenName" => "first_name",
							"surname" => "last_name",
							"suffix" => "suffix"
						]
					]
				],
				"description",
				"Presenters: first_name last_name suffix, first_name last_name suffix"
			],
			"description" => [
				[
					"presenters" => [
						[
							"photo256" => "photo_url",
							"givenName" => "first_name",
							"surname" => "last_name",
							"suffix" => "suffix"
						]
					],
					"description" => "This is the description."
				],
				"description",
				"This is the description. Presenters: first_name last_name suffix"
			],
		];
	}

	public function testGetPresenterString()
	{
		$recording = $this->makePresentation([
			"presenters" => [
				[
					"givenName" => "first_name",
					"surname" => "last_name",
					"suffix" => "suffix"
				],
				[
					"givenName" => "first_name",
					"surname" => "last_name",
					"suffix" => ""
				],
				[
					"givenName" => "first_name",
					"surname" => "",
					"suffix" => "suffix"
				],
			]
		]);

		$this->assertEquals(
			"first_name last_name suffix, first_name last_name, first_name suffix",
			$recording->getPresentersString()
		);
	}

	public function testJsonEncodable()
	{
		$recording = $this->makePresentation(["presenters" => [[]]]);

		$this->assertTrue(method_exists($recording, "jsonSerialize"));
	}

	public function testMagicGetUsesGetters()
	{
		$recording = $this->makePresentation([
			"presenters" => [
				[
					"givenName" => "first_name",
					"surname" => "last_name",
					"suffix" => "suffix"
				]
			]
		]);

		$this->assertEquals('first_name', $recording->presenters[0]['name']['first']);
	}
}