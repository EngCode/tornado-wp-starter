<?php
    //======= Get Form Data ======//
    $pageTit = get_the_title();
    if ( isset($_GET['utm_source']) ) { $utm_source = $_GET['utm_source']; } else { $utm_source = 'Not Available'; }
    if ( isset($_GET['utm_campaign']) ) { $utm_campaign = $_GET['utm_campaign'];} else { $utm_campaign = 'Not a Campaign'; }
    //====== Get the Form ======//
    echo do_shortcode('[contact-form-7 id="1075" page-title="'.$pageTit.' || المصدر : '.$utm_source.' || الحملة : '.$utm_campaign.'"]');
?>