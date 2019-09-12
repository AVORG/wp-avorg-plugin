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
        $this->scanningFactory->registerCallbacks("class/Block");
    }
}