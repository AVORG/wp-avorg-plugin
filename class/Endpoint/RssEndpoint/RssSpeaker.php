<?php

namespace Avorg\Endpoint\RssEndpoint;


use Avorg\Endpoint\RssEndpoint;

if (!\defined('ABSPATH')) exit;

class RssSpeaker extends RssEndpoint
{
	protected $routeFormat = "{ language }/sermons/presenters/podcast/{ entity_id:[0-9]+ }/latest/{ slug }";

	protected function getRecordings()
	{
		// TODO: Implement getRecordings() method.
	}

	protected function getTitle()
	{
		// TODO: Implement getTitle() method.
	}

	protected function getSubtitle()
	{
		// TODO: Implement getSubtitle() method.
	}
}