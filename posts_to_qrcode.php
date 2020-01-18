<?php
/*
Plugin Name: QrPost  
Plugin URI: https://www.reza-khan.com/plugins/posts-to-qrcode
Author: Reza Khan
Author URI: https://www.reza-khan.com
Description: Display QR Code under every posts
Version: 1.0
License: GPLv2 or later
Text Domain: qr-post
Domain Path: /languages/
*/


function ptqrc_load_testdomain()
{
    load_plugin_textdomain("qr-post", false, dirname(__FILE__) . "/languages");
}

add_action("plugins_loaded", "ptqrc_load_testdomain");


function ptqrc_display_qrcode($content)
{
    $current_post_id = get_the_ID();
    $current_post_title = get_the_title($current_post_id);
    $current_post_permalink = urlencode(get_the_permalink($current_post_id));
    $current_post_type = get_post_type($current_post_id);

    $excluded_post_types = apply_filters("ptqrc_excluded_post_types", array());
    if (in_array($current_post_type, $excluded_post_types)) {
        return $content;
    }

    $dimension = apply_filters("ptqrc_get_dimension", "150x150");

    $qrcode_source = sprintf("https://api.qrserver.com/v1/create-qr-code/?size=%s&data=%s", $dimension, $current_post_permalink);
    $content .= sprintf("<img src='%s' alter='%s'/>", $qrcode_source, $current_post_title);
    return $content;
}

add_filter("the_content", "ptqrc_display_qrcode", 99);
