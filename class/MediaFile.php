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

    abstract public function getType();
}