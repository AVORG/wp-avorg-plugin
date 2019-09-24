<?php

namespace Avorg;

use Exception;
use function defined;
use function get_option;

if (!defined('ABSPATH')) exit;

class AvorgApi
{
	private $context;

	public function getOneSeries($id)
	{
		if (!is_numeric($id)) return false;

		$response = $this->getResult("series/$id");

		return $response[0]->series;
	}

    /**
     * @param null $search
     * @return array
     * @throws Exception
     */
	public function getAllSeries($search = null)
	{
		$endpoint = "series";

		return array_map(function($item) {
			return $item->series;
		}, $this->getResult($endpoint));
	}

	/**
	 * @param $id
	 * @return bool
	 * @throws Exception
	 */
	public function getSponsor($id)
	{
		if (!is_numeric($id)) return false;

		$response = $this->getResult("sponsors/$id");

		return $response[0]->sponsors;
	}

    /**
     * @param null $search
     * @return array
     * @throws Exception
     */
	public function getSponsors($search = null)
	{
		$endpoint = "sponsors";

		return array_map(function($item) {
			return $item->sponsors;
		}, $this->getResult($endpoint));
	}

    /**
     * @param null $search
     * @return array
     * @throws Exception
     */
	public function getConferences($search = null)
	{
		$endpoint = "conferences";

		return array_map(function($item) {
			return $item->conferences;
		}, $this->getResult($endpoint));
	}

    /**
     * @param null $search
     * @return array
     * @throws Exception
     */
	public function getStories($search = null)
	{
		$endpoint = "audiobooks?story=1";

		return array_map(function($item) {
			return $item->audiobooks;
		}, $this->getResult($endpoint));
	}

	/**
	 * @param $bibleId
	 * @param $bookId
	 * @param $testamentId
	 * @return array
	 * @throws Exception
	 */
	public function getBibleChapters($bibleId, $bookId, $testamentId)
	{
		return (array) $this->getResult(
			"audiobibles/books/$bookId?volume=$bibleId&testament=$testamentId"
		);
	}

	/**
	 * @param $bible_id
	 * @return array
	 * @throws Exception
	 */
	public function getBibleBooks($bible_id)
	{
		return (array) $this->getResult("audiobibles/$bible_id");
	}

    /**
     * @param null $search
     * @return array
     * @throws Exception
     */
	public function getBibles($search = null)
	{
		return (array) $this->getResult("audiobibles");
	}

	/**
	 * @param $id
	 * @return bool
	 * @throws Exception
	 */
	public function getTopic($id)
	{
		if (!is_numeric($id)) return false;

		$response = $this->getResult("topics/$id");

		return $response[0]->topics;
	}

    /**
     * @param null $search
     * @return array
     * @throws Exception
     */
	public function getTopics($search = null)
	{
		$endpoint = "topics";

		return array_map(function ($item) {
			return $item->topics;
		}, $this->getResult($endpoint));
	}

	/**
	 * @param $id
	 * @return mixed
	 * @throws Exception
	 */
	public function getBook($id)
	{
		if (!is_numeric($id)) return false;

		$response = $this->getResult("audiobooks/$id");

		return $response[0]->audiobooks;
	}

    /**
     * @param null $search
     * @return array
     */
	public function getBooks($search = null)
	{
		$endpoint = "audiobooks";

		return array_map(function ($item) {
			return $item->audiobooks;
		}, $this->getResult($endpoint));
	}

	/**
	 * @return array|object
	 * @throws Exception
	 */
	public function getPlaylists()
	{
		return $this->getResult("playlist");
	}

	/**
	 * @param $id
	 * @return array|bool|object
	 * @throws Exception
	 */
	public function getPlaylist($id)
	{
		if (!is_numeric($id)) return false;

		return $this->getResult("playlist/$id");
	}

	/**
	 * @param $id
	 * @return bool
	 * @throws Exception
	 */
	public function getPresenter($id)
	{
		if (!is_numeric($id)) return false;

		$response = $this->getResult("presenters/{$id}");

		return $response[0]->presenters;
	}

    /**
     * @param null $search
     * @param null $start
     * @return mixed
     * @throws Exception
     */
	public function getPresenters($search = null, $start = null)
	{
		$endpoint = "presenters?search=$search&start=$start";

		return array_map(function ($item) {
			return $item->presenters;
		}, $this->getResult($endpoint));
	}

	/**
	 * @param $id
	 * @return bool
	 * @throws Exception
	 */
	public function getRecording($id)
	{
		if (!is_numeric($id)) return false;

		$response = $this->getResult("recordings/{$id}");

		return $response[0]->recordings;
	}

	/**
	 * @param string $list
	 * @return array
	 * @throws Exception
	 */
	public function getRecordings($list = "")
	{
		$endpoint = trim("recordings/$list", "/");

		return $this->getRecordingsResponse($endpoint);
	}

	/**
	 * @param $topicId
	 * @return array
	 * @throws Exception
	 */
	public function getTopicRecordings($topicId)
	{
		return $this->getRecordingsResponse("recordings/topic/$topicId");
	}

	/**
	 * @param $presenterId
	 * @return bool|array
	 * @throws Exception
	 */
	public function getPresenterRecordings($presenterId)
	{
		if (!is_numeric($presenterId)) return false;

		return $this->getRecordingsResponse("recordings/presenter/$presenterId");
	}

	/**
	 * @param $sponsorId
	 * @return bool|null
	 * @throws Exception
	 */
	public function getSponsorRecordings($sponsorId)
	{
		if (!is_numeric($sponsorId)) return false;

		return $this->getRecordingsResponse("recordings/sponsor/$sponsorId");
	}

	/**
	 * @param $conferenceId
	 * @return bool|array
	 * @throws Exception
	 */
	public function getConferenceRecordings($conferenceId)
	{
		if (!is_numeric($conferenceId)) return false;

		return $this->getRecordingsResponse("recordings/conference/$conferenceId");
	}

	/**
	 * @param $bookId
	 * @return bool|array
	 * @throws Exception
	 */
	public function getBookRecordings($bookId)
	{
		if (!is_numeric($bookId)) return false;

		return $this->getRecordingsResponse("recordings/audiobook/$bookId");
	}

	/**
	 * @param $seriesId
	 * @return bool|array
	 * @throws Exception
	 */
	public function getSeriesRecordings($seriesId)
	{
		if (!is_numeric($seriesId)) return false;

		return $this->getRecordingsResponse("recordings/series/$seriesId");
	}

	/**
	 * @param $endpoint
	 * @return null
	 * @throws Exception
	 */
	private function getRecordingsResponse($endpoint)
	{
		return array_map(function ($entry) {
			return $entry->recordings;
		}, $this->getResult($endpoint));
	}

    /**
     * @param $endpoint
     * @return mixed
     * @throws Exception
     */
    public function getResult($endpoint)
    {
        return $this->getResponse($endpoint)->result;
    }

	/**
	 * @param $endpoint
	 * @return object|array
	 * @throws Exception
	 */
	private function getResponse($endpoint)
	{
		if (defined('AVORG_TESTS_RUNNING') && AVORG_TESTS_RUNNING)
		{
			throw new Exception("Unmocked API method called");
		}

		if (!$this->context) $this->context = $this->createContext();

		if ($response = @file_get_contents(
			"https://api2.audioverse.org/$endpoint",
            false, $this->context)
        ) {
			return json_decode($response);
		} else {
			throw new Exception("Failed to get response from url $endpoint");
		}
	}

	private function createContext()
	{
		$apiUser = get_option("avorgApiUser");
		$apiPass = get_option("avorgApiPass");
		$auth = "Authorization: Basic " . base64_encode("$apiUser:$apiPass");
		$header = "Content-Type: text/xml\r\n$auth\r\n";
		$opts = ['http' => ['header' => $header]];

		return stream_context_create($opts);
	}
}