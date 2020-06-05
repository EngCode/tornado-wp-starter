<?php
    /**
     * Template Name: Default Rich Content
     * this template for displaying Rich Content for any of this pages single.php or page.php
     * @package Tornado Wordpress
    */
?>

<!-- Head Tag -->
<?php get_header(); ?>
<!-- Header -->
<?php get_template_part('inc/template-parts/components/header'); ?>

<!-- Page Content -->
<div class="container page-content">
    <!-- Grid -->
    <div class="row last-row">
        <!-- Content Side -->
        <div class="col-12 col-m-8 col-l-9">
            <?php 
                //====== if Have Post Content =======//
                if (have_posts()) : 
                    while (have_posts()) : the_post();
                        //======> Blog Details <==========//
                        get_template_part('inc/template-parts/blogs/blog','details');
                        //======> Blog Tags <=========//
                        get_template_part('inc/template-parts/blogs/blog','tags');
                        //======> Comments <=========//
                        comments_template();
                    //====== End if Have Post Content =======//
                    endwhile; 
                endif;
            ?>
        </div>
        <!-- Widgets Side -->
        <div class="col-12 col-m-4 col-l-3">

        </div>
    </div>
    <!-- // Grid -->
</div>
<!-- // Page Content -->

<!-- Custom Footer --> 
<?php get_template_part('inc/template-parts/components/footer'); ?>
<!-- Footer -->
<?php get_footer(); ?>