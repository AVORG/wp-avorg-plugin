<?php

namespace Avorg\Endpoint\RssEndpoint;


use Avorg\DataObjectRepository\RecordingRepository;
use Avorg\Endpoint\RssEndpoint;
use Avorg\Php;
use Avorg\Renderer;
use function defined;
use Exception;
use natlib\Factory;

if (!defined('ABSPATH')) exit;

class RssTrending extends RssEndpoint
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

	/**
	 * @return array
	 * @throws Exception
	 */
	protected function getRecordings()
	{
		return $this->recordingRepository->getRecordings("popular");
	}

	protected function getTitle()
	{
		return "AudioVerse Trending Recordings";
	}

	protected function getSubtitle()
	{
		return "Recently-popular recordings at AudioVerse";
	}
}