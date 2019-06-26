<?php

namespace Avorg\DataObject;

use Avorg\DataObject;
use function defined;

if (!defined('ABSPATH')) exit;

class Bible extends DataObject
{
	protected $detailClass = "Avorg\Page\Bible\Detail";

	protected function getSlug()
	{

	}
}