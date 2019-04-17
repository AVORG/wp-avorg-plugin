<?php

namespace Avorg\Endpoint;


use Avorg\Endpoint;
use Avorg\Factory;
use Avorg\Feed;
use Avorg\Php;
use Avorg\Renderer;
use Avorg\RouteFactory;

if (!\defined('ABSPATH')) exit;

abstract class RssEndpoint extends Endpoint
{
	/** @var Factory $factory */
	private $factory;

	/** @var Php $php */
	private $php;

	/** @var Renderer $renderer */
	private $renderer;

	public function __construct(
		Factory $factory,
		Php $php,
		Renderer $renderer,
		RouteFactory $routeFactory
	)
	{
		parent::__construct($routeFactory);

		$this->factory = $factory;
		$this->php = $php;
		$this->renderer = $renderer;
	}

	public function getOutput()
	{
		$this->php->header('Content-Type: application/rss+xml; charset=utf-8');

		return $this->renderer->render("page-feed.twig", [
			"recordings" => $this->getRecordings(),
			"title" => $this->getTitle(),
			"subtitle" => $this->getSubtitle(),
			"image" => AVORG_LOGO_URL
		], TRUE ) ?: "";
	}

	abstract protected function getRecordings();
	abstract protected function getTitle();
	abstract protected function getSubtitle();
}