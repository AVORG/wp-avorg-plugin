<?php

namespace Avorg\Page\Playlist;

use Avorg\DataObjectRepository\RecordingRepository;
use Avorg\Page;
use Avorg\DataObject\Recording;
use Avorg\Renderer;
use Avorg\ScriptFactory;
use Avorg\WordPress;
use function defined;

if (!defined('ABSPATH')) exit;

class Detail extends Page
{
	/** @var RecordingRepository $recordingRepository */
	private $recordingRepository;

	/** @var ScriptFactory $scriptFactory */
	private $scriptFactory;

	protected $defaultPageTitle = "Playlist Detail";
	protected $defaultPageContent = "Playlist Detail";
	protected $twigTemplate = "page-playlist.twig";

	public function __construct(
		RecordingRepository $presenterRepository,
		Renderer $renderer,
		ScriptFactory $scriptFactory,
		WordPress $wp
	)
	{
		parent::__construct($renderer, $wp);

		$this->recordingRepository = $presenterRepository;
		$this->scriptFactory = $scriptFactory;
	}

	protected function getData()
	{
		return [
			"recordings" => $this->getRecordings()
		];
	}

	/**
	 * @return array
	 */
	private function getRecordings()
	{
		$recordings = $this->recordingRepository->getPlaylistRecordings($this->getEntityId());

		$array_reduce = array_reduce($recordings, function ($carry, Recording $recording) {
			$carry[$recording->getId()] = $recording;

			return $carry;
		}, []);

		return $array_reduce;
	}

	protected function getTitle()
	{
		// TODO: Implement getEntityTitle() method.
	}
}