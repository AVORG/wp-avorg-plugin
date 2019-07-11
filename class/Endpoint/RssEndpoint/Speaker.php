<?php

namespace Avorg\Endpoint\RssEndpoint;


use Avorg\DataObject;
use Avorg\DataObject\Presenter;
use Avorg\DataObjectRepository\PresenterRepository;
use Avorg\DataObjectRepository\RecordingRepository;
use Avorg\Endpoint\RssEndpoint;
use Avorg\Php;
use Avorg\Renderer;
use Avorg\WordPress;
use function defined;
use Exception;
use natlib\Factory;

if (!defined('ABSPATH')) exit;

class Speaker extends RssEndpoint
{
	/** @var PresenterRepository $presenterRepository */
	private $presenterRepository;

	public function __construct(
		Factory $factory,
		Php $php,
		PresenterRepository $sponsorRepository,
		RecordingRepository $recordingRepository,
		Renderer $renderer,
		WordPress $wp
	)
	{
		parent::__construct($factory, $php, $recordingRepository, $renderer, $wp);

		$this->presenterRepository = $sponsorRepository;
	}

	/**
	 * @throws Exception
	 */
	protected function getRecordings()
	{
		return $this->recordingRepository->getPresenterRecordings($this->getEntityId());
	}

	/**
	 * @return string
	 * @throws Exception
	 */
	protected function getTitle()
	{
		$name = $this->getPresenterName();

		return "Sermons by $name";
	}

	/**
	 * @return string
	 * @throws Exception
	 */
	protected function getSubtitle()
	{
		$name = $this->getPresenterName();

		return "The latest AudioVerse sermons by $name";
	}

	/**
	 * @return string|null
	 * @throws Exception
	 */
	protected function getImage()
	{
		$presenter = $this->getEntity();

		return $presenter->photo256;
	}

	/**
	 * @return mixed
	 * @throws Exception
	 */
	private function getPresenterName()
	{
		/** @var Presenter $presenter */
		$presenter = $this->getEntity();

		return $presenter ? $presenter->getName() : null;
	}

	/**
	 * @return DataObject|null
	 * @throws Exception
	 */
	private function getEntity()
	{
		return $this->presenterRepository->getPresenter($this->getEntityId());
	}
}