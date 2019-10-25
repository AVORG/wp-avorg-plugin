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
     * @param array $request
     * @return array|string
     * @throws Exception
     */
    public function handleGet($request = [])
    {
        if (array_key_exists('presentationId', $request)) {
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
     * @param array $request
     * @throws Exception
     */
    public function handlePost($request = [])
    {
        $this->api->addFavorite(
            $request['presentationId'],
            $this->session->userId,
            $this->session->sessionToken
        );
    }

    /**
     * @param array $request
     * @return string|void
     * @throws Exception
     */
    public function handleDelete($request = [])
    {
        $this->api->unFavorite(
            $request['presentationId'],
            $this->session->userId,
            $this->session->sessionToken
        );
    }
}