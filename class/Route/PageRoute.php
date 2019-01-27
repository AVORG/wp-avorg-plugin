<?php

namespace Avorg\Route;

use Avorg\Route;

if (!\defined('ABSPATH')) exit;

class PageRoute extends Route
{
	private $pageId;

	/**
	 * @param mixed $pageId
	 * @return PageRoute
	 */
	public function setPageId($pageId)
	{
		$this->pageId = $pageId;

		return $this;
	}

	function getBaseRoute()
	{
		return "index.php?page_id=$this->pageId";
	}
}