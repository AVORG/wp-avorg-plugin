<?php

namespace Avorg;

class StubAvorgApi extends AvorgApi
{
	use Stub;

	public function getPlaylist($id)
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
}