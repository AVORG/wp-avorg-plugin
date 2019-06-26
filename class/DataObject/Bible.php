<?php

namespace Avorg\DataObject;

use Avorg\DataObject;
use function defined;

if (!defined('ABSPATH')) exit;

class Bible extends DataObject
{
	protected $detailClass = "Avorg\Page\Bible\Detail";

	public function getUrl()
	{
		return $this->router->buildUrl($this->detailClass, [
			"version" => $this->dam_id,
			"drama" => $this->drama
		]);
	}

	protected function getSlug() {}
}