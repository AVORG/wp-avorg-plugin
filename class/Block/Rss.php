<?php

namespace Avorg\Block;

use Avorg\Block;
use Avorg\DataObjectRepository\PresentationRepository;
use Avorg\Php;
use Avorg\Renderer;
use Avorg\Router;
use Avorg\WordPress;
use Exception;
use function defined;

if (!defined('ABSPATH')) exit;

class Rss extends Block
{
    protected $template = 'block-rss.twig';

    /** @var Router $router */
    private $router;

    public function __construct(
        Renderer $renderer,
        Router $router,
        WordPress $wp
    )
    {
        parent::__construct($renderer, $wp);

        $this->router = $router;
    }

    /**
     * @param $attributes
     * @param $content
     * @return array
     * @throws Exception
     */
    protected function getData($attributes, $content)
    {
        if (!array_key_exists('feed', $attributes)) return [];

        return [
           "url" =>  $this->router->buildUrl($attributes['feed'], [
               'entity_id' => $this->getEntityId(),
               'slug' => $this->wp->get_query_var('slug')
           ])
        ];
    }
}