<?php
    /**
     * Tornado Theme Custom Options Pages
     * @package Tornado Wordpress
     * Creating Custom Options for Easy Theme and Design Control.
     * 
     * ========> Reference by Comments <=======
     * 00 - Register Options
     * 01 - General => Meta Options
     * 02 - General => Custom Codes
     * 03 - Contact => Contact Info
     * 04 - Contact => Social
     * 05 - Design => Global
     * 06 - Design => Colors
     * 07 - Design => Fonts
     * 08 - Page Render Function
     * 
    */

    //======= Exit if Try to Access Directly =======//
    defined('ABSPATH') || exit;

    //========> Register Options <========//
    function register_theme_options() {
        //=========> General => Meta Options <=========//
        register_setting('tornado-options', 'meta_keywords');
        register_setting('tornado-options', 'meta_graph_cover');
        register_setting('tornado-options', 'meta_copyrights');
        //=========> General => Custom Codes <=========//
        register_setting('tornado-options', 'header_code');
        register_setting('tornado-options', 'body_code_start');
        register_setting('tornado-options', 'footer_code');
        //=========> Contact => Contact Info <=========//
        register_setting('tornado-options', 'phone_number');
        register_setting('tornado-options', 'phone_number_2nd');
        register_setting('tornado-options', 'contact_email');
        register_setting('tornado-options', 'contact_email_2nd');
        register_setting('tornado-options', 'whatsapp_number');
        register_setting('tornado-options', 'whatsapp_number_2nd');
        register_setting('tornado-options', 'branch_address');
        //=========> Contact => Social <=========//
        register_setting('tornado-options', 'facebook_url');
        register_setting('tornado-options', 'twitter_url');
        register_setting('tornado-options', 'instagram_url');
        register_setting('tornado-options', 'linkedin_url');
        //=========> Design => Global <=========//
        register_setting('tornado-options', 'theme_logo');
        register_setting('tornado-options', 'theme_logo_mobile');
        //=========> Design => Colors <=========//
        register_setting('tornado-options', 'primary_color');
        register_setting('tornado-options', 'primary_color_hover');
        register_setting('tornado-options', 'secondary_color');
        register_setting('tornado-options', 'secondary_color_hover');
        register_setting('tornado-options', 'typo_color');
        //=========> Design => Fonts GF <=========//
        register_setting('tornado-options', 'google_fonts');
        register_setting('tornado-options', 'primary_font');
        register_setting('tornado-options', 'secondary_font');
        register_setting('tornado-options', 'primary_font_rtl');
        register_setting('tornado-options', 'secondary_font_rtl');
        //=========> Design => Custom Fonts <=========//
        register_setting('tornado-options', 'custom_primary_font');
        register_setting('tornado-options', 'custom_secondary_font');
        register_setting('tornado-options', 'custom_primary_font_rtl');
        register_setting('tornado-options', 'custom_secondary_font_rtl');
        //=========> Design => Fonts Options <=========//
        register_setting('tornado-options', 'base_l_size');
        register_setting('tornado-options', 'base_m_size');
        register_setting('tornado-options', 'base_s_size');
        register_setting('tornado-options', 'base_line_height');
        register_setting('tornado-options', 'normal_weight');
        register_setting('tornado-options', 'medium_weight');
        register_setting('tornado-options', 'strong_weight');
    }

    add_action('admin_init', 'register_theme_options');

    //========> Page Render Function <========//
    function theme_options_render() {
        $theme_path = get_template_directory_uri();
?>

<!-- Theme Options -->
<div class="theme-options <?php if (is_rtl()) { echo 'rtl'; }?>">
    <!-- Tabs Menu -->
    <ul class="tabs-menu">
        <li class="logo"> <img src="<?php echo $theme_path; ?>/inc/functions/admin/img/phoenix-logo.png" alt=""> </li>
        <li class="ti-cog active" data-tab="general-options"><?php echo __('General Settings','tornado'); ?></li>
        <li class="ti-phone" data-tab="contact-options"><?php echo __('Contact Info','tornado'); ?></li>
        <li class="ti-brush" data-tab="design-options"><?php echo __('Design Options','tornado'); ?></li>
    </ul>
    <!-- Tabs Content -->
    <form method="post" action="options.php" class="tabs-form">
        <!-- Submit Button -->
        <div class="floating-submit">
            <?php
                //=======> Hidden Inputs Handler for WP Options Register <=========//
                settings_fields('tornado-options');
                do_settings_sections('tornado-options');
                //=======> Grap Submit Button <=========//
                submit_button();
            ?>
        </div>
        <!-- Page Content -->
        <div class="tabs-wraper">
            <!-- General Options -->
            <div class="tab-content active" id="general-options">
                <?php get_template_part('inc/functions/admin/options-tabs/01-General'); ?>
            </div>
            <!-- Contact Info Options -->
            <div class="tab-content" id="contact-options">
                <?php get_template_part('inc/functions/admin/options-tabs/02-Contact'); ?>
            </div>
            <!-- Contact Info Options -->
            <div class="tab-content" id="design-options">
                <?php get_template_part('inc/functions/admin/options-tabs/03-Design'); ?>
            </div>
            <!-- // Last Tab -->
        </div>
        <!-- Page Footer -->
        <div class="page-footer">
            <!-- Copyrights -->
            <p><?php echo __('Powered by Tornado UI v2 Framework © 2016 - 2020','tornado'); ?></p>
            <!-- Submit Button -->
            <?php submit_button(); ?>
        </div>
        <!-- // Page Footer -->
    </form>
    <!-- // Tabs Content -->
</div>
<!-- // Theme Options -->

<!-- Code Mirror -->
<script src="https://codemirror.net/lib/codemirror.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.48.0/mode/xml/xml.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.0/mode/htmlmixed/htmlmixed.min.js"></script>
<!-- Slim Select -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.26.0/slimselect.min.js"></script>
<!-- Custom JS -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        'use strict';

        //========> get Tabs Elements <========//
        var tab_btns = document.querySelectorAll('[data-tab]'),
            tabs_content = document.querySelectorAll('.tab-content');

        //========> On Click Add Tab ID to URL <========//
        Array.from(tab_btns).forEach(btn => {
            btn.addEventListener('click', event => {
                var tab_id = btn.getAttribute('data-tab');
                window.location.hash = tab_id;
            });
        });

        //========> get Tab Id from URL <========//
        var current_tab_id = window.location.hash.substr(1);
        if (current_tab_id) {
            var current_tab = document.querySelector('#'+current_tab_id);
            if (current_tab.classList.contains('tab-content')) {
                Array.from(tabs_content).forEach(tab => {
                    var tab_id = tab.getAttribute('id');
                    //=======> Active Button <========//
                    Array.from(tab_btns).forEach(btn => {
                        var btn_id = btn.getAttribute('data-tab');
                        if(btn_id == current_tab_id) {
                            btn.classList.add('active'); 
                        } else {
                            btn.classList.remove('active');
                        }
                    });
                    //=======> Active Tab <========//
                    setTimeout(() => {
                        if(tab_id == current_tab_id) {
                            tab.classList.add('active');
                        } else {
                            tab.classList.remove('active');
                        }
                    }, 200);
                });
            }
        }

        /*============ Select Color Picker Elements ==============*/
        var color_pickers = document.querySelectorAll('.color-picker');
        Array.from(color_pickers).forEach(element => {
            var pickerInput = element.querySelector('input'),
                pickerBackground = element.querySelector('.color-prev'),
                defaultColor = pickerInput.getAttribute('placeholder');

            var picker = new Picker(element);

            //==== Set Default =====//
            picker.setColor(defaultColor)
            pickerInput.value = defaultColor;

            // You can do what you want with the chosen color using two callbacks: onChange and onDone.
            picker.onChange = function(color) {
                pickerBackground.style.background = color.hex;
                pickerInput.value = color.hex;
            };
        });

        /*============ Advanced Select ==============*/
        var advancedSelect = document.querySelectorAll('.advanced-select');
        Array.from(advancedSelect).forEach((element,index) => {
            element.setAttribute('id','slime-selector-n'+index);
            var selectorID = '#slime-selector-n'+index;
            new SlimSelect({
                select: selectorID,
            });
        });

        /*============ Google Fonts Controler ==============*/
        var googleFontsStatus = document.querySelector('#google-fonts-status');
        if (googleFontsStatus) {
            googleFontsStatus.addEventListener('change', event => {
                var googleFontsControls = document.querySelectorAll('.google-fonts-controler'),
                    customFontsControls = document.querySelectorAll('.custom-fonts-controler');
                //======== If Google Fonts On ==========//
                if (googleFontsStatus.checked) {
                    Array.from(googleFontsControls).forEach(element => {
                        element.classList.remove('hidden');
                    });
                    Array.from(customFontsControls).forEach(element => {
                        element.classList.add('hidden');
                    });
                //======== If Google Fonts Off ==========//
                } else {
                    Array.from(googleFontsControls).forEach(element => {
                        element.classList.add('hidden');
                    });
                    Array.from(customFontsControls).forEach(element => {
                        element.classList.remove('hidden');
                    });
                }
            });
        }
    });
    //===== Media Uploader Popup =====//
    jQuery(document).ready(function ($) {
        //===== Runs when the image button is clicked =====//
        $('.uploader-btn').click(function (e) {
            e.preventDefault();
            //===== Instantiates the variable that holds the media library =====//
            var meta_image_frame;
            //===== Get preview Input =====//
            var meta_image = $(this).parents('.media-uploader-control').find('.uploader-input'),
                imagePrev = $(this).parents('.media-uploader-control').find('.image-prev');
            //===== If the media library already exists, re-open it =====//
            if (meta_image_frame) { meta_image_frame.open(); return; }
            //===== Sets up the media library =====//
            meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
                title: meta_image.title,
                button: { text: meta_image.button }
            });
            //===== Runs when an media is selected =====//
            meta_image_frame.on('select', function () {
                //===== Grabs the Selection and creates a JSON representation of the model =====//
                var media_attachment = meta_image_frame.state().get('selection').first().toJSON();
                //===== Sends the attachment URL to the media input field =====//
                meta_image.val(media_attachment.url);
                imagePrev.attr('src',media_attachment.url);
            });
            //===== Opens the media library =====//
            meta_image_frame.open();
        });
    });
</script>
<?php } ?>