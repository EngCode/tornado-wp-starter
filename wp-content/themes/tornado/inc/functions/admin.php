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
     * 05 - Design Options Action Output
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
        //=== Code Mirror CSS ===//
        wp_enqueue_style('codemirror-style', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.35.0/codemirror.css', false , NULL , 'all');
        wp_enqueue_style('codemirror-theme', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.54.0/theme/xq-light.min.css', false , NULL , 'all');
    };

    //======== Register Javascript Files ========//
    function tornado_admin_js() {
        //=====> Include Media Uploader JS <=====//
        wp_enqueue_media();
        wp_enqueue_script('media-uploader');
        //=====> Include Tornado JS File <=====//
        wp_enqueue_script('tornado_js', get_template_directory_uri() . '/dist/js/tornado.min.js', false , NULL , true);
        //=== Color Picker ===//
        wp_enqueue_script('vanilla-picker', 'https://unpkg.com/vanilla-picker@2', false , NULL , true);
    };

    //==== Run Assets Files By Gutenberg Hooks ====//
    add_action ('admin_enqueue_scripts','tornado_admin_css');
    add_action ('admin_enqueue_scripts','tornado_admin_js');

    //==== Design Options Action Output =====//
    add_action( 'wp_head', 'tornado_design_colors');
    function tornado_design_colors() {?>
            <style type="text/css">
                /*======== Root CSS Variables ========*/
                :root {
                    /*======== Primary Color ========*/
                    --primary-color: <?php echo get_option('primary_color'); ?>;
                    --primary-color-hover: <?php echo get_option('primary_color_hover'); ?>;
                    /*======== Secondary Color ========*/
                    --secondary-color: <?php echo get_option('secondary_color'); ?>;
                    --secondary-color-hover: <?php echo get_option('secondary_color_hover'); ?>;
                    /*======== TXT Color ========*/
                    --typo-color:<?php echo get_option('typo_color'); ?>;
                    /*========== Typography Font Family ==========*/
                    /* font family for Head's and Titles */
                    --primary-font:<?php echo get_option('primary_font'); ?>;
                    /* font family for Paragraph and Small Text */
                    --secondary-font:<?php echo get_option('secondary_font'); ?>;
                    /*========== Typography Base Font Sizes ==========*/
                    /* Base Font Size For Large Screens */
                    --base-l-size:<?php echo get_option('base_l_size'); ?>;
                    /* Base Font Size For Medium Screens */
                    --base-m-size:<?php echo get_option('base_m_size'); ?>;
                    /* Base Font Size For Small Screens */
                    --base-s-size:<?php echo get_option('base_s_size'); ?>;
                    /* Base Line Height */
                    --base-line-height:<?php echo get_option('base_line_height'); ?>;
                    /*========== Typography Font Weight ==========*/
                    /* Normal Font Weight */
                    --normal-weight:<?php echo get_option('normal_weight'); ?>;
                    /* Medium Font Weight */
                    --medium-weight:<?php echo get_option('medium_weight'); ?>;
                    /* Bold/Bolder Font Weight */
                    --strong-weight:<?php echo get_option('strong_weight'); ?>;
                }
            </style>
        <?php
    }
?>