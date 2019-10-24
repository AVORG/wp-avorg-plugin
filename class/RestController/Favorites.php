<?php

namespace Avorg\RestController;

use Avorg\AvorgApi;
use Avorg\RestController;
use Avorg\Session;
use Avorg\WordPress;
use Exception;
use function defined;

if (!defined('ABSPATH')) exit;

class Favorites extends RestController
{
    protected $route = '/favorites';

    /** @var AvorgApi $api */
    private $api;

    /** @var Session $session */
    private $session;

    public function __construct(AvorgApi $api, Session $session, WordPress $wp)
    {
        parent::__construct($wp);

        $this->api = $api;
        $this->session = $session;
    }

    /**
     * @param null $request
     * @throws Exception
     */
    public function getData($request = null)
    {
        $this->api->addFavorite(
            $request['presentationId'],
            $this->session->userId,
            $this->session->sessionToken
        );
    }
}