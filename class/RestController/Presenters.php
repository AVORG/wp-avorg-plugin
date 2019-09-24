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

    protected $args = [
        'search' => [
            'description' => 'Search term',
            'type' => 'string'
        ],
        'start' => [
            'description' => 'Index of item in result set that should begin returned data',
            'type' => 'integer'
        ]
    ];

    /** @var PresenterRepository $presenterRepository */
    private $presenterRepository;

    public function __construct(PresenterRepository $presenterRepository, WordPress $wp)
    {
        parent::__construct($wp);

        $this->presenterRepository = $presenterRepository;
    }

    public function getData($request = null)
    {
        $search = '';
        $start = $request['start'] ?? null;

        return $this->presenterRepository->getPresenters($search, $start);
    }
}