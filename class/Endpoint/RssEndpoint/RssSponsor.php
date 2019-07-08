<?php

namespace Avorg\Endpoint\RssEndpoint;


use Avorg\DataObjectRepository\RecordingRepository;
use Avorg\DataObjectRepository\SponsorRepository;
use Avorg\Endpoint\RssEndpoint;
use Avorg\Php;
use Avorg\Renderer;
use Avorg\WordPress;
use function defined;
use Exception;
use natlib\Factory;

if (!defined('ABSPATH')) exit;

class RssSponsor extends RssEndpoint
{
	/** @var SponsorRepository $sponsorRepository */
	private $sponsorRepository;

	public function __construct(
		Factory $factory,
		Php $php,
		SponsorRepository $sponsorRepository,
		RecordingRepository $recordingRepository,
		Renderer $renderer,
		WordPress $wp
	)
	{
		parent::__construct($factory, $php, $recordingRepository, $renderer, $wp);

		$this->sponsorRepository = $sponsorRepository;
	}

	/**
	 * @throws Exception
	 */
	protected function getRecordings()
	{
		return $this->recordingRepository->getSponsorRecordings($this->getEntityId());
	}

	protected function getTitle()
	{
		$sponsor = $this->getEntity();

		return "$sponsor->title Sermons";
	}

	protected function getSubtitle()
	{
		$sponsor = $this->getEntity();

		return "The latest AudioVerse sermons sponsored by $sponsor->title";
	}

	protected function getImage()
	{
		$sponsor = $this->getEntity();

		return $sponsor->photo256;
	}

	private function getEntity()
	{
		return $this->sponsorRepository->getSponsor($this->getEntityId());
	}
}