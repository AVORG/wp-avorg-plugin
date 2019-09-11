<?php

namespace Avorg;

use Exception;
use natlib\Stub;

class StubTwig extends Twig
{
	use Stub;

	/**
	 * @param $templateFile
	 * @param array $data
	 * @return mixed|string|null
	 * @throws Exception
	 */
	public function render($templateFile, $data = [])
	{
		if (!file_exists(AVORG_BASE_PATH . "/view/$templateFile")) {
			throw new Exception("Template file `$templateFile` does not exist!");
		}

		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function assertErrorRenderedWithMessage($message, $url = null)
	{
		$data = [
			"type" => "error",
			"message" => $message
		];

		if ($url) {
			$data['url'] = $url;
		}

		$this->assertTwigTemplateRenderedWithData("molecule-notice.twig", $data);
	}

	public function assertErrorNotRenderedWithMessage($message, $url = null)
	{
		$data = [
			"type" => "error",
			"message" => $message
		];

		if ($url) {
			$data['url'] = $url;
		}

		$this->assertTwigTemplateNotRenderedWithData("molecule-notice.twig", $data);
	}

    public function getRenderedData($template)
    {
        $calls = $this->getCalls("render");
        $filtered_calls = array_filter($calls, function($call) use($template) {
            return $call[0] === $template;
        });

        return $calls[0][1]['avorg']->getData();
    }

	public function assertTwigTemplateRendered($template)
	{
		$this->assertTwigTemplateRenderedWithDataMatching($template, function() { return true; });
	}

	public function assertTwigTemplateRenderedWithData($template, $data)
	{
		$this->assertTwigTemplateRenderedWithDataMatching($template, [$this, "doesDataObjectIncludeData"], $data);
	}

	public function assertTwigTemplateNotRenderedWithData($template, $data)
	{
		$this->assertTwigTemplateNotRenderedWithDataMatching($template, [$this, "doesDataObjectIncludeData"], $data);
	}

	public function assertTwigTemplateRenderedWithDataMatching($template, $callable, ...$params)
	{
		$this->assertAnyCallMatches("render", function($call) use($template, $callable, $params) {
			return $this->doesCallDataMatch($call, $template, $callable, $params);
		});
	}

	public function assertTwigTemplateNotRenderedWithDataMatching($template, $callable, ...$params)
	{
		$this->assertNoCallsMatch("render", function($call) use($template, $callable, $params) {
			return $this->doesCallDataMatch($call, $template, $callable, $params);
		});
	}

	private function doesCallDataMatch($call, $template, $callable, $params)
	{
		$callTemplate = $call[0];
		$callDataObject = $call[1]["avorg"];

		$doesTemplateMatch = $callTemplate === $template;
		$doesMatch = call_user_func($callable, $callDataObject, ...$params);

		return $doesTemplateMatch && $doesMatch;
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