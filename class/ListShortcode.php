<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class ListShortcode
{
    /** @var PresentationRepository $presentationRepository */
    private $presentationRepository;

	/** @var Renderer $twig */
	private $twig;
	
	/** @var WordPress $wp */
	private $wp;
	
	public function __construct(PresentationRepository $presentationRepository, Renderer $twig, WordPress $wp)
	{
	    $this->presentationRepository = $presentationRepository;
		$this->twig = $twig;
		$this->wp = $wp;
	}
	
	public function addShortcode()
	{
		$this->wp->call("add_shortcode", "avorg-list", [$this, "renderShortcode"]);
	}
	
	public function renderShortcode($attributes)
	{
		$validListTypes = ["featured","popular"];
		$shouldUseListAttribute = isset($attributes["list"]) && in_array($attributes["list"], $validListTypes);
		$list = ($shouldUseListAttribute) ? $attributes["list"] : null;
		$recordings = $this->presentationRepository->getPresentations($list);

		return $this->twig->render(
			"shortcode-list.twig",
			["recordings" => $recordings],
			TRUE
		);
	}
}