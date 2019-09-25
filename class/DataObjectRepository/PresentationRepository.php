<?php

namespace Avorg\DataObjectRepository;

use Avorg\DataObject;
use Avorg\DataObjectRepository;
use function defined;
use Exception;
use natlib\Stub;

if (!defined('ABSPATH')) exit;

class PresentationRepository extends DataObjectRepository
{
	protected $dataObjectClass = "Avorg\\DataObject\\Recording\\Presentation";

	/**
	 * @param $presentationId
	 * @return array
	 * @throws Exception
	 */
	public function getRelatedPresentations($presentationId)
	{
		$presentation = $this->getPresentation($presentationId);

		if (!$presentation) return [];

		$conferencePresentations = $this->getConferencePresentations($presentation->conferenceId);
		$seriesPresentations = ($presentation->seriesId === "0") ? [] :
			$this->getSeriesPresentations($presentation->seriesId);
		$sponsorPresentations = $this->getSponsorPresentations($presentation->sponsorId);
		$presenterPresentations = array_reduce((array) $presentation->presenters, function($carry, $presenter) {
            $presenterId = array_key_exists('id', $presenter) ? $presenter['id'] : null;

            return $presenterId ? array_merge($carry, $this->getPresenterPresentations($presenterId)) : $carry;
		}, []);

		$relatedPresentations = array_merge(
			$conferencePresentations,
			$seriesPresentations,
			$sponsorPresentations,
			$presenterPresentations
		);

		return array_unique($relatedPresentations);
	}

    /**
     * @param string $list
     * @return array
     * @throws Exception
     */
    public function getPresentations($list = "")
    {
    	$list = strtolower($list);

		if ($list && !in_array($list, ["featured", "popular"])) $list = "";

        $apiResponse = $this->api->getRecordings($list);

		return $this->makeDataObjects($apiResponse);
    }

	/**
	 * @param $presenterId
	 * @return array
	 * @throws Exception
	 */
	public function getPresenterPresentations($presenterId)
	{
		$apiResponse = $this->api->getPresenterRecordings($presenterId);

		return $this->makeDataObjects($apiResponse);
	}

	/**
	 * @param $id
	 * @return DataObject|null
	 * @throws Exception
	 */
	public function getPresentation($id)
    {
        $apiResponse = $this->api->getRecording($id);

        return $apiResponse ? $this->makeDataObject($apiResponse) : null;
    }

	/**
	 * @param $conferenceId
	 * @return array
	 * @throws Exception
	 */
	public function getConferencePresentations($conferenceId)
	{
		$rawObjects = $this->api->getConferenceRecordings($conferenceId);

		return $this->makeDataObjects($rawObjects);
	}

	/**
	 * @param $sponsorId
	 * @return array
	 * @throws Exception
	 */
	public function getSponsorPresentations($sponsorId)
	{
		$rawObjects = $this->api->getSponsorRecordings($sponsorId);

		return $this->makeDataObjects($rawObjects);
	}

	/**
	 * @param $topicId
	 * @return array
	 * @throws Exception
	 */
	public function getTopicPresentations($topicId)
	{
		$apiResponse = $this->api->getTopicRecordings($topicId);

		return $this->makeDataObjects($apiResponse);
	}

	/**
	 * @param $playlistId
	 * @return array
	 * @throws Exception
	 */
	public function getPlaylistPresentations($playlistId)
	{
		$apiResponse = (object)$this->api->getPlaylist($playlistId);
		$recordings = property_exists($apiResponse, 'recordings') ? $apiResponse : [];

		return array_map(function ($recording) {
			return $this->makeDataObject($recording);
		}, (array)$recordings);
	}

	/**
	 * @param $bookId
	 * @return array
	 * @throws Exception
	 */
	public function getBookPresentations($bookId)
	{
		$apiResponse = $this->api->getBookRecordings($bookId);

		return $this->makeDataObjects($apiResponse);
	}

	public function getSeriesPresentations($seriesId)
	{
		$rawObjects = $this->api->getSeriesRecordings($seriesId);

		return $this->makeDataObjects($rawObjects);
	}

    public function getDataObjects($search = null, $start = null)
    {
        // TODO: Implement getDataObjects() method.
    }
}