<?php

namespace Avorg\MediaFile;

use Avorg\MediaFile;

if (!\defined('ABSPATH')) exit;

class VideoFile extends MediaFile
{
    public function getStreamUrl()
    {
        return $this->apiMediaFile->downloadURL;
    }

    public function getType()
	{
		return "application/x-mpegURL";
	}
}