<?php
/**
 * Plugin Name: Salesforce User Sync
 * Description: Sync your Wordpress user accounts with Salesforce.
 * Version: 0.0.1
 * Author: timwass
 * Text Domain: salesforce-user-sync
 * License: GPLv2 or later
 */

// Make sure we don't expose any info if called directly
if (!function_exists('add_action')) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

define('SUS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SUS_PLUGIN_DIR', plugin_dir_path(__FILE__));

if (is_admin()) {
    require_once(SUS_PLUGIN_DIR . 'soapclient/SforcePartnerClient.php');
    require_once(SUS_PLUGIN_DIR . 'includes/class.sus-salesforce.php');
    require_once(SUS_PLUGIN_DIR . 'includes/class.sus-ajax.php');

    function sus_settings_link($links)
    {
        $settings_link = '<a href="admin.php?page=salesforce-user-sync">Settings</a>';
        array_unshift($links, $settings_link);
        return $links;
    }

    $plugin = plugin_basename(__FILE__);
    add_filter("plugin_action_links_$plugin", 'sus_settings_link');

    require_once(SUS_PLUGIN_DIR . 'includes/class.sus-admin.php');
}