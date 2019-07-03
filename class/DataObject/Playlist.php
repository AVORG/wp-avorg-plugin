<?php

namespace Avorg\DataObject;


use Avorg\DataObject;

if (!defined('ABSPATH')) exit;

class Playlist extends DataObject
{
	protected $detailClass = "Avorg\\Page\\Playlist\\Detail";
}