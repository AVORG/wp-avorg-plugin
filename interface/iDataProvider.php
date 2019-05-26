<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

interface iDataProvider {
	public function getData($queryData);

	public function getTitle($queryData);
}