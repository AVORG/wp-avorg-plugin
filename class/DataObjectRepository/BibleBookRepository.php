<?php

namespace Avorg\DataObjectRepository;


use Avorg\DataObject;
use Avorg\DataObjectRepository;
use Exception;
use ReflectionException;

if (!defined('ABSPATH')) exit;

class BibleBookRepository extends DataObjectRepository
{
	protected $dataObjectClass = "Avorg\\DataObject\\BibleBook";

	/**
	 * @param $bibleId
	 * @return array
	 * @throws Exception
	 */
	public function getBibleBooks($bibleId)
	{
		$rawBibleBooks = $this->api->getBibleBooks($bibleId);

		return $this->makeDataObjects($rawBibleBooks);
	}

	/**
	 * @param $bibleId
	 * @param $bookId
	 * @return DataObject
	 * @throws ReflectionException
	 * @throws Exception
	 */
	public function getBibleBook($bibleId, $bookId)
	{
		$rawBibleBooks = $this->api->getBibleBooks($bibleId);
		$rawBibleBook = $rawBibleBooks[$bookId];

		return $this->makeDataObject($rawBibleBook);
	}

    public function getDataObjects()
    {
        // TODO: Implement getDataObjects() method.
    }
}