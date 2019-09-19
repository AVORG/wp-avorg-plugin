<?php

namespace Avorg\DataObject;

use Avorg\DataObject;
use Avorg\DataObjectRepository\PresentationRepository;
use Avorg\Router;
use function defined;

if (!defined('ABSPATH')) exit;

class Presenter extends DataObject
{
	/** @var PresentationRepository $presentationRepository */
	private $presentationRepository;

	protected $detailClass = "Avorg\Page\Presenter\Detail";

	public function __construct(
		PresentationRepository $recordingRepository,
		Router $router
	)
	{
		parent::__construct($router);

		$this->presentationRepository = $recordingRepository;
		$this->router = $router;
	}

    public function getRecordings()
	{
		return $this->presentationRepository->getPresenterPresentations($this->getId());
	}

	public function getName()
	{
		return trim(implode(" ", [
			$this->__get("givenName"),
			$this->__get("surname"),
			$this->__get("suffix"),
		]));
	}

	public function getNameReversed()
	{
		$first = $this->__get("givenName");
		$last = $this->__get("surname");
		$suffix = $this->__get("suffix");

		return $suffix ? "$last $suffix, $first" : "$last, $first";
	}

	protected function getSlug()
	{
		return $this->router->formatStringForUrl($this->getName()) . ".html";
	}
}
