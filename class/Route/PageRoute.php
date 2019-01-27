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

	public function getRedirect()
	{
		$baseRedirect = "index.php?page_id=$this->pageId";
		$queryVarString = $this->getQueryVarString();

		return $queryVarString ? "$baseRedirect&$queryVarString" : $baseRedirect;
	}
}