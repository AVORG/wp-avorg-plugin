<?php

namespace Avorg\Block;

use Avorg\Block;
use Avorg\Php;
use Avorg\Renderer;
use Avorg\WordPress;
use function defined;

if (!defined('ABSPATH')) exit;

class Placeholder extends Block
{
    protected $template = 'block-placeholder.twig';

    /** @var Php */
    private $php;

    public function __construct(
        Php $php,
        Renderer $renderer,
        WordPress $wp
    )
    {
        parent::__construct($renderer, $wp);

        $this->php = $php;
    }

    protected function getData($attributes, $content)
    {
        $placeholderId = $this->arrSafe('id', $attributes);
        $mediaId = $this->getEntityId();
        $posts = $this->getPosts($placeholderId, $mediaId) ?? [];

        return [
            "content" => $this->php->arrayRand($posts)
        ];
    }

    /**
     * @param $identifier
     * @param null $mediaId
     */
    private function getPosts($identifier, $mediaId = null)
    {
        $args = array_merge(
            [
                'posts_per_page' => -1,
                'post_type' => 'avorg-content-bits',
                'meta_query' => [
                    [
                        'key' => 'avorgBitIdentifier',
                        'value' => $identifier
                    ]
                ]
            ],
            ($mediaId) ? [
                'tax_query' => [
                    [
                        'taxonomy' => 'avorgMediaIds',
                        'field' => 'slug',
                        'terms' => $mediaId
                    ]
                ]
            ] : []
        );

        return $this->wp->get_posts($args);
    }
}