<?php

namespace Avorg;

use natlib\Factory;

if (!\defined('ABSPATH')) exit;

class ScriptFactory
{
	/** @var Factory $factory */
	private $factory;

	public function __construct(Factory $factory)
	{
		$this->factory = $factory;
	}

    public function registerCallbacks()
    {
        $scripts = $this->getScripts();
        array_walk($scripts, function (Script $script) {
            $script->registerCallbacks();
        });
    }

    private function getScripts()
    {
        $paths = [
            "https://polyfill.io/v3/polyfill.min.js?features=default",
            "//vjs.zencdn.net/7.0/video.min.js",
            "https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-hls/5.14.1/videojs-contrib-hls.min.js"
        ];

        return array_map(function($path) {
            return $this->getScript($path);
        }, $paths);
    }

	public function getScript($path, ...$actions) {
		/** @var Script $script */
		$script = $this->factory->obtain("Avorg\\Script");

		$script->setPath($path)->setActions(...$actions);

		return $script;
	}
}