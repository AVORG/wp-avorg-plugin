<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class Presentation
{
    private $apiPresentation;
    private $url;

    public function __construct($apiPresentation, $url = null)
    {
        $this->apiPresentation = $apiPresentation;
        $this->url = $url;
    }

    public function getAudioFiles()
    {
        $apiMediaFiles = (isset($this->apiPresentation->mediaFiles)) ? $this->apiPresentation->mediaFiles : [];

        return $this->wrapItems(
            "\\Avorg\\MediaFile\\AudioFile",
            $apiMediaFiles
        );
    }

    public function getLogUrl()
    {
        $apiMediaFiles = (isset($this->apiPresentation->videoFiles)) ? $this->apiPresentation->videoFiles : [];

        return array_reduce($apiMediaFiles, function($carry, $file) {
            if (!isset($file->logURL)) return $carry;

            return $file->logURL ?: $carry;
        });
    }

    public function getPresenters()
    {
        $apiPresenters = (isset($this->apiPresentation->presenters)) ? $this->apiPresentation->presenters : [];

        return array_map(function($presenter) {
            return [
                "photo" => $presenter->photo86,
                "name" => [
                    "first" => $presenter->givenName,
                    "last" => $presenter->surname,
                    "suffix" => $presenter->suffix
                ]
            ];
        },  $apiPresenters);
    }

    public function getTitle()
    {
        return $this->apiPresentation->title;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getVideoFiles()
    {
        $apiMediaFiles = (isset($this->apiPresentation->videoFiles)) ? $this->apiPresentation->videoFiles : [];
        $filteredFiles = array_filter($apiMediaFiles, function($file) {
            return $file->container === "m3u8_ios";
        });

        return $this->wrapItems(
            "\\Avorg\\MediaFile\\VideoFile",
            $filteredFiles
        );
    }

    /**
     * @param $className
     * @param $items
     * @return array
     */
    private function wrapItems($className, $items)
    {
        return array_map(function ($item) use ($className) {
            return new $className($item);
        }, $items);
    }
}