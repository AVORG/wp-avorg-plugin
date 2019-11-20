<?php

namespace Avorg\RestController;

use Avorg\AvorgApi;
use Avorg\RestController;
use Avorg\Session;
use Avorg\WordPress;
use WP_REST_Request;
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
     * @param WP_REST_Request $request
     * @return array|boolean
     * @throws Exception
     */
    public function handleGet(WP_REST_Request $request)
    {
        if ($request['presentationId']) {
            return $this->api->isFavorited(
                $request['presentationId'],
                $this->session->userId,
                $this->session->sessionToken
            );
        }

        return $this->api->getFavorites(
            $this->session->userId,
            $this->session->sessionToken
        );
    }

    /**
     * @param WP_REST_Request $request
     * @return bool
     * @throws Exception
     */
    public function handlePost(WP_REST_Request $request)
    {
        return $this->api->addFavorite(
            $request['presentationId'],
            $this->session->userId,
            $this->session->sessionToken
        );
    }

    /**
     * @param WP_REST_Request $request
     * @return string|void
     * @throws Exception
     */
    public function handleDelete(WP_REST_Request $request)
    {
        return $this->api->unFavorite(
            $request['presentationId'],
            $this->session->userId,
            $this->session->sessionToken
        );
    }
}