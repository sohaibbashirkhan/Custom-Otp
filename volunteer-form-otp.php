<?php
/*
Plugin Name: Volunteer Form OTP
Description: A plugin to handle volunteer form submissions and OTP verification.
Version: 1.0
Author: ReserachTeq
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function start_session() {
    if (!session_id()) {
        session_start();
    }
}

add_action('init', 'start_session', 1);

// Create database table on plugin activation
function volunteer_form_otp_install() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'volunteer_form_data';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        full_name varchar(255) NOT NULL,
        gender varchar(10),
        dob date NOT NULL,
        address text NOT NULL,
        city varchar(100) NOT NULL,
        state varchar(100) NOT NULL,
        zip varchar(20) NOT NULL,
        country varchar(100) NOT NULL,
        phone varchar(20) NOT NULL,
        email varchar(100) NOT NULL,
        days_available text NOT NULL,
        times_available text NOT NULL,
        skills_experience text NOT NULL,
        volunteer_interests text NOT NULL,
        emergency_contact_name varchar(255) NOT NULL,
        relationship varchar(50) NOT NULL,
        emergency_phone varchar(20) NOT NULL,
        emergency_email varchar(100) NOT NULL,
        reference1_name varchar(255) NOT NULL,
        reference1_relationship varchar(50) NOT NULL,
        reference1_phone varchar(20) NOT NULL,
        reference1_email varchar(100) NOT NULL,
        reference2_name varchar(255) NOT NULL,
        reference2_relationship varchar(50) NOT NULL,
        reference2_phone varchar(20) NOT NULL,
        reference2_email varchar(100) NOT NULL,
        additional_info text,
        signature varchar(255) NOT NULL,
        date date NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

register_activation_hook(__FILE__, 'volunteer_form_otp_install');

// Shortcode for displaying the form
function volunteer_form_shortcode() {
    ob_start();
    include plugin_dir_path(__FILE__) . 'form.php';
    return ob_get_clean();
}

add_shortcode('volunteer_form', 'volunteer_form_shortcode');

function work_shortcode() {
    ob_start();
    include plugin_dir_path(__FILE__) . 'work.php';
    return ob_get_clean();
}

add_shortcode('work_verification', 'work_shortcode');
// Shortcode for OTP verification
function otp_verification_shortcode() {
    ob_start();
    include plugin_dir_path(__FILE__) . 'otp_verification.php';
    return ob_get_clean();
}

add_shortcode('otp_verification', 'otp_verification_shortcode');
?>
