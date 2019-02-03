<?php

namespace Avorg;

use Avorg\MediaFile\AudioFile;

if (!\defined('ABSPATH')) exit;

class Presentation
{
	/** @var LanguageFactory $languageFactory */
	private $languageFactory;

    private $apiPresentation;

    public function __construct($apiPresentation, LanguageFactory $languageFactory)
    {
        $this->apiPresentation = $apiPresentation;
        $this->languageFactory = $languageFactory;
    }

	public function toJson()
	{
		$data = array_merge((array) $this->apiPresentation, [
			"url" => $this->getUrl(),
			"audioFiles" => $this->convertMediaFilesToArrays($this->getAudioFiles()),
			"videoFiles" => $this->convertMediaFilesToArrays($this->getVideoFiles()),
			"logUrl" => $this->getLogUrl(),
			"datePublished" => $this->getDatePublished(),
			"presenters" => $this->getPresenters()
		]);

		return json_encode($data);
	}

    public function getAudioFiles()
    {
        $apiMediaFiles = (isset($this->apiPresentation->mediaFiles)) ? $this->apiPresentation->mediaFiles : [];

        return $this->wrapItems(
            "\\Avorg\\MediaFile\\AudioFile",
            $apiMediaFiles
        );
    }

    public function getDatePublished()
	{
		return $this->apiPresentation->publishDate;
	}

	public function getId()
	{

		return $this->apiPresentation->id;
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
		$language = $this->languageFactory->getLanguageByLangCode($this->apiPresentation->lang);

		if (!$language) return null;

		$fragments = [
			$language->getBaseRoute(),
			$language->translateUrlFragment("sermons"),
			$language->translateUrlFragment("recordings"),
			$this->apiPresentation->id,
			$this->formatTitleForUrl($this->apiPresentation->title) . ".html"
		];

		return "/" . implode("/", $fragments);
    }

	/**
	 * @param $title
	 * @return string
	 */
	private function formatTitleForUrl($title)
	{
		$titleLowerCase = strtolower($title);
		$titleNoPunctuation = preg_replace("/[^\w ]/", "", $titleLowerCase);
		$titleHyphenated = str_replace(" ", "-", $titleNoPunctuation);

		return $titleHyphenated;
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

	/**
	 * @param $mediaFiles
	 * @return array
	 */
	private function convertMediaFilesToArrays($mediaFiles)
	{
		return array_map(function (MediaFile $mediaFile) {
			return [
				"streamUrl" => $mediaFile->getStreamUrl(),
				"type" => $mediaFile->getType()
			];
		}, $mediaFiles);
	}
}