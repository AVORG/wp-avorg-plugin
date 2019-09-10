<?php

namespace Avorg;

use natlib\Factory;
use ReflectionException;

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
        $pathOptions = [
            "dist/frontend.js" => [],
            "//vjs.zencdn.net/7.0/video.min.js" => [],
            "https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-hls/5.14.1/videojs-contrib-hls.min.js" => [],
            "dist/editor.js" => [
                "actions" => ["enqueue_block_editor_assets"],
                "deps" => ['wp-element', 'wp-blocks', 'wp-components', 'wp-i18n']
            ]
        ];
        $paths = array_keys($pathOptions);

        return array_map(function($path) use($pathOptions) {
            $options = $pathOptions[$path];

            return $this->getScript($path, $options);
        }, $paths);
    }

    /**
     * @param $path
     * @param array $options
     * @return Script
     * @throws ReflectionException
     */
    public function getScript($path, $options = []) {
	    $actions = $this->arrSafe("actions", $options, ["wp_enqueue_scripts"]);
	    $deps = $this->arrSafe("deps", $options, []);

		/** @var Script $script */
		$script = $this->factory->obtain("Avorg\\Script");

		$script->setPath($path)->setActions(...$actions)->setDeps(...$deps);

		return $script;
	}

    private function arrSafe($key, $array, $default = Null)
    {
        return array_key_exists($key, $array) ? $array[$key] : $default;
    }
}