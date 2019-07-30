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

		$response = $this->getResponse("series/$id");

		return $response[0]->series;
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function getAllSeries()
	{
		$endpoint = "series";

		return array_map(function($item) {
			return $item->series;
		}, $this->getResponse($endpoint));
	}

	/**
	 * @param $id
	 * @return bool
	 * @throws Exception
	 */
	public function getSponsor($id)
	{
		if (!is_numeric($id)) return false;

		$response = $this->getResponse("sponsors/$id");

		return $response[0]->sponsors;
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function getSponsors()
	{
		$endpoint = "sponsors";

		return array_map(function($item) {
			return $item->sponsors;
		}, $this->getResponse($endpoint));
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function getConferences()
	{
		$endpoint = "conferences";

		return array_map(function($item) {
			return $item->conferences;
		}, $this->getResponse($endpoint));
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function getStories()
	{
		$endpoint = "audiobooks?story=1";

		return array_map(function($item) {
			return $item->audiobooks;
		}, $this->getResponse($endpoint));
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
		return (array) $this->getResponse(
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
		return (array) $this->getResponse("audiobibles/$bible_id");
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function getBibles()
	{
		return (array) $this->getResponse("audiobibles");
	}

	/**
	 * @param $id
	 * @return bool
	 * @throws Exception
	 */
	public function getTopic($id)
	{
		if (!is_numeric($id)) return false;

		$response = $this->getResponse("topics/$id");

		return $response[0]->topics;
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function getTopics()
	{
		$endpoint = "topics";

		return array_map(function ($item) {
			return $item->topics;
		}, $this->getResponse($endpoint));
	}

	/**
	 * @param $id
	 * @return mixed
	 * @throws Exception
	 */
	public function getBook($id)
	{
		if (!is_numeric($id)) return false;

		$response = $this->getResponse("audiobooks/$id");

		return $response[0]->audiobooks;
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function getBooks()
	{
		$endpoint = "audiobooks";

		return array_map(function ($item) {
			return $item->audiobooks;
		}, $this->getResponse($endpoint));
	}

	/**
	 * @return array|object
	 * @throws Exception
	 */
	public function getPlaylists()
	{
		return $this->getResponse("playlist");
	}

	/**
	 * @param $id
	 * @return array|bool|object
	 * @throws Exception
	 */
	public function getPlaylist($id)
	{
		if (!is_numeric($id)) return false;

		return $this->getResponse("playlist/$id");
	}

	/**
	 * @param $id
	 * @return bool
	 * @throws Exception
	 */
	public function getPresenter($id)
	{
		if (!is_numeric($id)) return false;

		$response = $this->getResponse("presenters/{$id}");

		return $response[0]->presenters;
	}

	/**
	 * @param null $search
	 * @return mixed
	 * @throws Exception
	 */
	public function getPresenters($search = null)
	{
		$endpoint = "presenters?search=$search&all=true";

		return array_map(function ($item) {
			return $item->presenters;
		}, $this->getResponse($endpoint));
	}

	/**
	 * @param $id
	 * @return bool
	 * @throws Exception
	 */
	public function getRecording($id)
	{
		if (!is_numeric($id)) return false;

		$response = $this->getResponse("recordings/{$id}");

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
		}, $this->getResponse($endpoint));
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
			"https://api2.audioverse.org/$endpoint", false, $this->context)) {
			return json_decode($response)->result;
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