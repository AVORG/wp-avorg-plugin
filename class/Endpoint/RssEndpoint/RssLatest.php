<?php

namespace Avorg\Endpoint\RssEndpoint;


use Avorg\Endpoint\RssEndpoint;
use Avorg\Php;
use Avorg\RecordingRepository;
use Avorg\Renderer;
use Avorg\RouteFactory;
use natlib\Factory;

if (!\defined('ABSPATH')) exit;

class RssLatest extends RssEndpoint
{
	/** @var RecordingRepository $recordingRepository */
	private $recordingRepository;

	public function __construct(
		Factory $factory,
		Php $php,
		RecordingRepository $recordingRepository,
		Renderer $renderer
	)
	{
		parent::__construct($factory, $php, $renderer);

		$this->recordingRepository = $recordingRepository;
	}

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