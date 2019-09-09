<?php

namespace Avorg\MediaFile;

use Avorg\MediaFile;
use function defined;

if (!defined('ABSPATH')) exit;

class AudioFile extends MediaFile
{
	public function getType()
	{
		$ext = pathinfo($this->apiMediaFile->filename, PATHINFO_EXTENSION);

		return "audio/$ext";
	}
}