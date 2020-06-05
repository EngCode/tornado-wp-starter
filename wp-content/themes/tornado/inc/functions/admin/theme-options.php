<?php
    /**
     * Tornado Theme Custom Options Pages
     * @package Tornado Wordpress
     * Creating Custom Options for Easy Theme and Design Control.
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
        //=========> Design => Logo <=========//
        register_setting('tornado-options', 'theme_logo');
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
        <li class="logo"> <img src="<?php echo $theme_path; ?>/inc/functions/admin/img/tornado-logo.png" alt=""> </li>
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

<!-- Media Uploader Popup -->
<script type="text/javascript">
jQuery(document).ready(function ($) {
    //===== Runs when the image button is clicked =====//
    $('.uploader-btn').click(function (e) {
        e.preventDefault();
        //===== Instantiates the variable that holds the media library =====//
        var meta_image_frame;
        //===== Get preview Input =====//
        var meta_image = $(this).parents('.media-uploader-control').find('.uploader-input');
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
        });
        //===== Opens the media library =====//
        meta_image_frame.open();
    });
});
</script>
<?php } ?>