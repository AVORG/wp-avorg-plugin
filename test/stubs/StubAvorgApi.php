<?php

namespace Avorg;

use natlib\Stub;

class StubAvorgApi extends AvorgApi
{
	use Stub;

	public function getOneSeries($id)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function getAllSeries()
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function getSponsor($id)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function getSponsors()
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function getConferences()
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function getStories()
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function getBibleBooks($bible_id)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function getBibleChapters($bibleId, $bookId, $testamentId)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function getBibles()
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function getTopic($id)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function getTopics()
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function getBook($id)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function getBooks()
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function getPlaylists()
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function getPlaylist($id)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function getPresenter($id)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function getPresenters($page = 0)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function getRecording($id)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function getRecordings($list = "")
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function getTopicRecordings($topicId)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function getSponsorRecordings($sponsorId)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function getSeriesRecordings($seriesId)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function getPresenterRecordings($presenterId)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function getConferenceRecordings($conferenceId)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function getBookRecordings($bookId)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	/* Helper Methods */

	public function loadOneSeries($data)
	{
		$this->setDataObjectReturnValue("getOneSeries", $data);
	}

	public function loadAllSeries(...$dataArrays)
	{
		$this->setDataObjectsReturnValue("getAllSeries", $dataArrays);
	}

	public function loadSponsor($data)
	{
		$this->setDataObjectReturnValue("getSponsor", $data);
	}

	public function loadSponsors(...$dataArrays)
	{
		$this->setDataObjectsReturnValue("getSponsors", $dataArrays);
	}

	public function loadConferences(...$dataArrays)
	{
		$this->setDataObjectsReturnValue("getConferences", $dataArrays);
	}

	public function loadStories(...$dataArrays)
	{
		$this->setDataObjectsReturnValue("getStories", $dataArrays);
	}

	public function loadPlaylists(...$dataArrays)
	{
		$this->setDataObjectsReturnValue("getPlaylists", $dataArrays);
	}

	public function loadPlaylist($dataArray)
	{
		$this->setDataObjectReturnValue("getPlaylist", $dataArray);
	}

	public function loadBibleChapters(...$dataArrays)
	{
		$this->setDataObjectsReturnValue("getBibleChapters", $dataArrays);
	}

	public function loadBibleBooks(...$dataArrays)
	{
		$objects = array_reduce($dataArrays, function($carry, $dataArray) {
			$key = array_key_exists("book_id", $dataArray) ? $dataArray["book_id"] : rand();
			$carry[$key] = $this->testCase->convertArrayToObjectRecursively($dataArray);

			return $carry;
		});

		$this->setReturnValue("getBibleBooks", $objects);
	}

	public function loadBibles(...$dataArrays)
	{
		$objects = array_reduce($dataArrays, function($carry, $dataArray) {
			$key = array_key_exists("dam_id", $dataArray) ? $dataArray["dam_id"] : rand();
			$dataArray["dam_id"] = $key;
			$carry[$key] = $this->testCase->convertArrayToObjectRecursively($dataArray);

			return $carry;
		}, []);

		$this->setReturnValue("getBibles", $objects);
	}

	public function loadPresenter($data)
	{
		$this->setDataObjectReturnValue("getPresenter", $data);
	}

	public function loadTopic($data)
	{
		$this->setDataObjectReturnValue("getTopic", $data);
	}

	public function loadTopics(...$dataArrays)
	{
		$this->setDataObjectsReturnValue("getTopics", $dataArrays);
	}

	public function loadBook($data)
	{
		$this->setDataObjectReturnValue("getBook", $data);
	}

	public function loadRecording($dataArray)
	{
		$this->setDataObjectReturnValue("getRecording", $dataArray);
	}

	public function loadRecordings(...$dataArrays)
	{
		$this->setDataObjectsReturnValue("getRecordings", $dataArrays);
	}

	public function loadConferenceRecordings(...$dataArrays)
	{
		$this->setDataObjectsReturnValue("getConferenceRecordings", $dataArrays);
	}

	public function loadSeriesRecordings(...$dataArrays)
	{
		$this->setDataObjectsReturnValue("getSeriesRecordings", $dataArrays);
	}

	public function loadSponsorRecordings(...$dataArrays)
	{
		$this->setDataObjectsReturnValue("getSponsorRecordings", $dataArrays);
	}

	public function loadBookRecordings(...$dataArrays)
	{
		$this->setDataObjectsReturnValue("getBookRecordings", $dataArrays);
	}

	public function loadPresenterRecordings(...$dataArrays)
	{
		$this->setDataObjectsReturnValue("getPresenterRecordings", $dataArrays);
	}

	public function loadTopicRecordings(...$dataArrays)
	{
		$this->setDataObjectsReturnValue("getTopicRecordings", $dataArrays);
	}

	private function setDataObjectsReturnValue($function, $dataArrays)
	{
		$objects = $this->convertArraysToObjectsRecursively($dataArrays);

		$this->setReturnValue($function, $objects);
	}

	private function setDataObjectReturnValue($function, $dataArray)
	{
		$object = $this->testCase->convertArrayToObjectRecursively($dataArray);

		$this->setReturnValue($function, $object);
	}

	private function convertArraysToObjectsRecursively($arrays)
	{
		return array_map([$this->testCase, "convertArrayToObjectRecursively"], $arrays);
	}
}