<?php
    /**
     * Tornado Theme - Custom Header Component
     * @package Tornado Wordpress
    */

    //======= Exit if Try to Access Directly =======//
    defined('ABSPATH') || exit;
?>
<!-- Main Header -->
<header class="tornado-header" data-sticky="absolute">
    <div class="container">
        <!-- Logo -->
        <a href="<?php echo site_url();?>" class="logo"> <img src="<?php echo get_option('theme_logo');?>" alt="<?php echo bloginfo('name');?>"> </a>
        <!-- Navigation Menu -->
        <div class="navigation-menu" data-id="main-menu">
            <?php echo wp_nav_menu(array(
                'menu' => 'main-menu',
                'theme_location' => 'main-menu',
                'container' => false,
                'container_class' => false,
            ));?>
        </div>
        <!-- Action Buttons -->
        <div class="action-btns social-btns">
            <a href="https://wa.me/<?php echo get_option('whatsapp_number');?>" class="ti-whatsapp whatsapp-bg"></a>
            <a href="<?php echo get_option('facebook_url');?>" class="ti-facebook"></a>
            <a href="<?php echo get_option('twitter_url');?>" class="ti-twitter"></a>
            <a href="<?php echo get_option('instagram_url');?>" class="ti-instagram"></a>
            <!-- Categories Mobile Button -->
            <a href="#" class="icon-btn tooltip-bottom menu-btn ti-menu-round" title="<?php echo pll__('القائمة الرئيسية','tornado');?>" data-id="main-menu"></a>
        </div>
        <!-- // Action Buttons -->
    </div>
</header>
<!-- // Main Header -->