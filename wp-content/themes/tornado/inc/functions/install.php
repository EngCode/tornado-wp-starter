<?php
    /**
     * Tornado Theme Initials
     * @package Tornado Wordpress
     * Setup theme defaults and registers support for various WordPress features.
     * 
     * Note that tornado_setup function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     * 
     * ========> Reference by Comments <=======
     * 00 - Default Meta Options
     * 01 - Default Design Global Options
     * 02 - Default Design Colors Options
     * 03 - Default Design Fonts Options
     * 
    */

    //======= Exit if Try to Access Directly =======//
    defined('ABSPATH') || exit;

    if (!function_exists('tornado_setup') ) :
        function tornado_install() {
            //=====> Default Meta Options <=====//
            update_option('meta_keywords', 'كلمة , مفتاحية , تجريبية , Tornado UI');
            update_option('meta_graph_cover', get_template_directory_uri() . '/screenshot.png');
            update_option('meta_copyrights', 'جميع الحقوق محفوظة لــ Tornado UI');

            //=====> Default Design Global Options <=====//
            update_option('theme_logo', get_template_directory_uri() . '/dist/img/logo.png' );

            //=====> Default Design Colors Options <=====//
            update_option('primary_color', '#4166d6');
            update_option('primary_color_hover', '#2e4dc9');
            update_option('secondary_color', '#de670f');
            update_option('secondary_color_hover', '#eaa711');
            update_option('reverse_color', '#FFFFFF');
            update_option('reverse_color_dark', '#1c1c1c');
            update_option('typo_color', '#555555');

            //=====> Default Design Fonts Options <=====//
            update_option('primary_font', "'DIN NEXT'");
            update_option('secondary_font', "'DIN NEXT'");
            update_option('base_l_size', '16px');
            update_option('base_m_size', '15px');
            update_option('base_s_size', '14px');
            update_option('base_line_height', '1.4');
            update_option('normal_weight', '400');
            update_option('medium_weight', '500');
            update_option('strong_weight', '600');
        }
    endif;

    add_action( 'after_setup_theme', 'tornado_setup' );
?>