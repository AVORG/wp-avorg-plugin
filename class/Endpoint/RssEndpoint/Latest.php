<?php

namespace Avorg\Endpoint\RssEndpoint;


use Avorg\DataObjectRepository\PresentationRepository;
use Avorg\Endpoint\RssEndpoint;
use Avorg\Php;
use Avorg\Renderer;
use Avorg\WordPress;
use natlib\Factory;

if (!\defined('ABSPATH')) exit;

class Latest extends RssEndpoint
{
	protected function getRecordings()
	{
		return $this->presentationRepository->getPresentations();
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