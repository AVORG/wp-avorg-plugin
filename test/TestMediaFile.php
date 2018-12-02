<?php

final class TestMediaFile extends Avorg\TestCase
{
    public function testIncludesType()
    {
        $apiMediaFile = $this->convertArrayToObjectRecursively([
            "filename" => "file.mp3"
        ]);

        $mediaFile = new \Avorg\MediaFile\AudioFile($apiMediaFile);

        $this->assertEquals("audio/mp3", $mediaFile->getType());
    }
}