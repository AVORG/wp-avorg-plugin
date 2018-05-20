<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class ContentBits
{
	/** @var WordPress $wp */
	private $wp;
	
	public function __construct(WordPress $WordPress)
	{
		$this->wp = $WordPress;
	}
	
	public function init() {
		$labels = array(
			'name'                  => 'Content Bits',
			'singular_name'         => 'Content Bit',
			'menu_name'             => 'Content Bits',
			'name_admin_bar'        => 'Content Bits',
			'archives'              => 'Item Archives',
			'attributes'            => 'Item Attributes',
			'parent_item_colon'     => 'Parent Item:',
			'all_items'             => 'All Items',
			'add_new_item'          => 'Add New Item',
			'add_new'               => 'Add New',
			'new_item'              => 'New Item',
			'edit_item'             => 'Edit Item',
			'update_item'           => 'Update Item',
			'view_item'             => 'View Item',
			'view_items'            => 'View Items',
			'search_items'          => 'Search Item',
			'not_found'             => 'Not found',
			'not_found_in_trash'    => 'Not found in Trash',
			'featured_image'        => 'Featured Image',
			'set_featured_image'    => 'Set featured image',
			'remove_featured_image' => 'Remove featured image',
			'use_featured_image'    => 'Use as featured image',
			'insert_into_item'      => 'Insert into item',
			'uploaded_to_this_item' => 'Uploaded to this item',
			'items_list'            => 'Items list',
			'items_list_navigation' => 'Items list navigation',
			'filter_items_list'     => 'Filter items list',
		);
		
		$args = array(
			'label'                 => 'Content Bit',
			'description'           => 'Pieces of content that can be pulled in using a shortcode',
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'revisions', 'custom-fields' ),
			'taxonomies'            => array( 'category', 'post_tag' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 20,
			'menu_icon'             => 'dashicons-screenoptions',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => true,
			'publicly_queryable'    => true,
			'rewrite'               => false,
			'capability_type'       => 'post',
		);
		
		$this->wp->call( "register_post_type", "avorgContentBits", $args );
	}
}