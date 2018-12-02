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
        $apiResponse = $this->api->getPresentations($list) ?: [];

        return array_map([$this, "buildPresentation"], $apiResponse);
    }

    public function getPresentation($presentationId)
    {
        $apiResponse = $this->api->getPresentation($presentationId);

        return $apiResponse ? $this->buildPresentation($apiResponse) : null;
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
}