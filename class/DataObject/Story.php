<?php

namespace Avorg\DataObject;


use Avorg\DataObject;

if (!defined('ABSPATH')) exit;

class Story extends DataObject
{
	protected $detailClass = "Avorg\\Page\\Story\\Detail";
}