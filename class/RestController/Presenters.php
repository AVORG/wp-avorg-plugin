<?php

namespace Avorg\RestController;

use Avorg\DataObjectRepository\PresenterRepository;
use Avorg\RestController;
use Avorg\WordPress;
use function defined;

if (!defined('ABSPATH')) exit;

class Presenters extends RestController
{
    protected $route = '/presenters';

    /** @var PresenterRepository $presenterRepository */
    private $presenterRepository;

    public function __construct(PresenterRepository $presenterRepository, WordPress $wp)
    {
        parent::__construct($wp);

        $this->presenterRepository = $presenterRepository;
    }

    public function getData()
    {
        return $this->presenterRepository->getPresenters();
    }
}