<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

abstract class MediaFile
{
    protected $apiMediaFile;

    public function __construct($apiMediaFile)
    {
        $this->apiMediaFile = $apiMediaFile;
    }

    public function getStreamUrl()
    {
        return $this->apiMediaFile->streamURL;
    }

    public function getType()
    {
        $ext = pathinfo($this->apiMediaFile->filename, PATHINFO_EXTENSION);

        return "audio/$ext";
    }
}