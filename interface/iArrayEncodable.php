<?php

namespace Avorg;

use function defined;

if (!defined('ABSPATH')) exit;


interface iArrayEncodable
{
	public function toArray();
}