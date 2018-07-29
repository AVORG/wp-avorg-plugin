<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class ContentBits
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
	
	public function init()
	{
		$this->addCustomPostType();
		$this->addMediaIdTaxonomy();
		$this->addShortcode();
	}
	
	private function addCustomPostType()
	{
		$labels = array(
			'name' => 'Content Bits',
			'singular_name' => 'Content Bit',
			'menu_name' => 'Content Bits',
			'name_admin_bar' => 'Content Bits',
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
			'label' => 'Content Bit',
			'description' => 'Pieces of content that can be pulled in using a shortcode',
			'labels' => $labels,
			'supports' => array('title', 'editor', 'revisions'),
			'taxonomies' => array(),
			'hierarchical' => false,
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 20,
			'menu_icon' => 'dashicons-screenoptions',
			'show_in_admin_bar' => true,
			'show_in_nav_menus' => true,
			'can_export' => true,
			'has_archive' => true,
			'exclude_from_search' => true,
			'publicly_queryable' => true,
			'rewrite' => false,
			'capability_type' => 'post',
		);
		
		$this->wp->call("register_post_type", "avorgContentBits", $args);
	}
	
	private function addMediaIdTaxonomy()
	{
		$labels = array(
			'name' => 'Media IDs',
			'singular_name' => 'Media ID',
			'menu_name' => 'Media IDs',
			'all_items' => 'All Items',
			'parent_item' => 'Parent Item',
			'parent_item_colon' => 'Parent Item:',
			'new_item_name' => 'New Item Name',
			'add_new_item' => 'Add New Item',
			'edit_item' => 'Edit Item',
			'update_item' => 'Update Item',
			'view_item' => 'View Item',
			'separate_items_with_commas' => 'Separate items with commas',
			'add_or_remove_items' => 'Add or remove items',
			'choose_from_most_used' => 'Choose from the most used',
			'popular_items' => 'Popular Items',
			'search_items' => 'Search Items',
			'not_found' => 'Not Found',
			'no_terms' => 'No items',
			'items_list' => 'Items list',
			'items_list_navigation' => 'Items list navigation',
		);
		
		$args = array(
			'labels' => $labels,
			'hierarchical' => false,
			'public' => true,
			'show_ui' => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud' => true,
			'rewrite' => false,
		);
		
		$this->wp->call("register_taxonomy", "avorgMediaIds", array('avorgcontentbits'), $args);
	}
	
	public function addIdentifierMetaBox()
	{
		$args = array(
			'avorgMetaBox',
			'Identifier',
			array($this, "renderIdentifierMetaBox"),
			'avorgcontentbits',
			'side',
			'default'
		);
		
		$this->wp->call("add_meta_box", ...$args);
	}
	
	public function renderIdentifierMetaBox()
	{
		$postId = $this->wp->call("get_the_ID");
		$savedValue = $this->wp->call("get_post_meta", $postId, "_avorgBitIdentifier", true);
		
		$this->twig->render("identifierMetaBox.twig", ["savedIdentifier" => $savedValue]);
	}
	
	public function saveIdentifierMetaBox()
	{
		if (!isset($_POST["avorgBitIdentifier"])) return;
		
		$postId = $this->wp->call("get_the_ID");
		$this->wp->call(
			"update_post_meta",
			$postId,
			"_avorgBitIdentifier",
			$_POST["avorgBitIdentifier"]
		);
	}
	
	private function addShortcode()
	{
		$this->wp->call("add_shortcode", "avorg-bits", [$this, "renderShortcode"]);
	}
	
	public function renderShortcode($attributes)
	{
		$presentationId = $this->wp->call('get_query_var', 'presentation_id');
		$posts = $this->getBits($attributes['id'], $presentationId)
			?: $this->getBits($attributes['id']);
		$postIndex = $this->php->array_rand($posts);
		$post = $posts[$postIndex];
		
		return $post->post_content;
	}
	
	private function getBits($identifier, $presentationId = null)
	{
		$taxQuery = ($presentationId) ? [
			'tax_query' => [
				[
					'taxonomy' => 'avorgMediaIds',
					'field' => 'slug',
					'terms' => $presentationId
				]
			]
		] : [];
		
		return $this->wp->call("get_posts", [
			'posts_per_page' => -1,
			'post_type' => 'avorgContentBits',
			'meta_query' => [
				[
					'key' => '_avorgBitIdentifier',
					'value' => $identifier
				]
			]] + $taxQuery);
	}
}