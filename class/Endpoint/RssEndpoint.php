<?php

namespace Avorg\Endpoint;


use Avorg\Endpoint;
use Avorg\Factory;
use Avorg\Feed;
use Avorg\Php;
use Avorg\RouteFactory;

if (!\defined('ABSPATH')) exit;

class RssEndpoint extends Endpoint
{
	/** @var Factory $factory */
	private $factory;

	/** @var Php $php */
	private $php;

	protected $routeFormat = "{ language }/sermons/presenters/podcast/{ entity_id:[0-9]+ }/latest/{ slug }";

	public function __construct(Factory $factory, Php $php, RouteFactory $routeFactory)
	{
		parent::__construct($routeFactory);

		$this->factory = $factory;
		$this->php = $php;
	}

	public function getOutput()
	{
		$this->php->header('Content-Type: application/rss+xml; charset=utf-8');

		/** @var Feed $feed */
		$feed = $this->factory->make("Feed");

		return $feed->toXml();
	}
}