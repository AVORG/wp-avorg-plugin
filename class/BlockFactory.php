<?php

namespace Avorg;

use function defined;
use natlib\Factory;
use ReflectionException;

if (!defined('ABSPATH')) exit;

class BlockFactory
{
    /** @var ScanningFactory $scanningFactory */
    private $scanningFactory;

    public function __construct(ScanningFactory $scanningFactory)
    {
        $this->scanningFactory = $scanningFactory;
    }

    public function registerCallbacks()
    {
        $entities = $this->scanningFactory->getEntities("class/Block");
        array_walk($entities, function (Block $entity) {
            $entity->registerCallbacks();
        });
    }
}