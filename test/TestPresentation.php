<?php

final class TestPresentation extends Avorg\TestCase
{
    /**
     * @param $apiResponse
     * @return \Avorg\Presentation
     */
    protected function getPresentationForApiResponse($apiResponse)
    {
        $apiResponseObject = $this->convertArrayToObjectRecursively($apiResponse);

        return new \Avorg\Presentation($apiResponseObject);
    }

    public function testIncludesPresenterPhotos()
    {
        $presentation = $this->getPresentationForApiResponse([
            "presenters" => [
                [
                    "photo86" => "photo_url"
                ]
            ]
        ]);

        $this->assertEquals("photo_url", $presentation->getPresenters()[0]["photo"]);
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
}