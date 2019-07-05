<?php

namespace Avorg\Endpoint;


use Avorg\DataObjectRepository\RecordingRepository;
use Avorg\Endpoint;
use Avorg\Php;
use Avorg\Renderer;
use Avorg\WordPress;
use natlib\Factory;

if (!\defined('ABSPATH')) exit;

abstract class RssEndpoint extends Endpoint
{
	/** @var Factory $factory */
	private $factory;

	/** @var Php $php */
	private $php;

	/** @var RecordingRepository $recordingRepository */
	protected $recordingRepository;

	/** @var Renderer $renderer */
	private $renderer;

	/** @var WordPress $wp */
	private $wp;

	public function __construct(
		Factory $factory,
		Php $php,
		RecordingRepository $recordingRepository,
		Renderer $renderer,
		WordPress $wp
	)
	{
		$this->factory = $factory;
		$this->php = $php;
		$this->recordingRepository = $recordingRepository;
		$this->renderer = $renderer;
		$this->wp = $wp;
	}

	public function getOutput()
	{
		$this->php->header('Content-Type: application/rss+xml; charset=utf-8');

		return $this->renderer->render("page-feed.twig", [
			"recordings" => $this->getRecordings(),
			"title" => $this->getTitle(),
			"subtitle" => $this->getSubtitle(),
			"image" => $this->getImage() ?: AVORG_LOGO_URL
		], TRUE ) ?: "";
	}

	abstract protected function getRecordings();
	abstract protected function getTitle();
	abstract protected function getSubtitle();

	protected function getImage()
	{
		return null;
	}

	/**
	 * @return mixed
	 */
	protected function getEntityId()
	{
		return $this->wp->get_query_var("entity_id");
	}
}