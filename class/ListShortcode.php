<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class ListShortcode
{
    /** @var RecordingRepository $recordingRepository */
    private $recordingRepository;

	/** @var Renderer $twig */
	private $twig;
	
	/** @var WordPress $wp */
	private $wp;
	
	public function __construct(RecordingRepository $recordingRepository, Renderer $twig, WordPress $wp)
	{
	    $this->recordingRepository = $recordingRepository;
		$this->twig = $twig;
		$this->wp = $wp;
	}
	
	public function addShortcode()
	{
		$this->wp->add_shortcode( "avorg-list", [$this, "renderShortcode"]);
	}

	/**
	 * @param $attributes
	 * @return string
	 * @throws \Exception
	 */
	public function renderShortcode($attributes)
	{
		$validListTypes = ["featured","popular"];
		$shouldUseListAttribute = isset($attributes["list"]) && in_array($attributes["list"], $validListTypes);
		$list = ($shouldUseListAttribute) ? $attributes["list"] : null;
		$recordings = $this->recordingRepository->getRecordings($list);

		return $this->twig->render(
			"shortcode-list.twig",
			["recordings" => $recordings],
			TRUE
		);
	}
}