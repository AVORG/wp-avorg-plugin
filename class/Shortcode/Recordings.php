<?php

namespace Avorg\Shortcode;

use Avorg\DataObjectRepository\RecordingRepository;
use Avorg\Renderer;
use Avorg\Router;
use Avorg\Shortcode;
use Avorg\WordPress;
use function defined;
use Exception;

if (!defined('ABSPATH')) exit;

class Recordings extends Shortcode
{
	protected $handle = "avorg-list";
	protected $template = "shortcode-list.twig";

	/** @var RecordingRepository $recordingRepository */
	private $recordingRepository;

	/** @var Router $router */
	private $router;

	public function __construct(
		RecordingRepository $recordingRepository,
		Router $router,
		Renderer $twig,
		WordPress $wp
	)
	{
		parent::__construct($twig, $wp);

		$this->recordingRepository = $recordingRepository;
		$this->router = $router;
	}

	/**
	 * @param $attributes
	 * @return array
	 * @throws Exception
	 */
	protected function getData($attributes)
	{
		$list = $this->getListName($attributes);

		return [
			"recordings" => $this->recordingRepository->getRecordings($list),
			"rss" => $this->getRssUrl($list)
		];
	}

	private function getListName($attributes)
	{
		if (!array_key_exists("list", (array) $attributes)) return null;

		$list = strtolower($attributes["list"]);

		if (!in_array($list, ["featured", "popular"])) return null;

		return $list;
	}

	/**
	 * @param $list
	 * @return string
	 * @throws Exception
	 */
	private function getRssUrl($list)
	{
		if ($list === null) {
			return $this->router->buildUrl("Avorg\Endpoint\RssEndpoint\Latest");
		}

		if ($list === "popular") {
			return $this->router->buildUrl("Avorg\Endpoint\RssEndpoint\Trending");
		}

		return null;
	}
}