<?php

namespace Avorg\DataObjectRepository;

use Avorg\DataObject;
use Avorg\DataObjectRepository;
use function defined;
use Exception;
use natlib\Stub;

if (!defined('ABSPATH')) exit;

class BibleChapterRepository extends DataObjectRepository
{
	protected $dataObjectClass = "Avorg\\DataObject\\Recording\\BibleChapter";

	/**
	 * @param $bibleId
	 * @param $bookId
	 * @param $testamentId
	 * @return array
	 * @throws Exception
	 */
	public function getChapters($bibleId, $bookId, $testamentId)
	{
		$rawObjects = $this->api->getBibleChapters($bibleId, $bookId, $testamentId);

		return $this->makeDataObjects($rawObjects);
	}

    public function getDataObjects($search = null, $start = null)
    {
        // TODO: Implement getDataObjects() method.
    }
}