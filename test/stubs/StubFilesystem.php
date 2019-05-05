<?php

namespace Avorg;

class StubFilesystem extends Filesystem
{
	use Stub;

	public function getFile($rel_path)
	{
		if ($rel_path === "languages.json") {
			return parent::getFile($rel_path);
		}

		return $this->handleCall(__FUNCTION__, func_get_args());
	}
}