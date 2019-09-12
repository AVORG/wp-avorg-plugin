<?php

namespace Avorg;

use function defined;
use natlib\Factory;
use ReflectionException;

if (!defined('ABSPATH')) exit;

class RestControllerFactory
{
    /** @var ScanningFactory $scanningFactory */
    private $scanningFactory;

    public function __construct(ScanningFactory $scanningFactory)
    {
        $this->scanningFactory = $scanningFactory;
    }

    public function registerCallbacks()
    {
        $entity = $this->scanningFactory->getEntities("class/RestController");
        array_walk($entity, function (RestController $entity) {
            $entity->registerCallbacks();
        });
    }
}