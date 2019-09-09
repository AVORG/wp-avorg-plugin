<?php

namespace Avorg\MediaFile;

use Avorg\MediaFile;

if (!\defined('ABSPATH')) exit;

class VideoFile extends MediaFile
{
    public function getStreamUrl()
    {
        return property_exists($this->apiMediaFile, 'downloadURL') ? $this->apiMediaFile->downloadURL : null;
    }

    public function getType()
	{
		return "application/x-mpegURL";
	}
}