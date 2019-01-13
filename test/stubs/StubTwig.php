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

	public function assertTwigTemplateRenderedWithData($template, $data)
	{
		$this->assertAnyCallMatches("render", function($carry, $call) use($template, $data) {
			$callTemplate = $call[0];
			$callDataObject = $call[1]["avorg"];

			$doesTemplateMatch = $callTemplate === $template;
			$doesIncludeData = $this->doesDataObjectIncludeData($callDataObject, $data);

			return $carry || ($doesTemplateMatch && $doesIncludeData);
		});
	}

	public function assertTwigTemplateRendered($template)
	{
		$message = "Failed to assert that $template was rendered";

		$this->assertAnyCallMatches("render", function($carry, $call) use($template) {
			$callTemplate = $call[0];

			return $carry || ($callTemplate === $template);
		}, $message);
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