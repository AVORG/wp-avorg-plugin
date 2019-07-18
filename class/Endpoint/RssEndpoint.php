<?php

namespace Avorg\Endpoint;


use Avorg\DataObjectRepository\PresentationRepository;
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

	/** @var PresentationRepository $recordingRepository */
	protected $recordingRepository;

	/** @var Renderer $renderer */
	private $renderer;

	/** @var WordPress $wp */
	private $wp;

	public function __construct(
		Factory $factory,
		Php $php,
		PresentationRepository $recordingRepository,
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
			"recordings" => $this->prepareRecordings(),
			"title" => $this->getTitle(),
			"subtitle" => $this->getSubtitle(),
			"image" => $this->getImage() ?: AVORG_LOGO_URL
		], TRUE ) ?: "";
	}

	private function prepareRecordings()
	{
		return array_slice($this->getRecordings(), 0, 100);
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