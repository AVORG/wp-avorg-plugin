<?php

namespace Avorg;

use Avorg\DataObjectRepository\RecordingRepository;
use Exception;

if (!\defined('ABSPATH')) exit;

class ListShortcode
{
	/** @var RecordingRepository $recordingRepository */
	private $recordingRepository;

	/** @var Router $router */
	private $router;

	/** @var Renderer $twig */
	private $twig;

	/** @var WordPress $wp */
	private $wp;

	public function __construct(
		RecordingRepository $recordingRepository,
		Router $router,
		Renderer $twig,
		WordPress $wp
	)
	{
		$this->recordingRepository = $recordingRepository;
		$this->router = $router;
		$this->twig = $twig;
		$this->wp = $wp;
	}

	public function addShortcode()
	{
		$this->wp->add_shortcode("avorg-list", [$this, "renderShortcode"]);
	}

	/**
	 * @param $attributes
	 * @return string
	 * @throws Exception
	 */
	public function renderShortcode($attributes)
	{
		$list = $this->getListName($attributes);
		$data = [
			"recordings" => $this->recordingRepository->getRecordings($list),
			"rss" => $this->getRssUrl($list)
		];

		return $this->twig->render("shortcode-list.twig", $data, TRUE);
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
			return $this->router->buildUrl("Avorg\Endpoint\RssEndpoint\RssLatest");
		}

		if ($list === "popular") {
			return $this->router->buildUrl("Avorg\Endpoint\RssEndpoint\RssTrending");
		}

		return null;
	}
}