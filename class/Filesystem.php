<?php

namespace Avorg;

use function defined;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;

if (!defined('ABSPATH')) exit;

class Filesystem
{
	public function getFile($rel_path)
	{
		$abs_path = $this->toAbsPath($rel_path);

		if (!$this->isPathSafe($abs_path)) {
			return null;
		}

		return file_get_contents($abs_path);
	}

	public function getClassesRecursively($rel_dir)
	{
		$paths = $this->getMatchingPathsRecursive($rel_dir, "/\.php/") ?: [];

		return array_map([$this, "pathToClassname"], $paths);
	}

	public function getMatchingPathsRecursive($rel_dir, $pattern)
	{
		$abs_dir = $this->toAbsPath($rel_dir);

		if (!$this->isPathSafe($abs_dir)) {
			return null;
		}

		$directoryIterator = new RecursiveDirectoryIterator($abs_dir);
		$iteratorIterator = new RecursiveIteratorIterator($directoryIterator);
		$regexIterator = new RegexIterator(
			$iteratorIterator,
			$pattern,
			RecursiveRegexIterator::MATCH
		);

		return array_keys(iterator_to_array($regexIterator));
	}

	/**
	 * @param $rel_path
	 * @return bool|string
	 */
	private function toAbsPath($rel_path)
	{
		return realpath(AVORG_BASE_PATH . "/$rel_path");
	}

	/**
	 * @param $abs_path
	 * @return bool
	 */
	private function isPathSafe($abs_path)
	{
		return strstr($abs_path, AVORG_BASE_PATH) !== FALSE;
	}

	public function pathToClassname($path)
	{
		$relativePath = str_replace(AVORG_BASE_PATH . "/class", "", $path);
		$pathMinusExtension = explode(".", $relativePath)[0];
		$classname = str_replace("/", "\\", $pathMinusExtension);

		return "Avorg\\" . trim($classname, "\\");
	}
}