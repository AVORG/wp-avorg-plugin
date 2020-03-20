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

	/** @var PresentationRepository $presentationRepository */
	protected $presentationRepository;

	/** @var Renderer $renderer */
	private $renderer;

	public function __construct(
		Factory $factory,
		Php $php,
		PresentationRepository $presentationRepository,
		Renderer $renderer,
		WordPress $wp
	)
	{
		parent::__construct($wp);

		$this->factory = $factory;
		$this->php = $php;
		$this->presentationRepository = $presentationRepository;
		$this->renderer = $renderer;
	}

	public function getOutput()
	{
		$this->php->header('Content-Type: application/rss+xml; charset=utf-8');

		/* TODO: Remove $_SERVER manipulations once codebase is deployed to production */
		$snapshot = $_SERVER['HTTP_HOST'];
        $_SERVER['HTTP_HOST'] = 'audioverse.org';

        $output = $this->renderer->render("page-feed.twig", [
            "recordings" => $this->prepareRecordings(),
            "title" => $this->getTitle(),
            "subtitle" => $this->getSubtitle(),
            "image" => $this->getImage() ?: AVORG_LOGO_URL
        ], TRUE) ?: "";

        $_SERVER['HTTP_HOST'] = $snapshot;

        return $output;
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
}