<?php

namespace Avorg;

class StubTwig extends Twig
{
	use Stub;

	public function render($templateFile, $data = [])
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function assertErrorRenderedWithMessage($message)
	{
		$this->assertTwigTemplateRenderedWithData("molecule-notice.twig", [
			"type" => "error",
			"message" => $message
		]);
	}

	public function assertTwigTemplateRendered($template)
	{
		$this->assertTwigTemplateRenderedWithDataMatching($template, function() { return true; });
	}

	public function assertTwigTemplateRenderedWithData($template, $data)
	{
		$this->assertTwigTemplateRenderedWithDataMatching($template, [$this, "doesDataObjectIncludeData"], $data);
	}

	public function assertTwigTemplateRenderedWithDataMatching($template, $callable, ...$params)
	{
		$this->assertAnyCallMatches("render", function($call) use($template, $callable, $params) {
			$callTemplate = $call[0];
			$callDataObject = $call[1]["avorg"];

			$doesTemplateMatch = $callTemplate === $template;
			$doesMatch = call_user_func($callable, $callDataObject, ...$params);

			return $doesTemplateMatch && $doesMatch;
		});
	}

	/**
	 * @param $haystackObject
	 * @param $keyValueNeedles
	 * @return mixed
	 */
	private function doesDataObjectIncludeData($haystackObject, $keyValueNeedles)
	{
		return array_reduce(array_keys($keyValueNeedles), function ($carry, $key) use ($keyValueNeedles, $haystackObject) {
			$datum = $keyValueNeedles[$key];

			return $carry && $haystackObject->$key === $datum;
		}, true);
	}
}