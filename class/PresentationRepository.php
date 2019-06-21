<?php

namespace Avorg;

use Exception;
use natlib\Factory;

if (!\defined('ABSPATH')) exit;

class PresentationRepository
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
    public function getPresentations($list = "")
    {
        $apiResponse = $this->api->getPresentations($list);

		return $this->buildPresentations($apiResponse);
    }

    public function getPresenterPresentations($presenterId)
	{
		$apiResponse = $this->api->getPresenterPresentations($presenterId);

		return $this->buildPresentations($apiResponse);
	}

	/**
	 * @param $presentationId
	 * @return Presentation|null
	 * @throws Exception
	 */
	public function getPresentation($presentationId)
    {
        $apiResponse = $this->api->getPresentation($presentationId);

        return $apiResponse ? $this->buildPresentation($apiResponse) : null;
    }

	/**
	 * @param $topicId
	 * @return array
	 * @throws Exception
	 */
	public function getTopicPresentations($topicId)
	{
		$apiResponse = $this->api->getTopicPresentations($topicId);

		return $this->buildPresentations($apiResponse);
	}

	public function getPlaylistPresentations($playlistId)
	{
		$apiResponse = $this->api->getPlaylist($playlistId);

		return array_map(function ($recording) {
			return $this->factory->make("Avorg\\Presentation")->setPresentation($recording);
		}, $apiResponse->recordings ?: []);
	}

	/**
	 * @param $apiResponse
	 * @return array
	 */
	private function buildPresentations($apiResponse)
	{
		return array_map([$this, "buildPresentation"], $apiResponse ?: []);
	}

	/**
	 * @param $apiRecording
	 * @return Presentation
	 * @throws \ReflectionException
	 */
	private function buildPresentation($apiRecording)
	{
		$unwrappedRecording = $apiRecording->recordings;

		return $this->factory->make("Avorg\\Presentation")->setPresentation($unwrappedRecording);
	}

}