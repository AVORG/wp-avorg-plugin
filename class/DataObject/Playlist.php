<?php

namespace Avorg\DataObject;


use Avorg\DataObject;
use Avorg\DataObjectRepository\PresentationRepository;
use Avorg\Renderer;
use Avorg\Router;
use Exception;

if (!defined('ABSPATH')) exit;

class Playlist extends DataObject
{
	protected $detailClass = "Avorg\\Page\\Playlist\\Detail";

	/** @var PresentationRepository $presentationRepository */
	private $presentationRepository;

	public function __construct(
	    PresentationRepository $presentationRepository,
        Renderer $renderer,
        Router $router
    )
	{
		parent::__construct($renderer, $router);

		$this->presentationRepository = $presentationRepository;
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function getPresentations()
	{
		return $this->presentationRepository->getPlaylistPresentations($this->id);
	}
}