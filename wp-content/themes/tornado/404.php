<?php
    /**
     * Template Name: Error 404
     * this template for displaying 404 pages (not found)
     * @package Tornado Wordpress
    */
?>

<!-- Head Tag -->
<?php get_header(); ?>
<!-- Header -->
<?php get_template_part('inc/template-parts/components/header'); ?>
<!-- Page Content -->
<div class="error404page">
    <img src="<?php echo get_template_directory_uri(); ?>/dist/img/404.png" alt="error-404">
</div>
<!-- Custom Footer --> 
<?php get_template_part('inc/template-parts/components/footer'); ?>
<!-- Footer -->
<?php get_footer(); ?> 