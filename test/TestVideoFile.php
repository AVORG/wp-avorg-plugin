<?php

final class TestVideoFile extends Avorg\TestCase
{
    public function testExists()
    {
        $this->assertTrue(class_exists("\\Avorg\\MediaFile\\VideoFile"));
    }
}