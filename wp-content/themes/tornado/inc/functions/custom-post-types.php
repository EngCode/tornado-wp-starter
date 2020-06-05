<?php
    /**
     * Tornado Custom Post Types
     * @package Tornado Wordpress
     * Registering Custom Posts Types or Custom Content Modules.
     * 
     * ========> Reference by Comments <=======
     * 00 - Blog Post Type
     * 02 - Custom Taxonomy Example
    */
    //======= Exit if Try to Access Directly =======//
    defined('ABSPATH') || exit;

    //======== Blog Post Type ========//
    function blog_cpt() {
        //===== CPT Options ====//
        $args = array(
            'label'         => __('Blog', 'tornado'),
            'name'          => 'post',
            'singular_name' => 'post',
            'menu_position' => 3,
            'menu_icon'     => 'dashicons-format-aside',
            'public'        => true,
            'has_archive'   => true,
            'show_in_rest'  => true,
            'supports'      => array( 'title', 'editor' , 'excerpt', 'thumbnail', 'revisions'),
            'taxonomies'    => array('tag','category'),
        );

        register_post_type('post', $args);
    }

    add_action( 'init', 'blog_cpt' );

    //======== Custom Taxonomy Example ========//
	function tax_name() {
		//===== Taxonomies Options ====//
		$args = array(
			'label'             => __('Categories', 'tornado'),
            'rewrite'           => array('slug' => 'docementation-categories' ),
            'public'            => true,
			'hierarchical'      => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'query_var'         => true,
		);

		register_taxonomy( 'docementation-categories', array( 'docementation' ), $args );
    }

	//==== Hooking The Custom Taxonomies ====//
	// add_action( 'init', 'tax_name', 0 );
?>