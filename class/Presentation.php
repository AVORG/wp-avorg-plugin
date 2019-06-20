<?php

namespace Avorg;

use function defined;

if (!defined('ABSPATH')) exit;

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
		$data = array_merge((array)$this->apiPresentation, [
			"id" => $this->getId(),
			"url" => $this->getUrl(),
			"audioFiles" => $this->convertMediaFilesToArrays($this->getAudioFiles()),
			"videoFiles" => $this->convertMediaFilesToArrays($this->getVideoFiles()),
			"logUrl" => $this->getLogUrl(),
			"datePublished" => $this->getDatePublished(),
			"presenters" => $this->getPresenters(),
			"image" => $this->getImage(),
			"description" => $this->getDescription()
		]);

		return json_encode($data);
	}

	public function getDescription()
	{
		$presenterNames = array_map(function ($presenter) {
			return implode(" ", [
				$presenter["name"]["first"],
				$presenter["name"]["last"],
				$presenter["name"]["suffix"],
			]);
		}, $this->getPresenters());

		return trim($this->apiPresentation->description . " Presenters: " . implode(", ", $presenterNames));
	}

	public function getImage()
	{
		if (!$this->apiPresentation) return null;

		$presenters = $this->getPresenters();
		$recordingHasImage = property_exists($this->apiPresentation, "photo86") && $this->apiPresentation->photo86;
		$presenterHasImage = $presenters && array_key_exists("photo", $presenters[0]);

		if ($recordingHasImage) {
			return $this->apiPresentation->photo86;
		} elseif ($presenterHasImage) {
			return $presenters[0]["photo"];
		} else {
			return AVORG_LOGO_URL;
		}
	}

	public function getAudioFiles()
	{
		$apiMediaFiles = (isset($this->apiPresentation->mediaFiles)) ? $this->apiPresentation->mediaFiles : [];

		return $this->wrapItems(
			"\\Avorg\\MediaFile\\AudioFile",
			$apiMediaFiles
		);
	}

	public function getVideoFiles()
	{
		$apiMediaFiles = (isset($this->apiPresentation->videoFiles)) ? $this->apiPresentation->videoFiles : [];
		$filteredFiles = array_filter($apiMediaFiles, function ($file) {
			return $file->container === "m3u8_ios";
		});

		return $this->wrapItems(
			"\\Avorg\\MediaFile\\VideoFile",
			$filteredFiles
		);
	}

	public function getDatePublished()
	{
		return $this->apiPresentation->publishDate;
	}

	public function getId()
	{

		return intval($this->apiPresentation->id);
	}

	public function getLogUrl()
	{
		$apiMediaFiles = (isset($this->apiPresentation->videoFiles)) ? $this->apiPresentation->videoFiles : [];

		return array_reduce($apiMediaFiles, function ($carry, $file) {
			if (!isset($file->logURL)) return $carry;

			return $file->logURL ?: $carry;
		});
	}

	public function getPresentersString()
	{
		$presenters = $this->getPresenters();
		$presenterFragments = array_map(function ($presenter) {
			$pieces = array_filter($presenter["name"]);
			return implode(" ", $pieces);
		}, $presenters);

		return implode(", ", $presenterFragments);
	}

	public function getPresenters()
	{
		$apiPresenters = (isset($this->apiPresentation->presenters)) ? $this->apiPresentation->presenters : [];

		return array_map(function ($presenter) {
			return [
				"photo" => $presenter->photo256,
				"name" => [
					"first" => $presenter->givenName,
					"last" => $presenter->surname,
					"suffix" => $presenter->suffix
				]
			];
		}, $apiPresenters);
	}

	public function getTitle()
	{
		return $this->apiPresentation->title;
	}

	public function getUrl()
	{
		$language = $this->languageFactory->getLanguageByLangCode($this->apiPresentation->lang);

		if (!$language) return null;

		$presentationId = $this->apiPresentation->id;
		$tail = $language->formatStringForUrl($this->apiPresentation->title) . ".html";
		$path = "sermons/recordings/$presentationId/$tail";

		return $language->getTranslatedUrl($path);
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