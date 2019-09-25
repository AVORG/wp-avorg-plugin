<?php

namespace Avorg\RestController\DataObjects;

use Avorg\DataObjectRepository\BookRepository;
use Avorg\RestController;
use Avorg\WordPress;
use Exception;

if (!defined('ABSPATH')) exit;

class Books extends RestController\DataObjects
{
    protected $route = '/books';

    public function __construct(BookRepository $repository, WordPress $wp)
    {
        parent::__construct($wp);

        $this->repository = $repository;
    }
}