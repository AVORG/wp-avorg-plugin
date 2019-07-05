<?php

namespace Avorg\Endpoint\RssEndpoint;


use Avorg\DataObjectRepository\RecordingRepository;
use Avorg\Endpoint\RssEndpoint;
use Avorg\Php;
use Avorg\Renderer;
use Avorg\WordPress;
use natlib\Factory;

if (!\defined('ABSPATH')) exit;

class RssLatest extends RssEndpoint
{
	protected function getRecordings()
	{
		return $this->recordingRepository->getRecordings();
	}

	protected function getTitle()
	{
		return "AudioVerse Latest Recordings";
	}

	protected function getSubtitle()
	{
		return "The latest recordings at AudioVerse";
	}
}