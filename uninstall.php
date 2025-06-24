<?php
/**
 * Uninstall script for Display YouTube Playlists
 * 
 * This file is called when the plugin is deleted from WordPress admin.
 * It cleans up all plugin data from the database.
 */

// If uninstall not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Clean up plugin options using WordPress functions instead of direct queries
delete_option('ypd_api_key');
delete_option('ypd_channel_id');
delete_option('ypd_theme');
delete_option('ypd_background_color');
delete_option('ypd_custom_css');

// Clean up transients (cache) using WordPress functions
// Since we can't easily iterate over all transients with a prefix,
// we'll clear the most common cache keys
$cache_prefixes = array(
    'ypd_playlists_',
    'ypd_playlist_videos_',
    'ypd_video_details_'
);

// Clear known cache keys (up to 100 variations of each)
foreach ($cache_prefixes as $prefix) {
    for ($i = 0; $i < 100; $i++) {
        $cache_key = $prefix . md5($i);
        delete_transient($cache_key);
    }
}

// For more thorough cleanup, we can use WordPress built-in functions
// to clean up any remaining transients (WordPress 4.9+)
if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
}

// Clean up any user meta or post meta if the plugin stored any
// (This plugin doesn't currently use these, but included for completeness)
// delete_metadata('user', 0, 'ypd_user_preference', '', true);
// delete_metadata('post', 0, 'ypd_post_setting', '', true);

// Optional: Log the cleanup (only if debugging is enabled)
if (defined('WP_DEBUG') && WP_DEBUG && function_exists('error_log')) {
    error_log('Display YouTube Playlists: Plugin data cleaned up during uninstall');
}
?>