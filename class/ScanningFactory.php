<?php

namespace Avorg;

use ErrorException;
use function defined;
use natlib\Factory;
use ReflectionException;

if (!defined('ABSPATH')) exit;

class ScanningFactory
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

    public function registerCallbacks($relDir)
    {
        $entities = $this->getEntities($relDir);
        array_walk($entities, function($entity) {
            $entity->registerCallbacks();
        });
    }

    /**
     * @param $relDir
     * @return array
     */
    public function getEntities($relDir)
    {
        return array_filter(array_map(
            [$this, "getEntity"],
            (array)$this->filesystem->getClassesRecursively($relDir)
        ));
    }

    /**
     * @param $class
     * @return mixed
     */
    private function getEntity($class)
    {
        try {
            return $this->factory->secure($class);
        } catch (\Exception $e) {
            return false;
        }
    }
}