<?php

namespace Avorg\Page;

use function Avorg\avorgLog;
use Avorg\Page;
use Avorg\Presentation;
use Avorg\PresentationRepository;
use Avorg\Renderer;
use Avorg\RouteFactory;
use Avorg\ScriptFactory;
use Avorg\WordPress;

if (!\defined('ABSPATH')) exit;

class Playlist extends Page
{
	/** @var PresentationRepository $presentationRepository */
	private $presentationRepository;

	/** @var ScriptFactory $scriptFactory */
	private $scriptFactory;

	protected $defaultPageTitle = "Playlist Detail";
	protected $defaultPageContent = "Playlist Detail";
	protected $twigTemplate = "page-playlist.twig";

	public function __construct(
		PresentationRepository $presenterRepository,
		Renderer $renderer,
		ScriptFactory $scriptFactory,
		WordPress $wp
	)
	{
		parent::__construct($renderer, $wp);

		$this->presentationRepository = $presenterRepository;
		$this->scriptFactory = $scriptFactory;
	}

	public function throw404($query)
	{
		// TODO: Implement throw404() method.
	}

	protected function getScripts()
	{
		return [
			$this->scriptFactory->getScript("script/playlist.js")->setData($this->getStaticData())
		];
	}

	private function getStaticData()
	{
		$staticPresentations = array_map(function(Presentation $presentation) {
			return json_decode($presentation->toJson());
		}, $this->getPresentations());

		return [
			"recordings" => $staticPresentations
		];
	}

	protected function getData()
	{
		return [
			"recordings" => $this->getPresentations()
		];
	}

	/**
	 * @return array
	 */
	private function getPresentations()
	{
		$presentations = $this->presentationRepository->getPlaylistPresentations($this->getEntityId());

		$array_reduce = array_reduce($presentations, function ($carry, Presentation $presentation) {
			$carry[$presentation->getId()] = $presentation;

			return $carry;
		}, []);

		return $array_reduce;
	}

	protected function getEntityTitle()
	{
		// TODO: Implement getEntityTitle() method.
	}
}