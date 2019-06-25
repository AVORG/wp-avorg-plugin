<?php

namespace Avorg;

use function defined;

if (!defined('ABSPATH')) exit;


interface iJsonEncodable
{
	public function toJson();
}