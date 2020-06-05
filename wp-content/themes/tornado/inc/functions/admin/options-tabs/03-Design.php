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
    <h1><?php echo __('Design Options','tornado'); ?></h1>
</div>

<!-- Panel Block -->
<div class="options-panel">
    <!-- Panel Title -->
    <h2 class="panel-title"><?php echo __('Logo and Colors','tornado'); ?></h2>
    <div class="row no-gutter">
        <!-- Control Item -->
        <div class="control-item col-12 col-l-6 <?php if (is_rtl()) { echo 'rtl'; }?>">
            <div class="content-box">
                <label for="theme_logo"><?php echo __('Theme Logo','tornado'); ?></label>
                <!-- Image Uploader -->  
                <div class="media-uploader-control iconic">
                    <?php 
                        if (!get_option('theme_logo')) {
                            $logo_value = 'https://via.placeholder.com/320x50?text=PLACEHOLDER';
                        } else {
                            $logo_value = get_option('theme_logo');
                        }
                    ?>
                    <!-- Image Input -->
                    <input type="hidden" name="theme_logo" class="uploader-input" value="<?php echo $logo_value; ?>">
                    <!-- Image Preview and Button -->
                    <img src="<?php echo $logo_value; ?>" alt="" class="image-prev">
                    <button type="button" class="uploader-btn button button-primary ti-play-work-up"></button>
                </div>
            </div>
        </div>
        <!-- Control Item -->
        <div class="control-item col-12 col-l-6 <?php if (is_rtl()) { echo 'rtl'; }?>">
            <div class="content-box">
                <label for="theme_logo_mobile"><?php echo __('Theme Logo Mobile','tornado'); ?></label>
                <!-- Image Uploader -->  
                <div class="media-uploader-control iconic">
                    <?php 
                        if (!get_option('theme_logo_mobile')) {
                            $logo_mobile_value = 'https://via.placeholder.com/320x50?text=PLACEHOLDER';
                        } else {
                            $logo_mobile_value = get_option('theme_logo_mobile');
                        }
                    ?>
                    <!-- Image Input -->
                    <input type="hidden" name="theme_logo_mobile" class="uploader-input" value="<?php echo $logo_mobile_value; ?>">
                    <!-- Image Preview and Button -->
                    <img src="<?php echo $logo_mobile_value; ?>" alt="" class="image-prev">
                    <button type="button" class="uploader-btn button button-primary ti-play-work-up"></button>
                </div>
            </div>
        </div>
        <!-- Control Item -->
        <div class="control-item col-12 col-l-6 <?php if (is_rtl()) { echo 'rtl'; }?>">
            <div class="content-box">
                <label for="primary_color">
                    <?php echo __('Primary Color','tornado'); ?>
                    <span class="ti-help-mark help-btn" data-txt="<?php echo __('Theme Design Primary Color','tornado'); ?>"></span>
                </label>
                <div class="color-picker">
                    <?php $color_val = get_option('primary_color_hover'); ?>
                    <input type="text" name="primary_color" value="<?php echo $color_val; ?>" placeholder="<?php echo $color_val; ?>">
                    <div class="color-prev" style="background-color:<?php echo $color_val; ?>"></div>
                </div>
            </div>
        </div>
        <!-- Control Item -->
        <div class="control-item col-12 col-l-6 <?php if (is_rtl()) { echo 'rtl'; }?>">
            <div class="content-box">
                <label for="primary_color_hover">
                    <?php echo __('Primary Hover','tornado'); ?>
                    <span class="ti-help-mark help-btn" data-txt="<?php echo __('Theme Design Primary Hover Effect Color','tornado'); ?>"></span>
                </label>
                <div class="color-picker">
                    <?php $color_val = get_option('primary_color_hover'); ?>
                    <input type="text" name="primary_color_hover" value="<?php echo $color_val; ?>" placeholder="<?php echo $color_val; ?>">
                    <div class="color-prev" style="background-color:<?php echo $color_val; ?>"></div>
                </div>
            </div>
        </div>
        <!-- Control Item -->
        <div class="control-item col-12 col-l-6 <?php if (is_rtl()) { echo 'rtl'; }?>">
            <div class="content-box">
                <label for="secondary_color">
                    <?php echo __('Secondary Color','tornado'); ?>
                    <span class="ti-help-mark help-btn" data-txt="<?php echo __('Theme Design Secondary Color','tornado'); ?>"></span>
                </label>
                <div class="color-picker">
                    <?php $color_val = get_option('secondary_color'); ?>
                    <input type="text" name="secondary_color" value="<?php echo $color_val; ?>" placeholder="<?php echo $color_val; ?>">
                    <div class="color-prev" style="background-color:<?php echo $color_val; ?>"></div>
                </div>
            </div>
        </div>
        <!-- Control Item -->
        <div class="control-item col-12 col-l-6 <?php if (is_rtl()) { echo 'rtl'; }?>">
            <div class="content-box">
                <label for="secondary_color_hover">
                    <?php echo __('Secondary Hover','tornado'); ?>
                    <span class="ti-help-mark help-btn" data-txt="<?php echo __('Theme Design Secondary Hover Effect Color','tornado'); ?>"></span>
                </label>
                <div class="color-picker">
                    <?php $color_val = get_option('secondary_color_hover'); ?>
                    <input type="text" name="secondary_color_hover" value="<?php echo $color_val; ?>" placeholder="<?php echo $color_val; ?>">
                    <div class="color-prev" style="background-color:<?php echo $color_val; ?>"></div>
                </div>
            </div>
        </div>
        <!-- // Control Item -->
    </div>
</div>
<!-- // Panel Block -->

<!-- Panel Block -->
<div class="options-panel">
    <!-- Panel Title -->
    <h2 class="panel-title"><?php echo __('Font Options','tornado'); ?></h2>
    <div class="row no-gutter">
        <!-- Control Item -->
        <div class="control-item col-12 col-l-6 <?php if (is_rtl()) { echo 'rtl'; }?>">
            <div class="content-box">
                <label for="primary_font">
                    <?php echo __('Headers Font','tornado'); ?>
                    <span class="ti-help-mark help-btn" data-txt="<?php echo __('Headers and Titles Font Family','tornado'); ?>"></span>
                </label>
                <input type="text" name="primary_font" placeholder="<?php echo "'Roboto', sans-serif"; ?>" value="<?php echo get_option('primary_font'); ?>">
            </div>
        </div>
        <!-- Control Item -->
        <div class="control-item col-12 col-l-6 <?php if (is_rtl()) { echo 'rtl'; }?>">
            <div class="content-box">
                <label for="secondary_font">
                    <?php echo __('Normal Font','tornado'); ?>
                    <span class="ti-help-mark help-btn" data-txt="<?php echo __('Normal Text Font Family','tornado'); ?>"></span>
                </label>
                <input type="text" name="secondary_font" placeholder="<?php echo "'Roboto', sans-serif"; ?>" value="<?php echo get_option('secondary_font'); ?>">
            </div>
        </div>
        <!-- Control Item -->
        <div class="control-item col-12 col-l-6 <?php if (is_rtl()) { echo 'rtl'; }?>">
            <div class="content-box">
                <label for="typo_color">
                    <?php echo __('Text Color','tornado'); ?>
                    <span class="ti-help-mark help-btn" data-txt="<?php echo __('Theme Design Text Color','tornado'); ?>"></span>
                </label>
                <div class="color-picker">
                    <?php $color_val = get_option('typo_color'); ?>
                    <input type="text" name="typo_color" value="<?php echo $color_val; ?>" placeholder="<?php echo $color_val; ?>">
                    <div class="color-prev" style="background-color:<?php echo $color_val; ?>"></div>
                </div>
            </div>
        </div>
        <!-- Control Item -->
        <div class="control-item col-12 col-l-6 <?php if (is_rtl()) { echo 'rtl'; }?>">
            <div class="content-box">
                <label for="base_l_size">
                    <?php echo __('Font Size','tornado'); ?>
                    <span class="ti-help-mark help-btn" data-txt="<?php echo __('Desktop Base Font Size in px','tornado'); ?>"></span>
                </label>
                <input type="text" name="base_l_size" placeholder="<?php echo '16px'; ?>" value="<?php echo get_option('base_l_size'); ?>">
            </div>
        </div>
        <!-- Control Item -->
        <div class="control-item col-12 col-l-6 <?php if (is_rtl()) { echo 'rtl'; }?>">
            <div class="content-box">
                <label for="base_m_size">
                    <?php echo __('Tablet Font Size','tornado'); ?>
                    <span class="ti-help-mark help-btn" data-txt="<?php echo __('Tablet Base Font Size in px','tornado'); ?>"></span>
                </label>
                <input type="text" name="base_m_size" placeholder="<?php echo '16px'; ?>" value="<?php echo get_option('base_m_size'); ?>">
            </div>
        </div>
        <!-- Control Item -->
        <div class="control-item col-12 col-l-6 <?php if (is_rtl()) { echo 'rtl'; }?>">
            <div class="content-box">
                <label for="base_s_size">
                    <?php echo __('Mobile Font Size','tornado'); ?>
                    <span class="ti-help-mark help-btn" data-txt="<?php echo __('Mobile Base Font Size in px','tornado'); ?>"></span>
                </label>
                <input type="text" name="base_s_size" placeholder="<?php echo '16px'; ?>" value="<?php echo get_option('base_s_size'); ?>">
            </div>
        </div>
        <!-- Control Item -->
        <div class="control-item col-12 col-l-6 <?php if (is_rtl()) { echo 'rtl'; }?>">
            <div class="content-box">
                <label for="base_line_height">
                    <?php echo __('Font Line-Height','tornado'); ?>
                    <span class="ti-help-mark help-btn" data-txt="<?php echo __('Font Base Line Hight in points or precentage','tornado'); ?>"></span>
                </label>
                <input type="text" name="base_line_height" placeholder="<?php echo '1.658'; ?>" value="<?php echo get_option('base_line_height'); ?>">
            </div>
        </div>
        <!-- Control Item -->
        <div class="control-item col-12 col-l-6 <?php if (is_rtl()) { echo 'rtl'; }?>">
            <div class="content-box">
                <label for="normal_weight">
                    <?php echo __('Font Normal Weight','tornado'); ?>
                    <span class="ti-help-mark help-btn" data-txt="<?php echo __('Font Normal/Regular Weight in Numbers.','tornado'); ?>"></span>
                </label>
                <input type="text" name="normal_weight" placeholder="<?php echo '400'; ?>" value="<?php echo get_option('normal_weight'); ?>">
            </div>
        </div>
        <!-- Control Item -->
        <div class="control-item col-12 col-l-6 <?php if (is_rtl()) { echo 'rtl'; }?>">
            <div class="content-box">
                <label for="medium_weight">
                    <?php echo __('Font Medium Weight','tornado'); ?>
                    <span class="ti-help-mark help-btn" data-txt="<?php echo __('Font Medium/Semi-Bold Weight in Numbers.','tornado'); ?>"></span>
                </label>
                <input type="text" name="medium_weight" placeholder="<?php echo '500'; ?>" value="<?php echo get_option('medium_weight'); ?>">
            </div>
        </div>
        <!-- Control Item -->
        <div class="control-item col-12 col-l-6 <?php if (is_rtl()) { echo 'rtl'; }?>">
            <div class="content-box">
                <label for="strong_weight">
                    <?php echo __('Font Bold Weight','tornado'); ?>
                    <span class="ti-help-mark help-btn" data-txt="<?php echo __('Font Bold Weight in Numbers.','tornado'); ?>"></span>
                </label>
                <input type="text" name="strong_weight" placeholder="<?php echo '700'; ?>" value="<?php echo get_option('strong_weight'); ?>">
            </div>
        </div>
        <!-- // Control Item -->
    </div>
</div>
<!-- // Panel Block -->