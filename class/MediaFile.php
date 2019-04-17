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

    public function getSize()
	{
		return $this->getFloatAttribute("filesize");
	}

	public function getDuration()
	{
		return $this->getFloatAttribute("duration");
	}

	public function getDurationString()
	{
		return gmdate("H:i:s", $this->getDuration());
	}

	public function getBitrate()
	{
		return $this->getFloatAttribute("bitrate");
	}

	public function getId()
	{
		return $this->getFloatAttribute("fileId");
	}

	private function getFloatAttribute($name)
	{
		return floatval($this->apiMediaFile->$name);
	}

    abstract public function getType();
}