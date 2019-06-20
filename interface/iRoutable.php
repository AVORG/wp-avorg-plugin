<?php

namespace Avorg;

use function defined;

if (!defined('ABSPATH')) exit;


interface iRoutable
{
	public function getRouteId();
}