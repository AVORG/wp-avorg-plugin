<?php

namespace Avorg\Block;

use Avorg\Block;
use Avorg\DataObjectRepository\PresentationRepository;
use Avorg\Php;
use Avorg\Renderer;
use Avorg\WordPress;
use Exception;
use function defined;

if (!defined('ABSPATH')) exit;

class Placeholder extends Block
{
    protected $template = 'block-placeholder.twig';

    protected function getData()
    {
        // TODO: Implement getData() method.
    }
}