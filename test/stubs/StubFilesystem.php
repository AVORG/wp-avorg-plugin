<?php

namespace Avorg;

use natlib\Stub;

class StubFilesystem extends Filesystem
{
	use Stub;

	public function getClassesRecursively($rel_path)
	{
		$response = $this->handleCall(__FUNCTION__, func_get_args());

		return $response ?: parent::getClassesRecursively($rel_path);
	}

	public function getFile($rel_path)
	{
		if ($rel_path === "languages.json") {
			return parent::getFile($rel_path);
		}

		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function getMatchingPathsRecursive($rel_dir, $pattern)
	{
		$response = $this->handleCall(__FUNCTION__, func_get_args());

		return $response ?: parent::getMatchingPathsRecursive($rel_dir, $pattern);
	}
}