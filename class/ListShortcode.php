<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class ListShortcode
{
	/** @var AvorgApi $api */
	private $api;
	
	/** @var Twig $twig */
	private $twig;
	
	/** @var WordPress $wp */
	private $wp;
	
	public function __construct(AvorgApi $api, Twig $twig, WordPress $wp)
	{
		$this->api = $api;
		$this->twig = $twig;
		$this->wp = $wp;
	}
	
	public function addShortcode()
	{
		$this->wp->call("add_shortcode", "avorg-list", [$this, "renderShortcode"]);
	}
	
	public function renderShortcode()
	{
		$result = $this->api->getPresentations() ?: [];
		
		$recordings = array_reduce( $result, function($carry, $item) {
			$carry[] = $item->recordings;
			return $carry;
		}, [] );
		
		return $this->twig->render(
			"shortcode-list.twig",
			["recordings" => $recordings],
			TRUE
		);
	}
}