<?php


namespace Avorg\Page\Bible;

use Avorg\DataObjectRepository\BibleRepository;
use Avorg\Page;
use Avorg\Renderer;
use Avorg\WordPress;
use function defined;
use Exception;

if (!defined('ABSPATH')) exit;

class Detail extends Page
{
	/** @var BibleRepository $bibleRepository */
	private $bibleRepository;

	protected $defaultPageTitle = "Bible";
	protected $defaultPageContent = "Bible";
	protected $twigTemplate = "page-bible.twig";

	public function __construct(
		BibleRepository $bibleRepository,
		Renderer $renderer,
		WordPress $wp
	)
	{
		parent::__construct($renderer, $wp);

		$this->bibleRepository = $bibleRepository;
	}

	protected function getData()
	{
		// TODO: Implement getData() method.
	}

	/**
	 * @return mixed
	 * @throws Exception
	 */
	protected function getTitle()
	{
		$bible = $this->bibleRepository->getBible($this->getEntityId());

		return $bible ? $bible->name : null;
	}

	protected function getEntityId()
	{
		return $this->wp->get_query_var("version") .
			$this->wp->get_query_var("drama");
	}
}