<?php

namespace Avorg;

use function defined;
use Exception;
use natlib\Factory;
use ReflectionException;

if (!defined('ABSPATH')) exit;

class RecordingRepository
{
    /** @var AvorgApi $api */
    private $api;

    /** @var Factory $factory */
    private $factory;

    /** @var LanguageFactory $languageFactory */
    private $languageFactory;

    /** @var Router $router */
    private $router;

    public function __construct(
    	AvorgApi $api,
		Factory $factory,
		LanguageFactory $languageFactory,
		Router $router
	)
    {
        $this->api = $api;
        $this->factory = $factory;
        $this->languageFactory = $languageFactory;
        $this->router = $router;
    }

    /**
     * @param string $list
     * @return array
     * @throws Exception
     */
    public function getRecordings($list = "")
    {
        $apiResponse = $this->api->getRecordings($list);

		return $this->buildRecordings($apiResponse);
    }

    public function getPresenterRecordings($presenterId)
	{
		$apiResponse = $this->api->getPresenterRecordings($presenterId);

		return $this->buildRecordings($apiResponse);
	}

	/**
	 * @param $id
	 * @return Recording|null
	 * @throws Exception
	 */
	public function getRecording($id)
    {
        $apiResponse = $this->api->getRecording($id);

        return $apiResponse ? $this->buildRecording($apiResponse) : null;
    }

	/**
	 * @param $topicId
	 * @return array
	 * @throws Exception
	 */
	public function getTopicRecordings($topicId)
	{
		$apiResponse = $this->api->getTopicRecordings($topicId);

		return $this->buildRecordings($apiResponse);
	}

	public function getPlaylistRecordings($playlistId)
	{
		$apiResponse = $this->api->getPlaylist($playlistId);

		return array_map(function ($recording) {
			return $this->factory->make("Avorg\\DataObject\\Recording")->setData($recording);
		}, $apiResponse->recordings ?: []);
	}

	/**
	 * @param $bookId
	 * @return array
	 * @throws Exception
	 */
	public function getBookRecordings($bookId)
	{
		$apiResponse = $this->api->getBookRecordings($bookId);

		return $this->buildRecordings($apiResponse);
	}

	/**
	 * @param $apiResponse
	 * @return array
	 */
	private function buildRecordings($apiResponse)
	{
		return array_map([$this, "buildRecording"], $apiResponse ?: []);
	}

	/**
	 * @param $apiRecording
	 * @return Recording
	 * @throws ReflectionException
	 */
	private function buildRecording($apiRecording)
	{
		return $this->factory->make("Avorg\\DataObject\\Recording")->setData($apiRecording);
	}

}