<?php

namespace Avorg;

use Exception;
use function get_option;

if (!\defined('ABSPATH')) exit;

class AvorgApi
{
	private $apiUser;
	private $apiPass;
	private $context;
	
	public function __construct()
	{
		$this->apiUser = get_option("avorgApiUser");
		$this->apiPass = get_option("avorgApiPass");
	}

	public function getBibles()
	{

	}

	public function getTopic($id)
	{
		// todo: Implement once Henry adds a topics/{ id } route
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function getTopics()
	{
		$response = $this->getResponse("topics");

		return array_map(function($item) {
			return $item->topics;
		}, $response->result);
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

		return $response->result[0]->audiobooks;
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function getBooks()
	{
		$response = $this->getResponse("audiobooks");

		return array_map(function($item) {
			return $item->audiobooks;
		}, $response->result);
	}

	public function getPlaylist($id)
	{
		if (!is_numeric($id)) return false;

		$response = $this->getResponse("playlist/$id");

		return $response->result;
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

		return $response->result[0]->presenters;
	}

	/**
	 * @param null $search
	 * @return mixed
	 * @throws Exception
	 */
	public function getPresenters($search = null)
	{
		$response = $this->getResponse("presenters?search=$search");

		return array_map(function($item) {
			return $item->presenters;
		}, $response->result);
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

		return $response->result[0]->recordings;
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
		$response = $this->getResponse($endpoint);

		if (!isset($response->result)) return null;

		return array_map(function($entry) {
			return $entry->recordings;
		}, $response->result);
	}
	
	/**
	 * @param $endpoint
	 * @return object
	 * @throws Exception
	 */
	private function getResponse($endpoint)
	{
		if (!$this->context) $this->context = $this->createContext();

		if ($result = @file_get_contents(
			"https://api2.audioverse.org/$endpoint", false, $this->context)) {
			return json_decode($result);
		} else {
			throw new Exception("Failed to get response from url $endpoint");
		}
	}
	
	private function createContext()
	{
		$opts = ['http' =>
			[
				'header' => "Content-Type: text/xml\r\n" . "Authorization: Basic " .
					base64_encode("$this->apiUser:$this->apiPass") . "\r\n"
			]
		];
		
		return stream_context_create($opts);
	}
}