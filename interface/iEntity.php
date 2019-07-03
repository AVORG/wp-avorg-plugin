<?php

namespace Avorg;

use function defined;

if (!defined('ABSPATH')) exit;


interface iEntity
{
	public function toJson();

	public function toArray();
}