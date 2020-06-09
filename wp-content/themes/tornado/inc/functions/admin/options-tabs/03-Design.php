<?php
    /**
     * Tornado Theme - Options Tab
     * @package Tornado Wordpress
    */
    //======= Exit if Try to Access Directly =======//
    defined('ABSPATH') || exit;
    if (get_option('google_fonts') === '1') {
        //====== Google Fonts API Info =======//
        $google_fonts_api = "AIzaSyASxx2HUwsHJ0gXmEi5V1xJyBI6WeTq8Hk";
        $google_fonts_url = 'https://www.googleapis.com/webfonts/v1/webfonts?key=' . $google_fonts_api;
        $google_fonts_args = array(
            'timeout'     => 15,
            'redirection' => 10,
            'httpversion' => '1.0',
        );
        //====== Fetch Google Fonts =======//
        $google_fonts_response = wp_remote_get( $google_fonts_url, $google_fonts_args );
        //====== Decode Google Fonts Json =======//
        $google_fonts_body = json_decode($google_fonts_response['body']);
        //====== get the Fonts List Array =======//
        $font_list = $google_fonts_body->items;
    }
?>

<!-- Page Head -->
<div class="page-head">
    <h1><?php echo __('Design Options','tornado'); ?></h1>
</div>

<?php
    //=========== Google Fonts Error ===========//
    if (get_option('google_fonts') === '1' && is_wp_error($google_fonts_response)) :
        echo '<div class="alert danger">'.esc_html( $google_fonts_response->get_error_message()).'</div>';
    endif;
?>

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
                    <?php $color_val = get_option('primary_color'); ?>
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
                    <?php echo __('Google Fonts','tornado'); ?>
                    <span class="ti-help-mark help-btn" data-txt="<?php echo __('Enable/Disable Google Fonts','tornado'); ?>"></span>
                </label>
                <label class="toggle-button">
                    <input type="checkbox" name="google_fonts" id="google-fonts-status" value="1" <?php checked(1, get_option('google_fonts'), true); ?>>
                    <span></span>
                </label>
            </div>
        </div>
        <!-- Control Item -->
        <div class="control-item col-12 col-l-6 <?php if (get_option('google_fonts') !== '1') { echo 'hidden'; } ?> google-fonts-controler <?php if (is_rtl()) { echo 'rtl'; }?>">
            <div class="content-box">
                <label for="primary_font">
                    <?php echo __('Headers Font','tornado'); ?>
                    <span class="ti-help-mark help-btn" data-txt="<?php echo __('Headers and Titles Font Family','tornado'); ?>"></span>
                </label>
                <select name="primary_font" class="advanced-select">
                    <?php
                        if (get_option('google_fonts') === '1') :
                            foreach ($font_list as $font ) :
                                //====== Check if Selected ======//
                                if ($font->family == get_option('primary_font')) {$is_selected = 'selected';} 
                                else {$is_selected = '';}
                                //====== Print Font Item ======//
                                echo '<option value="'.esc_attr($font->family).'" '.$is_selected.'>'.esc_html( $font->family).'</option>';
                            endforeach;
                        endif;
                    ?>
                </select>
            </div>
        </div>
        <!-- Control Item -->
        <div class="control-item col-12 col-l-6 <?php if (get_option('google_fonts') !== '1') { echo 'hidden'; } ?> google-fonts-controler <?php if (is_rtl()) { echo 'rtl'; }?>">
            <div class="content-box">
                <label for="secondary_font">
                    <?php echo __('Normal Font','tornado'); ?>
                    <span class="ti-help-mark help-btn" data-txt="<?php echo __('Normal Text Font Family','tornado'); ?>"></span>
                </label>
                <select name="secondary_font" class="advanced-select">
                    <?php
                        if (get_option('google_fonts') === '1') :
                            foreach ($font_list as $font ) :
                                //====== Check if Selected ======//
                                if ($font->family == get_option('secondary_font')) {$is_selected = 'selected';} 
                                else {$is_selected = '';}
                                //====== Print Font Item ======//
                                echo '<option value="'.esc_attr($font->family).'" '.$is_selected.'>'.esc_html( $font->family).'</option>';
                            endforeach;
                        endif;
                    ?>
                </select>
            </div>
        </div>
        <!-- Control Item -->
        <div class="control-item col-12 col-l-6 <?php if (get_option('google_fonts') !== '1') { echo 'hidden'; } ?> google-fonts-controler <?php if (is_rtl()) { echo 'rtl'; }?>">
            <div class="content-box">
                <label for="primary_font_rtl">
                    <?php echo __('Headers Font RTL','tornado'); ?>
                    <span class="ti-help-mark help-btn" data-txt="<?php echo __('Headers and Titles Font Family','tornado'); ?>"></span>
                </label>
                <select name="primary_font_rtl" class="advanced-select">
                    <?php
                        if (get_option('google_fonts') === '1') :
                            foreach ($font_list as $font ) :
                                //====== Check if Selected ======//
                                if ($font->family == get_option('primary_font_rtl')) {$is_selected = 'selected';} 
                                else {$is_selected = '';}
                                //====== Print Font Item ======//
                                echo '<option value="'.esc_attr($font->family).'" '.$is_selected.'>'.esc_html( $font->family).'</option>';
                            endforeach;
                        endif;
                    ?>
                </select>
            </div>
        </div>
        <!-- Control Item -->
        <div class="control-item col-12 col-l-6 <?php if (get_option('google_fonts') !== '1') { echo 'hidden'; } ?> google-fonts-controler <?php if (is_rtl()) { echo 'rtl'; }?>">
            <div class="content-box">
                <label for="secondary_font_rtl">
                    <?php echo __('Normal Font RTL','tornado'); ?>
                    <span class="ti-help-mark help-btn" data-txt="<?php echo __('Normal Text Font Family','tornado'); ?>"></span>
                </label>
                <select name="secondary_font_rtl" class="advanced-select">
                    <?php
                        if (get_option('google_fonts') === '1') :
                            foreach ($font_list as $font ) :
                                //====== Check if Selected ======//
                                if ($font->family == get_option('secondary_font_rtl')) {$is_selected = 'selected';} 
                                else {$is_selected = '';}
                                //====== Print Font Item ======//
                                echo '<option value="'.esc_attr($font->family).'" '.$is_selected.'>'.esc_html( $font->family).'</option>';
                            endforeach;
                        endif;
                    ?>
                </select>
            </div>
        </div>
        <!-- Control Item -->
        <div class="control-item col-12 col-l-6 <?php if (get_option('google_fonts') === '1') { echo 'hidden'; } ?> custom-fonts-controler <?php if (is_rtl()) { echo 'rtl'; }?>">
            <div class="content-box">
                <label for="custom_primary_font">
                    <?php echo __('Headers Font','tornado'); ?>
                    <span class="ti-help-mark help-btn" data-txt="<?php echo __('Headers and Titles Font Family','tornado'); ?>"></span>
                </label>
                <input type="text" name="custom_primary_font" value="<?php echo get_option('custom_primary_font'); ?>">
            </div>
        </div>
        <!-- Control Item -->
        <div class="control-item col-12 col-l-6 <?php if (get_option('google_fonts') === '1') { echo 'hidden'; } ?> custom-fonts-controler <?php if (is_rtl()) { echo 'rtl'; }?>">
            <div class="content-box">
                <label for="custom_secondary_font">
                    <?php echo __('Normal Font','tornado'); ?>
                    <span class="ti-help-mark help-btn" data-txt="<?php echo __('Normal Text Font Family','tornado'); ?>"></span>
                </label>
                <input type="text" name="custom_secondary_font" value="<?php echo get_option('custom_secondary_font'); ?>">
            </div>
        </div>
        <!-- Control Item -->
        <div class="control-item col-12 col-l-6 <?php if (get_option('google_fonts') === '1') { echo 'hidden'; } ?> custom-fonts-controler <?php if (is_rtl()) { echo 'rtl'; }?>">
            <div class="content-box">
                <label for="primary_font_rtl">
                    <?php echo __('Headers Font RTL','tornado'); ?>
                    <span class="ti-help-mark help-btn" data-txt="<?php echo __('Headers and Titles Font Family RTL','tornado'); ?>"></span>
                </label>
                <input type="text" name="custom_primary_font_rtl" value="<?php echo get_option('custom_primary_font_rtl'); ?>">
            </div>
        </div>
        <!-- Control Item -->
        <div class="control-item col-12 col-l-6 <?php if (get_option('google_fonts') === '1') { echo 'hidden'; } ?> custom-fonts-controler <?php if (is_rtl()) { echo 'rtl'; }?>">
            <div class="content-box">
                <label for="custom_secondary_font_rtl">
                    <?php echo __('Normal Font RTL','tornado'); ?>
                    <span class="ti-help-mark help-btn" data-txt="<?php echo __('Normal Text Font Family RTL','tornado'); ?>"></span>
                </label>
                <input type="text" name="custom_secondary_font_rtl" value="<?php echo get_option('custom_secondary_font_rtl'); ?>">
            </div>
        </div>
        <!-- Control Item -->
        <div class="control-item col-12 col-l-6 <?php if (is_rtl()) { echo 'rtl'; }?>">
            <div class="content-box">
                <label for="normal_weight">
                    <?php echo __('Font Normal Weight','tornado'); ?>
                    <span class="ti-help-mark help-btn" data-txt="<?php echo __('Font Normal/Regular Weight.','tornado'); ?>"></span>
                </label>
                <select class="chevron-select" name="normal_weight">
                    <option value="100" <?php if (get_option('normal_weight') == '100') { echo 'selected'; } ?>>Ultra light</option>
                    <option value="200" <?php if (get_option('normal_weight') == '200') { echo 'selected'; } ?>>Light</option>
                    <option value="300" <?php if (get_option('normal_weight') == '300') { echo 'selected'; } ?>>Book</option>
                    <option value="400" <?php if (get_option('normal_weight') == '400') { echo 'selected'; } ?>>Regular</option>
                    <option value="500" <?php if (get_option('normal_weight') == '500') { echo 'selected'; } ?>>Medium</option>
                    <option value="600" <?php if (get_option('normal_weight') == '600') { echo 'selected'; } ?>>Semi-bold</option>
                    <option value="700" <?php if (get_option('normal_weight') == '700') { echo 'selected'; } ?>>Bold</option>
                    <option value="800" <?php if (get_option('normal_weight') == '800') { echo 'selected'; } ?>>Heavy</option>
                    <option value="900" <?php if (get_option('normal_weight') == '900') { echo 'selected'; } ?>>Heavy-black</option>
                </select>
            </div>
        </div>
        <!-- Control Item -->
        <div class="control-item col-12 col-l-6 <?php if (is_rtl()) { echo 'rtl'; }?>">
            <div class="content-box">
                <label for="medium_weight">
                    <?php echo __('Font Medium Weight','tornado'); ?>
                    <span class="ti-help-mark help-btn" data-txt="<?php echo __('Font Medium/Semi-Bold Weight.','tornado'); ?>"></span>
                </label>
                <select class="chevron-select" name="normal_weight">
                    <option value="100" <?php if (get_option('medium_weight') == '100') { echo 'selected'; } ?>>Ultra light</option>
                    <option value="200" <?php if (get_option('medium_weight') == '200') { echo 'selected'; } ?>>Light</option>
                    <option value="300" <?php if (get_option('medium_weight') == '300') { echo 'selected'; } ?>>Book</option>
                    <option value="400" <?php if (get_option('medium_weight') == '400') { echo 'selected'; } ?>>Regular</option>
                    <option value="500" <?php if (get_option('medium_weight') == '500') { echo 'selected'; } ?>>Medium</option>
                    <option value="600" <?php if (get_option('medium_weight') == '600') { echo 'selected'; } ?>>Semi-bold</option>
                    <option value="700" <?php if (get_option('medium_weight') == '700') { echo 'selected'; } ?>>Bold</option>
                    <option value="800" <?php if (get_option('medium_weight') == '800') { echo 'selected'; } ?>>Heavy</option>
                    <option value="900" <?php if (get_option('medium_weight') == '900') { echo 'selected'; } ?>>Heavy-black</option>
                </select>
            </div>
        </div>
        <!-- Control Item -->
        <div class="control-item col-12 col-l-6 <?php if (is_rtl()) { echo 'rtl'; }?>">
            <div class="content-box">
                <label for="strong_weight">
                    <?php echo __('Font Bold Weight','tornado'); ?>
                    <span class="ti-help-mark help-btn" data-txt="<?php echo __('Font Bold Weight.','tornado'); ?>"></span>
                </label>
                <select class="chevron-select" name="normal_weight">
                    <option value="100" <?php if (get_option('strong_weight') == '100') { echo 'selected'; } ?>>Ultra light</option>
                    <option value="200" <?php if (get_option('strong_weight') == '200') { echo 'selected'; } ?>>Light</option>
                    <option value="300" <?php if (get_option('strong_weight') == '300') { echo 'selected'; } ?>>Book</option>
                    <option value="400" <?php if (get_option('strong_weight') == '400') { echo 'selected'; } ?>>Regular</option>
                    <option value="500" <?php if (get_option('strong_weight') == '500') { echo 'selected'; } ?>>Medium</option>
                    <option value="600" <?php if (get_option('strong_weight') == '600') { echo 'selected'; } ?>>Semi-bold</option>
                    <option value="700" <?php if (get_option('strong_weight') == '700') { echo 'selected'; } ?>>Bold</option>
                    <option value="800" <?php if (get_option('strong_weight') == '800') { echo 'selected'; } ?>>Heavy</option>
                    <option value="900" <?php if (get_option('strong_weight') == '900') { echo 'selected'; } ?>>Heavy-black</option>
                </select>
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
        <!-- // Control Item -->
    </div>
</div>
<!-- // Panel Block -->