<?php

namespace Avorg\Endpoint;


use Avorg\Endpoint;
use Avorg\Factory;
use Avorg\Feed;
use Avorg\Php;
use Avorg\RouteFactory;

if (!\defined('ABSPATH')) exit;

abstract class RssEndpoint extends Endpoint
{
	/** @var Factory $factory */
	private $factory;

	/** @var Php $php */
	private $php;

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

		$feed->setRecordings($this->getRecordings());

		return $feed->toXml();
	}

	abstract protected function getRecordings();
}