<?php

namespace Avorg\DataObject;

use Avorg\DataObject;
use Avorg\DataObjectRepository\BibleBookRepository;
use Avorg\Router;
use function defined;
use Exception;

if (!defined('ABSPATH')) exit;

class Bible extends DataObject
{
	/** @var BibleBookRepository $bibleBookRepository */
	private $bibleBookRepository;

	protected $detailClass = "Avorg\Page\Bible\Detail";

	public function __construct(BibleBookRepository $bibleBookRepository, Router $router)
	{
		parent::__construct($router);

		$this->bibleBookRepository = $bibleBookRepository;
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function getBooks()
	{
		$fullId = $this->dam_id . $this->drama;

		return $this->bibleBookRepository->getBibleBooks($fullId);
	}

	/**
	 * @return string
	 * @throws Exception
	 */
	public function getUrl()
	{
		return $this->router->buildUrl($this->detailClass, [
			"version" => $this->dam_id,
			"drama" => $this->drama
		]);
	}
}