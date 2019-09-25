<?php

namespace Avorg\RestController\DataObjects;

use Avorg\DataObjectRepository\PresenterRepository;
use Avorg\RestController;
use Avorg\WordPress;
use function defined;

if (!defined('ABSPATH')) exit;

class Presenters extends RestController\DataObjects
{
    protected $route = '/presenters';

    public function __construct(PresenterRepository $presenterRepository, WordPress $wp)
    {
        parent::__construct($wp);

        $this->repository = $presenterRepository;
    }
}