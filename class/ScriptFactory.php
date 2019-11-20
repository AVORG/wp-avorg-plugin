<?php

namespace Avorg;

use natlib\Factory;
use ReflectionException;
use function defined;

if (!defined('ABSPATH')) exit;

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
                'in_footer' => true,
                'deps' => ['wp-element']
            ],
            "dist/editor.js" => [
                "handle" => "Avorg_Script_Editor",
                "in_footer" => true,
                "actions" => ["enqueue_block_editor_assets"],
                "deps" => ['wp-element', 'wp-blocks', 'wp-components', 'wp-i18n']
            ]
        ];
        $paths = array_keys($pathOptions);

        return array_map(function($path) use($pathOptions) {
            return $this->getScript($path, $pathOptions[$path]);
        }, $paths);
    }

    /**
     * @param $path
     * @param array $options
     * @return Script
     * @throws ReflectionException
     */
    public function getScript($path, $options = []) {
        $handle = $options["handle"] ?? null;
	    $actions = $options["actions"] ?? ["wp_enqueue_scripts"];
	    $deps = $options["deps"] ?? [];
	    $inFooter = $options['in_footer'] ?? false;

		return $this->factory
            ->make("Avorg\\Script")
            ->setPath($path)
            ->setActions(...$actions)
            ->setHandle($handle)
            ->setDeps(...$deps)
            ->setInFooter($inFooter);
	}
}