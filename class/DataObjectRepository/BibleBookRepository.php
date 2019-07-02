<?php

namespace Avorg\DataObjectRepository;


use Avorg\DataObjectRepository;
use Exception;

if (!defined('ABSPATH')) exit;

class BibleBookRepository extends DataObjectRepository
{
	protected $dataObjectClass = "Avorg\\DataObject\\BibleBook";

	/**
	 * @param $id
	 * @return array
	 * @throws Exception
	 */
	public function getBibleBooks($id)
	{
		$rawBibleBooks = $this->api->getBibleBooks($id);

		return $this->makeDataObjects($rawBibleBooks);
	}
}