<?php

namespace Avorg\MediaFile;

use Avorg\MediaFile;

if (!\defined('ABSPATH')) exit;

class AudioFile extends MediaFile
{
	public function getType()
	{
		$ext = pathinfo($this->apiMediaFile->filename, PATHINFO_EXTENSION);

		return "audio/$ext";
	}
}