<?php
    /**
     * Tornado Theme - Options Tab
     * @package Tornado Wordpress
    */
    //======= Exit if Try to Access Directly =======//
    defined('ABSPATH') || exit;
?>

<!-- Page Head -->
<div class="page-head">
    <h1><?php echo __('General Settings','tornado'); ?></h1>
</div>

<!-- Panel Block -->
<div class="options-panel">
    <!-- Panel Title -->
    <h2 class="panel-title"><?php echo __('Design Options','tornado'); ?></h2>
    <div class="row no-gutter">
        <!-- Control Item -->
        <div class="control-item col-12 col-m-6 <?php if (is_rtl()) { echo 'rtl'; }?>">
            <label for="theme_logo"><?php echo __('Theme Logo','tornado'); ?></label>
            <!-- Image Uploader -->  
            <div class="media-uploader-control">
                <input type="hidden" name="theme_logo" class="uploader-input" placeholder="<?php echo __('Image URL','tornado'); ?>">
                <?php if (!get_option('theme_logo')) : ?>
                <input type="button" class="uploader-btn button button-primary" value="<?php echo __('Select', 'tornado'); ?>">
                <?php else : ?>
                <a href="<?php echo get_option('theme_logo'); ?>" class="button button-warning button-prev" target="_blank"><?php echo __('Preview', 'tornado'); ?></a>
                <input type="button" class="uploader-btn button button-primary" value="<?php echo __('Change', 'tornado'); ?>">
                <?php endif; ?>
            </div>
        </div>
        <!-- // Control Item -->
    </div>
</div>
<!-- // Panel Block -->