<?php

namespace Avorg\DataObject;

use Avorg\DataObject;
use function defined;

if (!defined('ABSPATH')) exit;

class Topic extends DataObject
{
	protected $detailClass = "Avorg\Page\Topic\Detail";
}