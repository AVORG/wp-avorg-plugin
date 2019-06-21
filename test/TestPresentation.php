<?php

use Avorg\Presentation;

final class TestPresentation extends Avorg\TestCase
{
	/**
	 * @param $apiResponse
	 * @return Presentation
	 * @throws ReflectionException
	 */
    protected function getPresentationForApiResponse($apiResponse)
    {
        $apiResponseObject = $this->convertArrayToObjectRecursively($apiResponse);

        return $this->factory->make("Avorg\\Presentation")->setPresentation($apiResponseObject);
    }

    public function testIncludesPresenterName()
    {
        $presentation = $this->getPresentationForApiResponse([
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

        $this->assertEquals($expected, $presentation->getPresenters()[0]["name"]);
    }

    public function testIncludesTitle()
    {
        $presentation = $this->getPresentationForApiResponse([
            "title" => "sermon_title"
        ]);

        $this->assertEquals("sermon_title", $presentation->getTitle());
    }

    public function testIncludesRecordings()
    {
        $presentation = $this->getPresentationForApiResponse([
            "mediaFiles" => [[]]
        ]);

        $this->assertInstanceOf("\\Avorg\\MediaFile", $presentation->getAudioFiles()[0]);
    }

    public function testPassesThroughStreamUrl()
    {
        $presentation = $this->getPresentationForApiResponse([
            "mediaFiles" => [[
                "streamURL" => "stream_url"
            ]]
        ]);

        $this->assertEquals("stream_url", $presentation->getAudioFiles()[0]->getStreamUrl());
    }

    public function testPassesThroughStreamUrlForVideoFiles()
    {
        $presentation = $this->getPresentationForApiResponse([
            "videoFiles" => [[
                "downloadURL" => "stream_url",
                "container" => "m3u8_ios"
            ]]
        ]);

        $this->assertEquals("stream_url", $presentation->getVideoFiles()[0]->getStreamUrl());
    }

    public function testUsesVideoFileClass()
    {
        $presentation = $this->getPresentationForApiResponse([
            "videoFiles" => [[
                "container" => "m3u8_ios"
            ]]
        ]);

        $this->assertInstanceOf("\\Avorg\\MediaFile\\VideoFile", $presentation->getVideoFiles()[0]);
    }

    public function testOnlyReturnsM3u8Videos()
    {
        $presentation = $this->getPresentationForApiResponse([
            "videoFiles" => [
                [
                    "container" => "m3u8_ios"
                ],
                [
                    "container" => "something_else"
                ]
            ]
        ]);

        $this->assertCount(1, $presentation->getVideoFiles());
    }

    public function testGetLogUrl()
    {
        $presentation = $this->getPresentationForApiResponse([
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

        $this->assertEquals("log_url", $presentation->getLogUrl());
    }

    public function testGetLogUrlWhenNoLogUrl()
    {
        $presentation = $this->getPresentationForApiResponse([
            "videoFiles" => [
                [
                    "container" => "m3u8_ios"
                ]
            ]
        ]);

        $this->assertEquals(null, $presentation->getLogUrl());
    }

    public function testIncludesPublishDate()
	{
		$presentation = $this->getPresentationForApiResponse([
			"publishDate" => "2018-02-19 05:22:17"
		]);

		$this->assertEquals("2018-02-19 05:22:17", $presentation->getDatePublished());
	}

	public function testGetUrl()
	{
		$apiRecording = $this->convertArrayToObjectRecursively([
			"lang" => "en",
			"id" => "1836",
			"title" => 'E.P. Daniels and True Revival'
		]);

		$presentation = $this->getPresentationForApiResponse($apiRecording);

		$this->assertEquals(
			"http://localhost:8080/english/sermons/recordings/1836/ep-daniels-and-true-revival.html",
			$presentation->getUrl()
		);
	}

	public function testGetId()
	{
		$apiRecording = $this->convertArrayToObjectRecursively([
			"id" => "1836"
		]);

		$presentation = $this->getPresentationForApiResponse($apiRecording);

		$this->assertEquals(1836, $presentation->getId());
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
		$apiRecording = $this->convertArrayToObjectRecursively($recordingArray);
		$presentation = $this->getPresentationForApiResponse($apiRecording);
		$json         = $presentation->toJson();
		$object       = json_decode($json, true);

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
			]
		];
	}

	public function testGetPresenterString()
	{
		$presentation = $this->getPresentationForApiResponse([
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
			$presentation->getPresentersString()
		);
	}
}