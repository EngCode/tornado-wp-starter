<?php
    /**
     * Tornado Theme Custom Options Pages
     * @package Tornado Wordpress
     * Creating Custom Options for Easy Theme and Design Control.
     * 
     * ========> Reference by Comments <=======
     * 00 - Pages Render Functions Files
     * 01 - Theme Options
     * 02 - Theme Menus Options
     * 03 - Admin CSS Include
     * 04 - Admin JS Include
     * 
    */

    //======= Exit if Try to Access Directly =======//
    defined('ABSPATH') || exit;
    
    //========> Pages Render Functions Files <=======//
    include( dirname(__FILE__) . '/admin/theme-options.php' );

    function tornado_options_pages () {
        //========> Theme Options <=======//
        add_menu_page(
            //===> Page Title
            __('Theme Options', 'tornado'),
            //===> Menu Item
            __('Theme Options', 'tornado'),
            //===> Capability
            'manage_options',
            //===> Menu Slug
            'theme-options',
            //===> Page Render Function
            'theme_options_render',
            //===> Menu Icon Path
            get_template_directory_uri() . '/inc/functions/admin/img/menu-icon.png',
            //===> Menu Position
            59
        );

        //========> Theme Menus Options <=======//
        add_submenu_page (
            'theme-options',
            __('Menus Settings', 'tornado' ),
            __('Menus Settings', 'tornado' ),
            'manage_options',
            'nav-menus.php',
            '',
            'dashicons-menu',1
        );
    }

    add_action( 'admin_menu', 'tornado_options_pages');

    //========> Admin CSS Include <=======//
    function tornado_admin_css() {
        //=== Tornado Icons CSS ===//
        wp_enqueue_style('tornado-icon', get_template_directory_uri() . '/dist/css/tornado-icons.css', false , NULL , 'all'); 
        //=== Admin CSS ===//
        wp_enqueue_style('tornado-admin-css', get_template_directory_uri() . '/dist/css/theme-options.css', false , NULL , 'all');
    };

    //======== Register Javascript Files ========//
    function tornado_admin_js() {
        //=====> Include Media Uploader JS <=====//
        wp_enqueue_media();
        wp_enqueue_script('media-uploader');
        //=====> Include Tornado JS File <=====//
        wp_enqueue_script('tornado_js', get_template_directory_uri() . '/dist/js/tornado.min.js', false , NULL , true);
        //=== Admin CSS ===//
        // wp_enqueue_script('tornado-admin-js', get_template_directory_uri() . '/dist/js/admin.min.js', false , NULL , true);
    };

    //==== Run Assets Files By Gutenberg Hooks ====//
    add_action ('admin_enqueue_scripts','tornado_admin_css');
    add_action ('admin_enqueue_scripts','tornado_admin_js');
?>