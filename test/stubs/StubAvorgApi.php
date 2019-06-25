<?php

namespace Avorg;

use natlib\Stub;

class StubAvorgApi extends AvorgApi
{
	use Stub;

	public function getBook($id)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function getBooks()
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

	public function getPresenterRecordings($presenterId)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function getBookRecordings($bookId)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	/* Helper Methods */

	public function loadRecording($dataArray) {
		$responseObject = $this->convertRecordingArrayToResponseObject($dataArray);

		$this->setReturnValue("getRecording", $responseObject);
	}

	public function loadRecordings(...$dataArrays) {
		$objects = array_map([$this, "convertRecordingArrayToResponseObject"], $dataArrays);

		$this->setReturnValue("getRecordings", $objects);
	}

	/**
	 * @param $dataArray
	 * @return mixed
	 */
	private function convertRecordingArrayToResponseObject($dataArray)
	{
		return $this->convertArrayToObjectRecursively([
			"recordings" => $dataArray
		]);
	}

	/**
	 * @param $array
	 * @return mixed
	 */
	private function convertArrayToObjectRecursively($array)
	{
		return json_decode(json_encode($array), FALSE);
	}
}