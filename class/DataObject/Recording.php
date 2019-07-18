<?php

namespace Avorg\DataObject;

use Avorg\DataObject;
use Avorg\MediaFile;
use function defined;
use Exception;

if (!defined('ABSPATH')) exit;

abstract class Recording extends DataObject
{
	/**
	 * @return array
	 * @throws Exception
	 */
	public function toArray()
	{
		return array_merge((array)$this->data, [
			"id" => $this->getId(),
			"url" => $this->getUrl(),
			"audioFiles" => [],
			"videoFiles" => [],
			"presenters" => []
		]);
	}
}