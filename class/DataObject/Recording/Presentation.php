<?php

namespace Avorg\DataObject\Recording;

use Avorg\DataObject;
use Avorg\MediaFile;
use function defined;

if (!defined('ABSPATH')) exit;

class Presentation extends DataObject\Recording
{
	protected $detailClass = "Avorg\Page\Presentation\Detail";

	public function toArray()
	{
		return array_merge(parent::toArray(), [
			"logUrl" => $this->getLogUrl(),
			"datePublished" => $this->getDatePublished(),
			"presenters" => $this->getPresenters(),
			"presentersString" => $this->getPresentersString(),
			"image" => $this->getImage(),
			"description" => $this->getDescription(),
			"audioFiles" => $this->convertMediaFilesToArrays($this->getAudioFiles()),
			"videoFiles" => $this->convertMediaFilesToArrays($this->getVideoFiles()),
		]);
	}

	public function getLogUrl()
	{
		$apiMediaFiles = (isset($this->data->videoFiles)) ? $this->data->videoFiles : [];

		return array_reduce($apiMediaFiles, function ($carry, $file) {
			if (!isset($file->logURL)) return $carry;

			return $file->logURL ?: $carry;
		});
	}

	public function getPresenters()
	{
		$apiPresenters = (isset($this->data->presenters)) ? $this->data->presenters : [];

		return array_map(function($presenter) {
			return array_merge((array)$presenter, [
                "photo" => property_exists($presenter, 'photo256') ? $presenter->photo256 : null,
                "name" => [
                    "first" => property_exists($presenter, 'givenName') ? $presenter->givenName : null,
                    "last" => property_exists($presenter, 'surname') ? $presenter->surname : null,
                    "suffix" => property_exists($presenter, 'suffix') ? $presenter->suffix : null
                ]
            ]);
		}, $apiPresenters);
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

	public function getDatePublished()
	{
		return $this->publishDate;
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

		$rawDescription = property_exists($this->data, 'description') ? $this->data->description : null;

		return trim( $rawDescription . " Presenters: " . implode(", ", $presenterNames));
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