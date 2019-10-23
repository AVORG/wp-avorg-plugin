<?php

namespace Avorg;

use Exception;
use function defined;
use function get_option;

if (!defined('ABSPATH')) exit;

class AvorgApi
{
	private $getContext;

    /**
     * @param $email
     * @param $password
     * @return mixed
     * @throws Exception
     */
    public function logIn($email, $password)
    {
        return $this->postResponseNew("auth/login", [
            'email' => $email,
            'password' => $password
        ])->data;
    }

    /**
     * @param $id
     * @return bool
     * @throws Exception
     */
    public function getOneSeries($id)
	{
		if (!is_numeric($id)) return false;

		$response = $this->getResult("series/$id");

		return $response[0]->series;
	}

    /**
     * @param null $search
     * @param null $start
     * @return array
     * @throws Exception
     */
	public function getAllSeries($search = null, $start = null)
	{
		$endpoint = "series?search=$search&start=$start";

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
     * @param null $start
     * @return array
     * @throws Exception
     */
	public function getSponsors($search = null, $start = null)
	{
		$endpoint = "sponsors?search=$search&start=$start";

		return array_map(function($item) {
			return $item->sponsors;
		}, $this->getResult($endpoint));
	}

    /**
     * @param null $search
     * @param null $start
     * @return array
     * @throws Exception
     */
	public function getConferences($search = null, $start = null)
	{
		$endpoint = "conferences?search=$search&start=$start";

		return array_map(function($item) {
			return $item->conferences;
		}, $this->getResult($endpoint));
	}

    /**
     * @param null $search
     * @param null $start
     * @return array
     * @throws Exception
     */
	public function getStories($search = null, $start = null)
	{
		$endpoint = "audiobooks?story=1&search=$search&start=$start";

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
     * @param null $start
     * @return array
     * @throws Exception
     */
	public function getTopics($search = null, $start = null)
	{
		$endpoint = "topics?search=$search&start=$start";

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
     * @param null $start
     * @return array
     * @throws Exception
     */
	public function getBooks($search = null, $start = null)
	{
		$endpoint = "audiobooks?search=$search&start=$start";

		return array_map(function ($item) {
			return $item->audiobooks;
		}, $this->getResult($endpoint));
	}

    /**
     * @param null $search
     * @param null $start
     * @return array|object
     * @throws Exception
     */
	public function getPlaylists($search = null, $start = null)
	{
		return $this->getResult("playlist?search=$search&start=$start");
	}

	public function getPlaylistsByUser($userId, $sessionToken, $search = null, $start = null)
    {
        return $this->getResult("playlist?userId=$userId&sessionToken=$sessionToken&search=$search&start=$start");
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
        return $this->getResponseOld($endpoint)->result;
    }

    /**
     * @param $endpoint
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    private function postResponseNew($endpoint, $data = [])
    {
        $this->testGuard();

        $apiKey = get_option("avorgApiKey");
        $auth = "Authorization: Bearer $apiKey";
        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n$auth\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];
        $context = stream_context_create($options);
        $url = "https://api.audioverse.org/$endpoint";

        if ($response = file_get_contents(
            $url,
            false, $context
        )) {
            return json_decode($response);
        } else {
            throw new Exception("Failed to get response from url $endpoint");
        }
    }

	/**
	 * @param $endpoint
	 * @return object|array
	 * @throws Exception
	 */
	private function getResponseOld($endpoint)
	{
		$this->testGuard();

		if (!$this->getContext) $this->getContext = $this->createGetContext();

		if ($response = @file_get_contents(
			"https://api2.audioverse.org/$endpoint",
            false, $this->getContext)
        ) {
			return json_decode($response);
		} else {
			throw new Exception("Failed to get response from url $endpoint");
		}
	}

    /**
     * @throws Exception
     */
    private function testGuard()
    {
        if (defined('AVORG_TESTS_RUNNING') && AVORG_TESTS_RUNNING)
        {
            throw new Exception("Unmocked API method called");
        }
    }

	private function createGetContext()
	{
		$apiUser = get_option("avorgApiUser");
		$apiPass = get_option("avorgApiPass");
		$auth = "Authorization: Basic " . base64_encode("$apiUser:$apiPass");
		$header = "Content-Type: text/xml\r\n$auth\r\n";
		$opts = ['http' => ['header' => $header]];

		return stream_context_create($opts);
	}
}