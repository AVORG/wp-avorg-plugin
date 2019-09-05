<?php

final class TestMediaFile extends Avorg\TestCase
{
	/**
	 * @dataProvider attributeAccessProvider
	 */
	public function testAttributeAccess($apiResponseArray, $getterName, $expected )
    {
        $apiMediaFile = $this->arrayToObject($apiResponseArray);

        $mediaFile = new \Avorg\MediaFile\AudioFile($apiMediaFile);

        $this->assertEquals($expected, $mediaFile->$getterName());
    }

    public function attributeAccessProvider()
	{
		return [
			"type" => [
				["filename" => "file.mp3"],
				"getType",
				"audio/mp3"
			],
			"size" => [
				["filesize" => "77777"],
				"getSize",
				77777
			],
			"duration" => [
				["duration" => "111.5"],
				"getDuration",
				111.5
			],
			"bitrate" => [
				["bitrate" => "96"],
				"getBitrate",
				96
			],
			"id" => [
				["fileId" => "123"],
				"getId",
				123
			],
			"durationString" => [
				["duration" => "111.5"],
				"getDurationString",
				"00:01:51"
			],
		];
	}
}