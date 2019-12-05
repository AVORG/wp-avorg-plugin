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

    /** @var WordPress $wp */
    private $wp;

    public function __construct(ScanningFactory $scanningFactory, WordPress $wp)
    {
        $this->scanningFactory = $scanningFactory;
        $this->wp = $wp;
    }

    public function registerCallbacks()
    {
        $this->wp->add_filter('block_categories', [$this, 'filterCategories']);

        $this->scanningFactory->registerCallbacks("class/Block");
    }

    public function filterCategories($categories)
    {
        return array_merge($categories, [
            [
                'slug' => 'avorg',
                'title' => 'AudioVerse'
            ]
        ]);
    }
}