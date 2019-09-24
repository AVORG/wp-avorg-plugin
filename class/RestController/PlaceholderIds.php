<?php

namespace Avorg\RestController;

use Avorg\RestController;
use function defined;

if (!defined('ABSPATH')) exit;

class PlaceholderIds extends RestController
{
    protected $route = '/placeholder-ids';

    public function getData()
    {
        return $this->wp->get_all_meta_values("avorgBitIdentifier");
    }
}