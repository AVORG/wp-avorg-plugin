<?php

namespace Avorg;

use function defined;
use natlib\Factory;
use ReflectionException;

if (!defined('ABSPATH')) exit;

class RestControllerFactory
{
    /** @var Factory $factory */
    private $factory;

    /** @var Filesystem $filesystem */
    private $filesystem;

    public function __construct(Factory $factory, Filesystem $filesystem)
    {
        $this->factory = $factory;
        $this->filesystem = $filesystem;
    }

    public function registerCallbacks()
    {
        $controllers = $this->getControllers();
        array_walk($controllers, function (RestController $controller) {
            $controller->registerCallbacks();
        });
    }

    /**
     * @return array
     */
    private function getControllers()
    {
        return array_map(
            [$this, "getController"],
            (array) $this->filesystem->getClassesRecursively("class/RestController")
        );
    }

    /**
     * @param $class
     * @return mixed
     * @throws ReflectionException
     */
    private function getController($class)
    {
        return $this->factory->secure($class);
    }
}