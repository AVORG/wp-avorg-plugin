<?php


namespace Avorg\Page\Bible;

use Avorg\BibleRepository;
use Avorg\Page;
use Avorg\Renderer;
use Avorg\WordPress;
use function defined;

if (!defined('ABSPATH')) exit;

class Listing extends Page
{
	/** @var BibleRepository $bibleRepository */
	private $bibleRepository;

	protected $defaultPageTitle = "Bibles";
	protected $defaultPageContent = "Bibles";
	protected $twigTemplate = "page-bibles.twig";

	public function __construct(BibleRepository $bibleRepository, Renderer $renderer, WordPress $wp)
	{
		parent::__construct($renderer, $wp);

		$this->bibleRepository = $bibleRepository;
	}

	public function throw404($query)
	{
		// TODO: Implement throw404() method.
	}

	protected function getData()
	{
		return [
			"books" => $this->bibleRepository->getBibles()
		];
	}

	protected function getEntityTitle()
	{
		// TODO: Implement getEntityTitle() method.
	}
}