<?php

namespace Avorg\DataObject;

use Avorg\DataObject;
use Avorg\MediaFile;
use function defined;

if (!defined('ABSPATH')) exit;

class Recording extends DataObject
{
	protected $detailClass = "Avorg\Page\Media";

	public function getDescription()
	{
		$presenterNames = array_map(function ($presenter) {
			return implode(" ", [
				$presenter["name"]["first"],
				$presenter["name"]["last"],
				$presenter["name"]["suffix"],
			]);
		}, $this->getPresenters());

		return trim($this->data->description . " Presenters: " . implode(", ", $presenterNames));
	}

	public function getImage()
	{
		if (!$this->data) return null;

		$presenters = $this->getPresenters();
		$recordingHasImage = property_exists($this->data, "photo86") && $this->data->photo86;
		$presenterHasImage = $presenters && array_key_exists("photo", $presenters[0]);

		if ($recordingHasImage) {
			return $this->data->photo86;
		} elseif ($presenterHasImage) {
			return $presenters[0]["photo"];
		} else {
			return AVORG_LOGO_URL;
		}
	}

	public function getAudioFiles()
	{
		$apiMediaFiles = (isset($this->data->mediaFiles)) ? $this->data->mediaFiles : [];

		return $this->wrapItems(
			"\\Avorg\\MediaFile\\AudioFile",
			$apiMediaFiles
		);
	}

	public function getVideoFiles()
	{
		$apiMediaFiles = (isset($this->data->videoFiles)) ? $this->data->videoFiles : [];
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
		return $this->data->publishDate;
	}

	public function getLogUrl()
	{
		$apiMediaFiles = (isset($this->data->videoFiles)) ? $this->data->videoFiles : [];

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
		$apiPresenters = (isset($this->data->presenters)) ? $this->data->presenters : [];

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
	 * @return array
	 */
	public function toArray()
	{
		return array_merge((array)$this->data, [
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

	protected function getSlug()
	{
		return $this->router->formatStringForUrl($this->data->title) . ".html";
	}
}