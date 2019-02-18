<?php

namespace Avorg\Endpoint\RssEndpoint;


use Avorg\Endpoint\RssEndpoint;

if (!\defined('ABSPATH')) exit;

class RssLatest extends RssEndpoint
{
	protected $routeFormat = "{ language }/podcasts/latest";
}