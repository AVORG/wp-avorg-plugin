<?php

namespace Avorg;

use Exception;
use function get_option;

if (!\defined('ABSPATH')) exit;

class AvorgApi
{
	private $context;

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
	 * @param $id
	 * @return array
	 * @throws Exception
	 */
	public function getBibleBooks($id)
	{
		return (array) $this->getResponse("audiobibles/$id");
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
	 * @return null
	 * @throws Exception
	 */
	public function getRecordings($list = "")
	{
		$endpoint = trim("recordings/$list", "/");

		return $this->getRecordingsResponse($endpoint);
	}

	/**
	 * @param $topicId
	 * @return null
	 * @throws Exception
	 */
	public function getTopicRecordings($topicId)
	{
		return $this->getRecordingsResponse("recordings/topic/$topicId");
	}

	/**
	 * @param $presenterId
	 * @return bool|null
	 * @throws Exception
	 */
	public function getPresenterRecordings($presenterId)
	{
		if (!is_numeric($presenterId)) return false;

		return $this->getRecordingsResponse("recordings/presenter/$presenterId");
	}

	/**
	 * @param $bookId
	 * @return bool|null
	 * @throws Exception
	 */
	public function getBookRecordings($bookId)
	{
		if (!is_numeric($bookId)) return false;

		return $this->getRecordingsResponse("recordings/audiobook/$bookId");
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