<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class PresentationRepository
{
    /** @var AvorgApi $api */
    private $api;

    /** @var Router $router */
    private $router;

    public function __construct(AvorgApi $api, Router $router)
    {
        $this->api = $api;
        $this->router = $router;
    }

    /**
     * @param string $list
     * @return array
     * @throws \Exception
     */
    public function getPresentations($list = "")
    {
        $apiResponse = $this->api->getPresentations($list);

		return $this->buildPresentations($apiResponse);
    }

	/**
	 * @param $presentationId
	 * @return Presentation|null
	 * @throws \Exception
	 */
	public function getPresentation($presentationId)
    {
        $apiResponse = $this->api->getPresentation($presentationId);

        return $apiResponse ? $this->buildPresentation($apiResponse) : null;
    }

	/**
	 * @param $topicId
	 * @return array
	 * @throws \Exception
	 */
	public function getTopicPresentations($topicId)
	{
		$apiResponse = $this->api->getTopicPresentations($topicId);

		return $this->buildPresentations($apiResponse);
	}

    /**
     * @param $apiRecording
     * @return Presentation
     */
    private function buildPresentation($apiRecording)
    {
        $unwrappedRecording = $apiRecording->recordings;
        $url = $this->router->getUrlForApiRecording($unwrappedRecording);

        return new Presentation($unwrappedRecording, $url);
    }

	/**
	 * @param $apiResponse
	 * @return array
	 */
	private function buildPresentations($apiResponse)
	{
		return array_map([$this, "buildPresentation"], $apiResponse ?: []);
	}
}