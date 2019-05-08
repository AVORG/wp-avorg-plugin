<?php

namespace Avorg;

class StubAvorgApi extends AvorgApi
{
	use Stub;

	public function getPlaylist($id)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function getPresenters($page = 0)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function getPresentation($id)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function getPresentations($list = "")
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function getTopicPresentations($topicId)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	/* Helper Methods */

	public function loadPresentation($dataArray) {
		$responseObject = $this->convertPresentationArrayToResponseObject($dataArray);

		$this->setReturnValue("getPresentation", $responseObject);
	}

	public function loadPresentations(...$dataArrays) {
		$objects = array_map([$this, "convertPresentationArrayToResponseObject"], $dataArrays);

		$this->setReturnValue("getPresentations", $objects);
	}

	/**
	 * @param $dataArray
	 * @return mixed
	 */
	private function convertPresentationArrayToResponseObject($dataArray)
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