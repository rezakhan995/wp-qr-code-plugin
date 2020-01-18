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


    $current_height = get_option("ptqrc_height");
    $current_height = $current_height ? $current_height : 150;
    $current_width = get_option("ptqrc_width");
    $current_width = $current_width ? $current_width : 150;

    $dimension = apply_filters("ptqrc_get_dimension", "{$current_width}x{$current_height}");

    $qrcode_source = sprintf("https://api.qrserver.com/v1/create-qr-code/?size=%s&data=%s", $dimension, $current_post_permalink);
    $content .= sprintf("<img src='%s' alter='%s'/>", $qrcode_source, $current_post_title);
    return $content;
}

add_filter("the_content", "ptqrc_display_qrcode", 99);


function ptqrc_show_settings()
{

    add_settings_section("ptqrc_settings_section", __("Post To QrCode Settings", "qr-post"), "ptqrc_settings_section", "general");

    add_settings_field("ptqrc_height", __("QR Code Height", "qr-post"), 'ptqrc_set_height', "general", "ptqrc_settings_section");
    add_settings_field("ptqrc_width", __("QR Code Width", "qr-post"), 'ptqrc_set_width', "general", "ptqrc_settings_section");
    add_settings_field("ptqrc_country", __("Select coutries to show QR Code", "qr-post"), "ptqrc_select_country", "general", "ptqrc_settings_section");



    register_setting("general", "ptqrc_country", array("sanitize_callback" => "esc_attr"));
    register_setting("general", "ptqrc_height", array("sanitize_callback" => "esc_attr"));
    register_setting("general", "ptqrc_width", array("sanitize_callback" => "esc_attr"));
}

function ptqrc_select_country()
{

    $country = get_option("ptqrc_country");
    $countries = array(
        "None",
        "Bangladesh",
        "India",
        "Pakinstan",
        "Myanmar",
        "Nepal",
        "Srilanka"
    );
    printf("<select name='%s' id='%s'>", "ptqrc_country", "ptqrc_country");
    foreach ($countries as $single_country) {
        $select = "";
        if ($country == $single_country) {
            $select = "selected";
        }
        printf("<option id='%s' %s>%s</option>", $single_country, $select, $single_country);
    }
    printf("</select>");
}

function ptqrc_settings_section()
{
    echo "<h5>This section is used to configure the height and width for QR Code</h5>";
}

function ptqrc_set_height()
{
    $current_height = get_option("ptqrc_height");
    printf("<input type='number' name='%s' value='%s' id='%s' />", "ptqrc_height", $current_height, "ptqrc_height");
}

function ptqrc_set_width()
{
    $current_width = get_option("ptqrc_width");
    printf("<input type='number' name='%s' value='%s' id='%s' />", "ptqrc_width", $current_width, "ptqrc_width");
}



add_action("admin_init", "ptqrc_show_settings");
