<?php

namespace Avorg\DataObject\Recording;

use Avorg\DataObject;
use Avorg\DataObject\Presenter;
use Avorg\DataObjectRepository\PresenterRepository;
use Avorg\MediaFile;
use Avorg\Renderer;
use Avorg\Router;
use function defined;

if (!defined('ABSPATH')) exit;

class Presentation extends DataObject\Recording
{
	protected $detailClass = "Avorg\Page\Presentation\Detail";

	/** @var PresenterRepository $presenterRepository */
    private $presenterRepository;

    public function __construct(PresenterRepository $presenterRepository, Renderer $renderer, Router $router)
    {
        parent::__construct($renderer, $router);

        $this->presenterRepository = $presenterRepository;
    }

	public function toArray()
	{
		return array_merge(parent::toArray(), [
			"logUrl" => $this->getLogUrl(),
			"datePublished" => $this->getDatePublished(),
			"presenters" => $this->presentersToArray(),
			"presentersString" => $this->getPresentersString(),
			"image" => $this->getImage(),
			"description" => $this->getDescription(),
			"audioFiles" => $this->convertMediaFilesToArrays($this->getAudioFiles()),
			"videoFiles" => $this->convertMediaFilesToArrays($this->getVideoFiles()),
		]);
	}

	private function presentersToArray()
    {
        return array_map(function(Presenter $presenter) {
            return $presenter->toArray();
        }, $this->getPresenters());
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
	    return $this->presenterRepository->makePresenters($this->data->presenters ?? []);

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
		$presenterFragments = array_map(function (Presenter $presenter) {
			return $presenter->getName();
		}, $presenters);

		return implode(", ", $presenterFragments);
	}

	public function getDatePublished()
	{
		return $this->publishDate;
	}

	public function getDescription()
	{
		$rawDescription = property_exists($this->data, 'description') ? $this->data->description : null;

		return trim( $rawDescription . " Presenters: " . $this->getPresentersString());
	}

	public function getImage()
	{
		if (!$this->data) return null;

		return $this->data->photo86 ??
            $this->getPresenters()[0]->photo256 ??
            AVORG_LOGO_URL;
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