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
        $posts = $this->getFilteredPosts($placeholderId, $mediaId);

        return [
            "content" => $this->php->arrayRand($posts)
        ];
    }

    /**
     * @param $identifier
     * @param null $mediaId
     * @return mixed
     */
    private function getFilteredPosts($identifier, $mediaId = null)
    {
        $posts = $this->getPosts($identifier) ?? [];

        return $this->getAssociatedPosts($posts, $mediaId)
            ?: $this->getUnassociatedPosts($posts);
    }

    private function getAssociatedPosts($posts, $mediaId)
    {
        return array_filter($posts, function($post) use($mediaId) {
            var_dump($post->ID, $post->avorgMediaIds);
            return in_array($mediaId, $post->avorgMediaIds);
        });
    }

    private function getUnassociatedPosts($posts)
    {
        return array_filter($posts, function($post) {
            return empty($post->avorgMediaIds);
        });
    }

    /**
     * @param $identifier
     * @return mixed
     */
    private function getPosts($identifier)
    {
        $args = [
            'posts_per_page' => -1,
            'post_type' => 'avorg-content-bits',
            'meta_query' => [
                [
                    'key' => 'avorgBitIdentifier',
                    'value' => $identifier
                ]
            ]
        ];

        return $this->wp->get_posts($args);
    }
}