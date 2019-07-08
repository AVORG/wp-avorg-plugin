<?php

namespace Avorg\Route;

use Avorg\Route;

if (!\defined('ABSPATH')) exit;

class PageRoute extends Route
{

	public function getBaseRoute()
	{
		return "index.php?page_id=$this->id";
	}
}