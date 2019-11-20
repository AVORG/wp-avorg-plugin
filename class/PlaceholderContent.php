<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class PlaceholderContent
{
    /** @var Php $php */
    private $php;

    /** @var Renderer $twig */
    private $twig;

    /** @var WordPress $wp */
    private $wp;

    public function __construct(Php $php, Renderer $twig, WordPress $wp)
    {
        $this->php = $php;
        $this->twig = $twig;
        $this->wp = $wp;
    }

    public function registerCallbacks()
    {
        $this->wp->add_action("add_meta_boxes", [$this, "addMetaBoxes"]);
        $this->wp->add_action("save_post", [$this, "savePost"]);
        $this->wp->add_action("rest_api_init", [$this, 'exposeIdentifierInApi']);
        $this->wp->add_action("init", [$this, "init"]);
    }

    public function init()
    {
        $this->addCustomPostType();
    }

    private function addCustomPostType()
    {
        $labels = array(
            'name' => 'Placeholder Content',
            'singular_name' => 'Placeholder Item',
            'menu_name' => 'Placeholder Content',
            'name_admin_bar' => 'Placeholder Content',
            'archives' => 'Item Archives',
            'attributes' => 'Item Attributes',
            'parent_item_colon' => 'Parent Item:',
            'all_items' => 'All Items',
            'add_new_item' => 'Add New Item',
            'add_new' => 'Add New',
            'new_item' => 'New Item',
            'edit_item' => 'Edit Item',
            'update_item' => 'Update Item',
            'view_item' => 'View Item',
            'view_items' => 'View Items',
            'search_items' => 'Search Item',
            'not_found' => 'Not found',
            'not_found_in_trash' => 'Not found in Trash',
            'featured_image' => 'Featured Image',
            'set_featured_image' => 'Set featured image',
            'remove_featured_image' => 'Remove featured image',
            'use_featured_image' => 'Use as featured image',
            'insert_into_item' => 'Insert into item',
            'uploaded_to_this_item' => 'Uploaded to this item',
            'items_list' => 'Items list',
            'items_list_navigation' => 'Items list navigation',
            'filter_items_list' => 'Filter items list',
        );

        $args = array(
            'label' => 'Placeholder Item',
            'description' => 'Pieces of content that can be pulled in using a shortcode',
            'labels' => $labels,
            'supports' => array('title', 'editor', 'revisions', 'custom-fields'),
            'taxonomies' => array(),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 20,
            'menu_icon' => 'dashicons-location',
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'rewrite' => false,
            'capability_type' => 'post',
            'show_in_rest' => true,
        );

        $this->wp->register_post_type("avorg-content-bits", $args);
    }

    public function exposeIdentifierInApi()
    {
        $this->wp->register_meta('post', 'avorgBitIdentifier', [
            'object_subtype' => 'avorg-content-bits',
            'type' => 'string',
            'description' => 'Placeholder identifier',
            'show_in_rest' => True,
            'single' => True
        ]);
    }

    public function addMetaBoxes()
    {
        $this->addMediaIdMetaBox();
        $this->addIdentifierMetaBox();
        $this->addDocumentationMetaBox();
    }

    private function addMediaIdMetaBox()
    {
        $args = [
            'avorg_placeholderContent_mediaIds',
            'Media IDs',
            [$this, "renderMediaIdMetaBox"],
            'avorg-content-bits',
            'side'
        ];

        $this->wp->add_meta_box(...$args);
    }

    public function renderMediaIdMetaBox()
    {
        $this->twig->render("molecule-mediaIdMetaBox.twig");
    }

    private function addDocumentationMetaBox()
    {
        $args = [
            'avorg_contentBits_docs',
            'Documentation',
            [$this, "renderDocumentationMetaBox"],
            'avorg-content-bits'
        ];

        $this->wp->add_meta_box(...$args);
    }

    public function renderDocumentationMetaBox()
    {
        $this->twig->render("molecule-contentBitsDocs.html");
    }

    private function addIdentifierMetaBox()
    {
        $args = [
            'avorg_contentBits_identifier',
            'Identifier',
            [$this, "renderIdentifierMetaBox"],
            'avorg-content-bits',
            'side',
            'default'
        ];

        $this->wp->add_meta_box(...$args);
    }

    public function renderIdentifierMetaBox()
    {
        $postId = $this->wp->get_the_ID();
        $savedValue = $this->wp->get_post_meta($postId, "avorgBitIdentifier", true);

        $this->twig->render("molecule-identifierMetaBox.twig", [
            "savedIdentifier" => $savedValue,
            "allIdentifiers" => $this->wp->get_all_meta_values("avorgBitIdentifier")
        ]);
    }

    public function savePost()
    {
        $this->saveIdentifier();
        $this->saveMediaIds();
    }

    private function saveIdentifier(): void
    {
        if (!isset($_POST["avorgBitIdentifier"])) return;

        $postId = $this->wp->get_the_ID();
        $this->wp->update_post_meta(
            $postId,
            "avorgBitIdentifier",
            $_POST["avorgBitIdentifier"]
        );
    }

    private function saveMediaIds(): void
    {
        if (!isset($_POST["avorgMediaIds"])) return;

        $postId = $this->wp->get_the_ID();
        $this->wp->update_post_meta(
            $postId,
            "avorgMediaIds",
            json_decode($_POST["avorgMediaIds"])
        );
    }
}
