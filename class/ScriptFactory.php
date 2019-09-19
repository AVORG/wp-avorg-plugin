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
            "//vjs.zencdn.net/7.0/video.min.js" => [],
            "https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-hls/5.14.1/videojs-contrib-hls.min.js" => [],
            "dist/frontend.js" => [
                "handle" => "Avorg_Script_Frontend",
            ],
            "dist/editor.js" => [
                "handle" => "Avorg_Script_Editor",
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
        $handle = $this->arrSafe("handle", $options, null);
	    $actions = $this->arrSafe("actions", $options, ["wp_enqueue_scripts"]);
	    $deps = $this->arrSafe("deps", $options, []);

		return $this->factory->make("Avorg\\Script")
            ->setPath($path)
            ->setActions(...$actions)
            ->setHandle($handle)
            ->setDeps(...$deps);
	}

    private function arrSafe($key, $array, $default = Null)
    {
        return array_key_exists($key, $array) ? $array[$key] : $default;
    }
}