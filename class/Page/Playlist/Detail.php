<?php

namespace Avorg\Page\Playlist;

use Avorg\DataObjectRepository\PresentationRepository;
use Avorg\Page;
use Avorg\DataObject\Recording;
use Avorg\Renderer;
use Avorg\ScriptFactory;
use Avorg\WordPress;
use function defined;
use Exception;

if (!defined('ABSPATH')) exit;

class Detail extends Page
{
	/** @var PresentationRepository $recordingRepository */
	private $recordingRepository;

	/** @var ScriptFactory $scriptFactory */
	private $scriptFactory;

	protected $defaultPageTitle = "Playlist Detail";
	protected $defaultPageContent = "Playlist Detail";
	protected $twigTemplate = "page-playlist.twig";

	public function __construct(
		PresentationRepository $presenterRepository,
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
	 * @throws Exception
	 */
	private function getRecordings()
	{
		$recordings = $this->recordingRepository->getPlaylistPresentations($this->getEntityId());

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