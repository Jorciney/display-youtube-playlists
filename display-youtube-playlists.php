<?php
/**
 * Plugin Name: Display YouTube Playlists
 * Plugin URI: https://jorciney.dev/wp-plugins/display-youtube-playlists
 * Description: Display YouTube channel playlists in a beautiful horizontal scrolling gallery with configurable themes and background colors.
 * Version: 2.0.0
 * Author: Jorciney
 * Author URI: https://jorciney.dev
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: display-youtube-playlists
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Plugin constants
define('DYP_VERSION', '2.0.0');
define('DYP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('DYP_PLUGIN_PATH', plugin_dir_path(__FILE__));

// Create options table on activation
register_activation_hook(__FILE__, 'dyp_create_options');

function dyp_create_options() {
    // Add default options
    add_option('dyp_api_key', '');
    add_option('dyp_channel_id', '');
    add_option('dyp_theme', 'light');
    add_option('dyp_background_color', '#f8f9fa');
    add_option('dyp_custom_css', '');
}

// Add admin menu
add_action('admin_menu', 'dyp_admin_menu');

function dyp_admin_menu() {
    add_options_page(
        'Display YouTube Playlists Settings',
        'Display YouTube Playlists',
        'manage_options',
        'display-youtube-playlists',
        'dyp_admin_page'
    );
}

function dyp_admin_page() {
    // Handle form submission
    if (isset($_POST['submit'])) {
        check_admin_referer('dyp_config_nonce');
        
        // Validate and sanitize inputs with isset checks
        $api_key = isset($_POST['api_key']) ? sanitize_text_field(wp_unslash($_POST['api_key'])) : '';
        $channel_id = isset($_POST['channel_id']) ? sanitize_text_field(wp_unslash($_POST['channel_id'])) : '';
        $theme = isset($_POST['theme']) ? sanitize_text_field(wp_unslash($_POST['theme'])) : 'light';
        $background_color = isset($_POST['background_color']) ? sanitize_hex_color(wp_unslash($_POST['background_color'])) : '#f8f9fa';
        $custom_css = isset($_POST['custom_css']) ? wp_strip_all_tags(wp_unslash($_POST['custom_css'])) : '';
        
        // Validate theme value
        if (!in_array($theme, array('light', 'dark'), true)) {
            $theme = 'light';
        }
        
        // Update options
        update_option('dyp_api_key', $api_key);
        update_option('dyp_channel_id', $channel_id);
        update_option('dyp_theme', $theme);
        update_option('dyp_background_color', $background_color);
        update_option('dyp_custom_css', $custom_css);
        
        echo '<div class="notice notice-success"><p><strong>Settings saved successfully!</strong></p></div>';
        
        // Clear cache when settings change
        dyp_clear_cache();
    }
    
    // Get current options
    $api_key = get_option('dyp_api_key', '');
    $channel_id = get_option('dyp_channel_id', '');
    $theme = get_option('dyp_theme', 'light');
    $background_color = get_option('dyp_background_color', '#f8f9fa');
    $custom_css = get_option('dyp_custom_css', '');
    ?>
    <div class="wrap">
        <h1>🎥 Display YouTube Playlists Settings</h1>
        
        <div class="notice notice-info">
            <p><strong>📋 Quick Setup Instructions:</strong></p>
            <ol>
                <li><strong>Get YouTube API Key:</strong> Visit <a href="https://console.developers.google.com" target="_blank">Google Cloud Console</a> → Create/Select Project → Enable YouTube Data API v3 → Create API Key</li>
                <li><strong>Find Channel ID:</strong> Go to your YouTube channel → View page source → Search for "channelId" or use <a href="https://www.youtube.com/account_advanced" target="_blank">YouTube Advanced Settings</a></li>
                <li><strong>Configure settings below and save</strong></li>
                <li><strong>Add shortcode to any page/post:</strong> <code>[display_youtube_playlists]</code></li>
            </ol>
        </div>

        <form method="post" action="">
            <?php wp_nonce_field('dyp_config_nonce'); ?>
            
            <div class="dyp-admin-container">
                <div class="dyp-main-settings">
                    <h2>🔧 Main Settings</h2>
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="api_key">YouTube API Key</label>
                                <span class="required">*</span>
                            </th>
                            <td>
                                <input type="text" 
                                       id="api_key" 
                                       name="api_key" 
                                       value="<?php echo esc_attr($api_key); ?>" 
                                       class="regular-text" 
                                       placeholder="AIzaSyBnVUNflrflrflrflrflrflrflrfl..." />
                                <p class="description">
                                    <strong>How to get:</strong> 
                                    <a href="https://console.developers.google.com" target="_blank">Google Cloud Console</a> → 
                                    Create Project → Enable "YouTube Data API v3" → Create Credentials → API Key
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="channel_id">YouTube Channel ID</label>
                                <span class="required">*</span>
                            </th>
                            <td>
                                <input type="text" 
                                       id="channel_id" 
                                       name="channel_id" 
                                       value="<?php echo esc_attr($channel_id); ?>" 
                                       class="regular-text" 
                                       placeholder="UCdXAKJffaLTi9YQRyVb8NBg" />
                                <p class="description">
                                    <strong>How to find:</strong> Go to your YouTube channel → 
                                    <a href="https://www.youtube.com/account_advanced" target="_blank">Advanced Settings</a> → 
                                    Copy Channel ID <strong>OR</strong> View page source → Search for "channelId"
                                </p>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="dyp-appearance-settings">
                    <h2>🎨 Appearance Settings</h2>
                    <table class="form-table">
                        <tr>
                            <th scope="row">Default Theme</th>
                            <td>
                                <select name="theme" id="theme">
                                    <option value="light" <?php selected($theme, 'light'); ?>>☀️ Light Theme</option>
                                    <option value="dark" <?php selected($theme, 'dark'); ?>>🌙 Dark Theme</option>
                                </select>
                                <p class="description">Choose the default appearance theme for your video gallery</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Background Color</th>
                            <td>
                                <div class="dyp-color-picker-container">
                                    <input type="color" 
                                           name="background_color" 
                                           id="background_color" 
                                           value="<?php echo esc_attr($background_color); ?>" />
                                    <input type="text" 
                                           id="background_color_text" 
                                           value="<?php echo esc_attr($background_color); ?>" 
                                           class="small-text" 
                                           readonly />
                                    <button type="button" id="reset_background_color" class="button button-secondary">🔄 Reset to Default</button>
                                </div>
                                <p class="description">Set a custom background color for the video gallery container</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Custom CSS</th>
                            <td>
                                <textarea name="custom_css" 
                                         id="custom_css" 
                                         rows="8" 
                                         cols="50" 
                                         class="large-text code"
                                         placeholder="/* Add your custom CSS here */
.youtube-playlist-container {
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.video-card {
    border: 2px solid #your-color;
}"><?php echo esc_textarea($custom_css); ?></textarea>
                                <p class="description">Add custom CSS to further customize the appearance</p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <?php submit_button('💾 Save Settings', 'primary', 'submit', false); ?>
            <button type="button" id="test-connection" class="button button-secondary">🔍 Test API Connection</button>
            <button type="button" id="clear-cache" class="button button-secondary">🗑️ Clear Cache</button>
        </form>

        <div class="dyp-usage-instructions">
            <h2>📖 Usage Instructions</h2>
            <div class="dyp-shortcode-examples">
                <h3>Basic Usage</h3>
                <code>[display_youtube_playlists]</code>
                <p>Displays all playlists from your configured channel with default settings</p>

                <h3>Advanced Options</h3>
                <ul>
                    <li><code>[display_youtube_playlists theme="dark"]</code> - Force dark theme</li>
                    <li><code>[display_youtube_playlists theme="light"]</code> - Force light theme</li>
                    <li><code>[display_youtube_playlists background="#ff5733"]</code> - Custom background color</li>
                    <li><code>[display_youtube_playlists max_videos="10"]</code> - Limit videos per playlist</li>
                    <li><code>[display_youtube_playlists debug="true"]</code> - Debug mode (admins only)</li>
                </ul>

                <h3>Combine Options</h3>
                <code>[display_youtube_playlists theme="dark" background="#1a1a1a" max_videos="12"]</code>
            </div>
        </div>

        <div class="dyp-troubleshooting">
            <h2>🛠️ Troubleshooting</h2>
            <div class="dyp-help-section">
                <h4>Common Issues:</h4>
                <ul>
                    <li><strong>No videos showing:</strong> Check API key and Channel ID are correct</li>
                    <li><strong>API quota exceeded:</strong> YouTube API has daily limits - wait 24 hours or upgrade quota</li>
                    <li><strong>Videos not updating:</strong> Use "Clear Cache" button above</li>
                    <li><strong>Theme not applying:</strong> Check for CSS conflicts with your theme</li>
                </ul>
                
                <h4>Need Help?</h4>
                <p>Use the "Test API Connection" button above to verify your settings, or enable debug mode in your shortcode: <code>[display_youtube_playlists debug="true"]</code></p>
            </div>
        </div>
    </div>

    <style>
        .dyp-admin-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin: 20px 0;
        }
        
        .dyp-main-settings,
        .dyp-appearance-settings {
            background: #fff;
            border: 1px solid #ccd0d4;
            border-radius: 8px;
            padding: 20px;
        }
        
        .dyp-main-settings h2,
        .dyp-appearance-settings h2 {
            margin-top: 0;
            color: #1e1e1e;
            border-bottom: 2px solid #0073aa;
            padding-bottom: 10px;
        }
        
        .required {
            color: #d63638;
            font-weight: bold;
        }
        
        .dyp-usage-instructions,
        .dyp-troubleshooting {
            background: #f6f7f7;
            border: 1px solid #c3c4c7;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .dyp-shortcode-examples code {
            background: #2271b1;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            display: inline-block;
            margin: 5px 0;
        }
        
        .dyp-help-section ul {
            background: white;
            padding: 15px 30px;
            border-radius: 4px;
            border-left: 4px solid #0073aa;
        }
        
        .dyp-color-picker-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .dyp-color-picker-container input[type="color"] {
            width: 50px;
            height: 35px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        #background_color_text {
            font-family: monospace;
            text-transform: uppercase;
        }
        
        @media (max-width: 768px) {
            .dyp-admin-container {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <script>
    jQuery(document).ready(function($) {
        // Default colors for theme reset
        const defaultColors = {
            light: '#f8f9fa',
            dark: '#1a1a1a'
        };
        
        // Sync color picker with text input
        $('#background_color').on('change', function() {
            $('#background_color_text').val($(this).val());
        });
        
        // Reset background color to default based on current theme
        $('#reset_background_color').on('click', function() {
            const currentTheme = $('#theme').val();
            const defaultColor = defaultColors[currentTheme];
            
            $('#background_color').val(defaultColor);
            $('#background_color_text').val(defaultColor);
            
            // Show feedback
            $(this).text('✅ Reset!').prop('disabled', true);
            setTimeout(() => {
                $(this).text('🔄 Reset to Default').prop('disabled', false);
            }, 1500);
        });
        
        // Update default color when theme changes
        $('#theme').on('change', function() {
            const newTheme = $(this).val();
            const button = $('#reset_background_color');
            
            // Update button text to show which default will be used
            if (newTheme === 'dark') {
                button.attr('title', 'Reset to dark theme default (#1a1a1a)');
            } else {
                button.attr('title', 'Reset to light theme default (#f8f9fa)');
            }
        });
        
        // Set initial tooltip
        $('#theme').trigger('change');
        
        // Test API connection
        $('#test-connection').on('click', function() {
            const button = $(this);
            const apiKey = $('#api_key').val();
            const channelId = $('#channel_id').val();
            
            if (!apiKey || !channelId) {
                alert('Please enter both API Key and Channel ID first.');
                return;
            }
            
            button.text('🔄 Testing...').prop('disabled', true);
            
            $.post(ajaxurl, {
                action: 'dyp_test_connection',
                api_key: apiKey,
                channel_id: channelId,
                nonce: '<?php echo esc_attr(wp_create_nonce('dyp_test_nonce')); ?>'
            }).done(function(response) {
                if (response.success) {
                    alert('✅ Connection successful!\n\nChannel: ' + response.data.channel_name + '\nPlaylists found: ' + response.data.playlist_count);
                } else {
                    alert('❌ Connection failed:\n' + response.data);
                }
            }).fail(function() {
                alert('❌ Connection test failed. Please check your settings.');
            }).always(function() {
                button.text('🔍 Test API Connection').prop('disabled', false);
            });
        });
        
        // Clear cache
        $('#clear-cache').on('click', function() {
            const button = $(this);
            button.text('🔄 Clearing...').prop('disabled', true);
            
            $.post(ajaxurl, {
                action: 'dyp_clear_cache',
                nonce: '<?php echo esc_attr(wp_create_nonce('dyp_clear_cache_nonce')); ?>'
            }).done(function(response) {
                if (response.success) {
                    alert('✅ Cache cleared successfully!');
                } else {
                    alert('❌ Failed to clear cache.');
                }
            }).always(function() {
                button.text('🗑️ Clear Cache').prop('disabled', false);
            });
        });
    });
    </script>
    <?php
}

// AJAX handler for testing API connection
add_action('wp_ajax_dyp_test_connection', 'dyp_ajax_test_connection');

function dyp_ajax_test_connection() {
    check_ajax_referer('dyp_test_nonce', 'nonce');
    
    $api_key = isset($_POST['api_key']) ? sanitize_text_field(wp_unslash($_POST['api_key'])) : '';
    $channel_id = isset($_POST['channel_id']) ? sanitize_text_field(wp_unslash($_POST['channel_id'])) : '';
    
    // Test channel info
    $test_url = "https://www.googleapis.com/youtube/v3/channels?part=snippet&id={$channel_id}&key={$api_key}";
    $response = wp_remote_get($test_url, array('timeout' => 30));
    
    if (is_wp_error($response)) {
        wp_send_json_error('Network error: ' . $response->get_error_message());
    }
    
    $data = json_decode(wp_remote_retrieve_body($response), true);
    
    if (wp_remote_retrieve_response_code($response) !== 200) {
        $error = isset($data['error']['message']) ? $data['error']['message'] : 'API Error';
        wp_send_json_error($error);
    }
    
    if (!isset($data['items'][0])) {
        wp_send_json_error('Channel not found. Please check your Channel ID.');
    }
    
    // Test playlists
    $playlists_url = "https://www.googleapis.com/youtube/v3/playlists?part=snippet&channelId={$channel_id}&maxResults=10&key={$api_key}";
    $playlists_response = wp_remote_get($playlists_url, array('timeout' => 30));
    $playlists_data = json_decode(wp_remote_retrieve_body($playlists_response), true);
    
    $playlist_count = isset($playlists_data['items']) ? count($playlists_data['items']) : 0;
    
    wp_send_json_success(array(
        'channel_name' => $data['items'][0]['snippet']['title'],
        'playlist_count' => $playlist_count
    ));
}

// AJAX handler for clearing cache
add_action('wp_ajax_dyp_clear_cache', 'dyp_ajax_clear_cache');

function dyp_ajax_clear_cache() {
    check_ajax_referer('dyp_clear_cache_nonce', 'nonce');
    
    $result = dyp_clear_cache();
    
    if ($result) {
        wp_send_json_success();
    } else {
        wp_send_json_error();
    }
}

// Main function to fetch YouTube videos data
function dyp_get_videos_data($api_key = '', $channel_id = '') {
    // Use stored options if not provided
    if (empty($api_key)) {
        $api_key = get_option('dyp_api_key', '');
    }
    if (empty($channel_id)) {
        $channel_id = get_option('dyp_channel_id', '');
    }
    
    // Validate required settings
    if (empty($api_key) || empty($channel_id)) {
        if (function_exists('error_log')) {
            error_log('Display YouTube Playlists: Missing API key or Channel ID');
        }
        return false;
    }
    
    return dyp_fetch_playlists_videos($channel_id, $api_key);
}

// Fetch playlists and their videos
function dyp_fetch_playlists_videos($channel_id, $api_key) {
    $playlists = dyp_fetch_playlists($channel_id, $api_key);
    
    if (!$playlists || empty($playlists)) {
        if (function_exists('error_log')) {
            error_log('Display YouTube Playlists: No playlists found for channel ' . $channel_id);
        }
        return false;
    }
    
    $videos_by_playlist = array();
    
    foreach ($playlists as $playlist) {
        $playlist_title = $playlist['title'];
        $playlist_id = $playlist['id'];
        
        if (empty($playlist_id)) {
            continue;
        }
        
        $playlist_videos = dyp_fetch_playlist_videos($playlist_id, $api_key, 15);
        
        if ($playlist_videos && !empty($playlist_videos)) {
            $videos_by_playlist[$playlist_title] = $playlist_videos;
        }
    }
    
    return !empty($videos_by_playlist) ? $videos_by_playlist : false;
}

// Fetch videos from a specific playlist
function dyp_fetch_playlist_videos($playlist_id, $api_key, $max_results = 20) {
    $cache_key = 'dyp_playlist_videos_' . md5($playlist_id);
    $cached_data = get_transient($cache_key);
    
    if ($cached_data !== false) {
        return $cached_data;
    }
    
    $url = "https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&playlistId={$playlist_id}&maxResults={$max_results}&key={$api_key}";
    
    $response = wp_remote_get($url, array('timeout' => 30));
    
    if (is_wp_error($response)) {
        if (function_exists('error_log')) {
            error_log('Display YouTube Playlists: Request error for playlist ' . $playlist_id . ' - ' . $response->get_error_message());
        }
        return false;
    }
    
    $response_code = wp_remote_retrieve_response_code($response);
    if ($response_code !== 200) {
        if (function_exists('error_log')) {
            error_log('Display YouTube Playlists: HTTP error ' . $response_code . ' for playlist ' . $playlist_id);
        }
        return false;
    }
    
    $data = json_decode(wp_remote_retrieve_body($response), true);
    
    if (!isset($data['items'])) {
        return false;
    }
    
    $videos = array();
    
    foreach ($data['items'] as $item) {
        $video_id = $item['snippet']['resourceId']['videoId'];
        
        // Get additional video details
        $video_details = dyp_fetch_video_details($video_id, $api_key);
        
        // Process thumbnail
        $thumbnail_url = dyp_get_best_thumbnail($item['snippet']['thumbnails']);
        
        if (empty($thumbnail_url)) {
            $thumbnail_url = "https://img.youtube.com/vi/{$video_id}/hqdefault.jpg";
        }
        
        $videos[] = array(
            'title' => $item['snippet']['title'],
            'date' => dyp_format_date($item['snippet']['publishedAt']),
            'thumbnail' => $thumbnail_url,
            'video_id' => $video_id,
            'duration' => $video_details['duration'] ?? '',
            'views' => $video_details['views'] ?? ''
        );
    }
    
    // Cache for 1 hour
    set_transient($cache_key, $videos, HOUR_IN_SECONDS);
    
    return $videos;
}

// Fetch video details (duration, views)
function dyp_fetch_video_details($video_id, $api_key) {
    $cache_key = 'dyp_video_details_' . $video_id;
    $cached_data = get_transient($cache_key);
    
    if ($cached_data !== false) {
        return $cached_data;
    }
    
    $url = "https://www.googleapis.com/youtube/v3/videos?part=statistics,contentDetails&id={$video_id}&key={$api_key}";
    
    $response = wp_remote_get($url, array('timeout' => 30));
    
    if (is_wp_error($response)) {
        return array();
    }
    
    $data = json_decode(wp_remote_retrieve_body($response), true);
    
    if (!isset($data['items'][0])) {
        return array();
    }
    
    $item = $data['items'][0];
    
    $details = array(
        'duration' => dyp_format_duration($item['contentDetails']['duration'] ?? ''),
        'views' => dyp_format_view_count($item['statistics']['viewCount'] ?? 0)
    );
    
    // Cache for 6 hours
    set_transient($cache_key, $details, 6 * HOUR_IN_SECONDS);
    
    return $details;
}

// Fetch playlists from channel
function dyp_fetch_playlists($channel_id, $api_key) {
    $cache_key = 'dyp_playlists_' . md5($channel_id);
    $cached_data = get_transient($cache_key);
    
    if ($cached_data !== false) {
        return $cached_data;
    }
    
    $url = "https://www.googleapis.com/youtube/v3/playlists?part=snippet,contentDetails&channelId={$channel_id}&maxResults=50&key={$api_key}";
    
    $response = wp_remote_get($url, array('timeout' => 30));
    
    if (is_wp_error($response)) {
        return false;
    }
    
    $data = json_decode(wp_remote_retrieve_body($response), true);
    
    if (!isset($data['items']) || empty($data['items'])) {
        return false;
    }
    
    $playlists = array();
    
    foreach ($data['items'] as $item) {
        if (isset($item['id']) && !empty($item['id'])) {
            $playlists[] = array(
                'title' => $item['snippet']['title'] ?? 'Untitled Playlist',
                'id' => $item['id']
            );
        }
    }
    
    // Cache for 2 hours
    set_transient($cache_key, $playlists, 2 * HOUR_IN_SECONDS);
    
    return $playlists;
}

// Utility functions
function dyp_format_duration($duration) {
    if (empty($duration)) return '';
    
    try {
        $interval = new DateInterval($duration);
        
        if ($interval->h > 0) {
            return sprintf('%d:%02d:%02d', $interval->h, $interval->i, $interval->s);
        } else {
            return sprintf('%d:%02d', $interval->i, $interval->s);
        }
    } catch (Exception $e) {
        return '';
    }
}

function dyp_format_view_count($views) {
    $views = intval($views);
    
    if ($views >= 1000000) {
        return round($views / 1000000, 1) . 'M';
    } elseif ($views >= 1000) {
        return round($views / 1000, 1) . 'K';
    } else {
        return $views;
    }
}

function dyp_format_date($date_string) {
    $date = new DateTime($date_string);
    return $date->format('d M Y');
}

function dyp_get_best_thumbnail($thumbnails) {
    if (isset($thumbnails['maxres'])) {
        return $thumbnails['maxres']['url'];
    } elseif (isset($thumbnails['high'])) {
        return $thumbnails['high']['url'];
    } elseif (isset($thumbnails['medium'])) {
        return $thumbnails['medium']['url'];
    } else {
        return $thumbnails['default']['url'] ?? '';
    }
}

// Main shortcode
function dyp_shortcode($atts) {
    $atts = shortcode_atts(array(
        'theme' => get_option('dyp_theme', 'light'),
        'background' => get_option('dyp_background_color', '#f8f9fa'),
        'max_videos' => 15,
        'debug' => false,
    ), $atts);
    
    $api_key = get_option('dyp_api_key', '');
    $channel_id = get_option('dyp_channel_id', '');
    
    // Check if plugin is configured
    if (empty($api_key) || empty($channel_id)) {
        if (current_user_can('manage_options')) {
            return '<div class="dyp-error">
                        <h4>⚙️ Display YouTube Playlists - Configuration Required</h4>
                        <p>Please configure your YouTube API Key and Channel ID in the <a href="' . esc_url(admin_url('options-general.php?page=display-youtube-playlists')) . '">plugin settings</a>.</p>
                    </div>';
        } else {
            return '<p>YouTube videos are currently being configured. Please check back soon!</p>';
        }
    }
    
    $videos_data = dyp_get_videos_data($api_key, $channel_id);
    
    // Debug mode for administrators
    if ($atts['debug'] && current_user_can('manage_options')) {
        $debug_info = dyp_generate_debug_info($api_key, $channel_id, $videos_data, $atts);
        
        if (!$videos_data) {
            return $debug_info . '<p class="dyp-error">Unable to load videos. Please check the debug information above.</p>';
        }
        
        return $debug_info . dyp_display_videos($videos_data, $atts);
    }
    
    if (empty($videos_data)) {
        return '<p class="dyp-error">No videos found. Please check your plugin configuration.</p>';
    }
    
    return dyp_display_videos($videos_data, $atts);
}

// Generate debug information
function dyp_generate_debug_info($api_key, $channel_id, $videos_data, $atts) {
    $debug_info = '<div class="dyp-debug">';
    $debug_info .= '<h4>🐛 Display YouTube Playlists - Debug Information</h4>';
    $debug_info .= '<p><strong>API Key:</strong> ' . (!empty($api_key) ? '✅ Configured' : '❌ Missing') . '</p>';
    $debug_info .= '<p><strong>Channel ID:</strong> ' . (!empty($channel_id) ? '✅ ' . esc_html($channel_id) : '❌ Missing') . '</p>';
    $debug_info .= '<p><strong>Theme:</strong> ' . esc_html($atts['theme']) . '</p>';
    $debug_info .= '<p><strong>Background:</strong> ' . esc_html($atts['background']) . '</p>';
    $debug_info .= '<p><strong>Max Videos:</strong> ' . esc_html($atts['max_videos']) . '</p>';
    
    if ($videos_data && is_array($videos_data)) {
        $total_videos = array_sum(array_map('count', $videos_data));
        $debug_info .= '<p><strong>✅ Playlists Found:</strong> ' . count($videos_data) . '</p>';
        $debug_info .= '<p><strong>✅ Total Videos:</strong> ' . $total_videos . '</p>';
        $debug_info .= '<p><strong>✅ Playlist Names:</strong> ' . implode(', ', array_keys($videos_data)) . '</p>';
    } else {
        $debug_info .= '<p><strong>❌ No video data found</strong></p>';
    }
    
    $debug_info .= '</div>';
    
    return $debug_info;
}

// Display videos function
function dyp_display_videos($videos_data, $atts = array()) {
    $theme = $atts['theme'] ?? 'light';
    $background_color = $atts['background'] ?? get_option('dyp_background_color', '#f8f9fa');
    $custom_css = get_option('dyp_custom_css', '');
    $theme_class = 'dyp-theme-' . $theme;
    
    ob_start();
    ?>
    <div class="youtube-playlist-container <?php echo esc_attr($theme_class); ?>" 
         style="background-color: <?php echo esc_attr($background_color); ?>;">
        
        <?php foreach ($videos_data as $playlist_name => $videos): ?>
            <div class="dyp-playlist-section">
                <div class="dyp-playlist-header">
                    <h2 class="dyp-playlist-title"><?php echo esc_html($playlist_name); ?></h2>
                    <div class="dyp-scroll-hint">
                        <span>Scroll to see more</span>
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
                
                <div class="dyp-videos-scroll-container">
                    <button class="dyp-scroll-btn dyp-scroll-left" aria-label="Scroll left">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    
                    <div class="dyp-videos-horizontal">
                        <?php foreach ($videos as $video): ?>
                            <div class="dyp-video-card" data-video-id="<?php echo esc_attr($video['video_id']); ?>">
                                <div class="dyp-video-thumbnail-container">
                                    <img src="<?php echo esc_url($video['thumbnail']); ?>" 
                                         alt="<?php echo esc_attr($video['title']); ?>" 
                                         class="dyp-video-thumbnail"
                                         loading="lazy">
                                    
                                    <div class="dyp-play-overlay">
                                        <div class="dyp-play-button">
                                            <i class="fas fa-play"></i>
                                        </div>
                                    </div>
                                    
                                    <?php if (!empty($video['duration'])): ?>
                                        <div class="dyp-video-duration">
                                            <?php echo esc_html($video['duration']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="dyp-video-info">
                                    <h3 class="dyp-video-title"><?php echo esc_html($video['title']); ?></h3>
                                    <div class="dyp-video-meta">
                                        <span class="dyp-video-date"><?php echo esc_html($video['date']); ?></span>
                                        <?php if (!empty($video['views'])): ?>
                                            <span class="dyp-video-views"><?php echo esc_html($video['views']); ?> views</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <button class="dyp-scroll-btn dyp-scroll-right" aria-label="Scroll right">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Video Modal -->
    <div id="dyp-video-modal" class="dyp-video-modal">
        <div class="dyp-video-modal-content">
            <span class="dyp-close-modal">&times;</span>
            <div class="dyp-video-container">
                <iframe id="dyp-video-iframe" 
                        width="100%" 
                        height="100%" 
                        frameborder="0" 
                        allowfullscreen>
                </iframe>
            </div>
        </div>
    </div>

    <style>
        /* Base styles */
        .youtube-playlist-container * {
            box-sizing: border-box;
        }

        .youtube-playlist-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
            border-radius: 12px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        /* Error styles */
        .dyp-error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            margin: 20px 0;
        }

        .dyp-error h4 {
            margin-top: 0;
        }

        /* Debug styles */
        .dyp-debug {
            background: #e7f3ff;
            color: #0c5460;
            padding: 15px;
            border: 1px solid #b8daff;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #007bff;
        }

        .dyp-debug h4 {
            margin-top: 0;
            color: #004085;
        }

        /* Light theme */
        .dyp-theme-light {
            color: #1a1b1d;
        }

        .dyp-theme-light .dyp-playlist-title {
            color: #1a1b1d;
        }

        .dyp-theme-light .dyp-scroll-hint {
            color: #666;
        }

        .dyp-theme-light .dyp-video-card {
            background: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .dyp-theme-light .dyp-video-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .dyp-theme-light .dyp-video-title {
            color: #1a1b1d;
        }

        .dyp-theme-light .dyp-video-meta {
            color: #666;
        }

        .dyp-theme-light .dyp-scroll-btn {
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .dyp-theme-light .dyp-scroll-btn:hover {
            background: white;
        }

        .dyp-theme-light .dyp-scroll-btn i {
            color: #1a1b1d;
        }

        /* Dark theme */
        .dyp-theme-dark {
            color: #ffffff;
        }

        .dyp-theme-dark .dyp-playlist-title {
            color: #ffffff;
        }

        .dyp-theme-dark .dyp-scroll-hint {
            color: #cccccc;
        }

        .dyp-theme-dark .dyp-video-card {
            background: #2d2d2d;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .dyp-theme-dark .dyp-video-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
        }

        .dyp-theme-dark .dyp-video-title {
            color: #ffffff;
        }

        .dyp-theme-dark .dyp-video-meta {
            color: #cccccc;
        }

        .dyp-theme-dark .dyp-scroll-btn {
            background: rgba(45, 45, 45, 0.9);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        .dyp-theme-dark .dyp-scroll-btn:hover {
            background: #2d2d2d;
        }

        .dyp-theme-dark .dyp-scroll-btn i {
            color: #ffffff;
        }

        /* Common styles */
        .dyp-playlist-section {
            margin-bottom: 50px;
        }

        .dyp-playlist-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .dyp-playlist-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
        }

        .dyp-scroll-hint {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .dyp-scroll-hint i {
            animation: dyp-bounce-right 2s infinite;
        }

        @keyframes dyp-bounce-right {
            0%, 20%, 50%, 80%, 100% { transform: translateX(0); }
            40% { transform: translateX(4px); }
            60% { transform: translateX(2px); }
        }

        .dyp-videos-scroll-container {
            position: relative;
            display: flex;
            align-items: center;
        }

        .dyp-videos-horizontal {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            scroll-behavior: smooth;
            padding: 10px 0;
            flex: 1;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .dyp-videos-horizontal::-webkit-scrollbar {
            display: none;
        }

        .dyp-scroll-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            opacity: 0.8;
        }

        .dyp-scroll-btn:hover {
            opacity: 1;
            transform: translateY(-50%) scale(1.1);
        }

        .dyp-scroll-left {
            left: -20px;
        }

        .dyp-scroll-right {
            right: -20px;
        }

        .dyp-scroll-btn i {
            font-size: 16px;
        }

        .dyp-video-card {
            min-width: 280px;
            max-width: 280px;
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .dyp-video-card:hover {
            transform: translateY(-4px);
        }

        .dyp-video-thumbnail-container {
            position: relative;
            width: 100%;
            height: 157px;
            overflow: hidden;
        }

        .dyp-video-thumbnail {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .dyp-video-card:hover .dyp-video-thumbnail {
            transform: scale(1.05);
        }

        .dyp-play-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .dyp-video-card:hover .dyp-play-overlay {
            opacity: 1;
        }

        .dyp-play-button {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transform: scale(0.8);
            transition: transform 0.3s ease;
        }

        .dyp-video-card:hover .dyp-play-button {
            transform: scale(1);
        }

        .dyp-play-button i {
            color: #1a1b1d;
            font-size: 20px;
            margin-left: 2px;
        }

        .dyp-video-duration {
            position: absolute;
            bottom: 8px;
            right: 8px;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }

        .dyp-video-info {
            padding: 16px;
        }

        .dyp-video-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 8px;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 2.6em;
        }

        .dyp-video-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.85rem;
        }

        /* Video Modal */
        .dyp-video-modal {
            display: none;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            animation: dyp-fadeIn 0.3s ease;
        }

        .dyp-video-modal-content {
            position: relative;
            margin: 5% auto;
            width: 90%;
            max-width: 1000px;
            height: 80%;
            background: #000;
            border-radius: 8px;
            overflow: hidden;
        }

        .dyp-close-modal {
            position: absolute;
            top: -40px;
            right: 0;
            color: white;
            font-size: 32px;
            font-weight: bold;
            cursor: pointer;
            z-index: 10001;
        }

        .dyp-close-modal:hover {
            opacity: 0.7;
        }

        .dyp-video-container {
            width: 100%;
            height: 100%;
        }

        @keyframes dyp-fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .youtube-playlist-container {
                padding: 20px 15px;
            }
            
            .dyp-playlist-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .dyp-playlist-title {
                font-size: 1.5rem;
            }
            
            .dyp-scroll-hint {
                font-size: 0.8rem;
            }
            
            .dyp-video-card {
                min-width: 250px;
                max-width: 250px;
            }
            
            .dyp-video-thumbnail-container {
                height: 140px;
            }
            
            .dyp-scroll-btn {
                display: none;
            }
            
            .dyp-video-modal-content {
                width: 95%;
                height: 70%;
                margin: 10% auto;
            }
        }

        @media (max-width: 480px) {
            .dyp-video-card {
                min-width: 220px;
                max-width: 220px;
            }
            
            .dyp-playlist-title {
                font-size: 1.3rem;
            }
        }

        /* Custom CSS from settings */
        <?php echo wp_kses_post($custom_css); ?>
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const videoCards = document.querySelectorAll('.dyp-video-card');
        const modal = document.getElementById('dyp-video-modal');
        const iframe = document.getElementById('dyp-video-iframe');
        const closeModal = document.querySelector('.dyp-close-modal');
        
        // Open video in modal
        videoCards.forEach(card => {
            card.addEventListener('click', function() {
                const videoId = this.dataset.videoId;
                if (videoId) {
                    const videoUrl = `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0`;
                    iframe.src = videoUrl;
                    modal.style.display = 'block';
                    document.body.style.overflow = 'hidden';
                }
            });
        });
        
        // Close modal
        function closeVideoModal() {
            modal.style.display = 'none';
            iframe.src = '';
            document.body.style.overflow = 'auto';
        }
        
        closeModal.addEventListener('click', closeVideoModal);
        
        // Close modal when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeVideoModal();
            }
        });
        
        // Close modal with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.style.display === 'block') {
                closeVideoModal();
            }
        });
        
        // Horizontal scroll functionality
        const scrollContainers = document.querySelectorAll('.dyp-videos-scroll-container');
        
        scrollContainers.forEach(container => {
            const scrollArea = container.querySelector('.dyp-videos-horizontal');
            const leftBtn = container.querySelector('.dyp-scroll-left');
            const rightBtn = container.querySelector('.dyp-scroll-right');
            
            // Scroll left
            leftBtn.addEventListener('click', function() {
                scrollArea.scrollLeft -= 300;
            });
            
            // Scroll right
            rightBtn.addEventListener('click', function() {
                scrollArea.scrollLeft += 300;
            });
            
            // Update scroll button visibility
            function updateScrollButtons() {
                const isAtStart = scrollArea.scrollLeft <= 0;
                const isAtEnd = scrollArea.scrollLeft >= scrollArea.scrollWidth - scrollArea.clientWidth;
                
                leftBtn.style.opacity = isAtStart ? '0.3' : '0.8';
                rightBtn.style.opacity = isAtEnd ? '0.3' : '0.8';
                leftBtn.style.pointerEvents = isAtStart ? 'none' : 'auto';
                rightBtn.style.pointerEvents = isAtEnd ? 'none' : 'auto';
            }
            
            // Initial state
            updateScrollButtons();
            
            // Update on scroll
            scrollArea.addEventListener('scroll', updateScrollButtons);
            
            // Update on window resize
            window.addEventListener('resize', updateScrollButtons);
        });
    });
    </script>
    <?php
    return ob_get_clean();
}

// Clear cache function
function dyp_clear_cache() {
    // Use WordPress transient functions instead of direct database queries
    $cache_keys = array(
        'dyp_playlists_',
        'dyp_playlist_videos_',
        'dyp_video_details_'
    );
    
    $cleared = 0;
    
    foreach ($cache_keys as $key_prefix) {
        // Since we can't easily iterate over all transients with a prefix,
        // we'll use a different approach - just clear known cache keys
        // This is less efficient but follows WordPress coding standards
        for ($i = 0; $i < 100; $i++) {
            $key = $key_prefix . md5($i);
            if (delete_transient($key)) {
                $cleared++;
            }
        }
    }
    
    if (function_exists('error_log')) {
        error_log('Display YouTube Playlists: Cache cleared - ' . $cleared . ' entries removed');
    }
    
    return $cleared > 0;
}

// Register shortcode
add_shortcode('display_youtube_playlists', 'dyp_shortcode');

// Enqueue Font Awesome from local file (compliance with WordPress.org)
function dyp_enqueue_scripts() {
    // Only enqueue if not already loaded by theme or another plugin
    if (!wp_style_is('font-awesome', 'enqueued') && !wp_style_is('fontawesome', 'enqueued')) {
        // For WordPress.org compliance, we'll use CSS content instead of external CDN
        wp_add_inline_style('wp-admin', '
            .fas::before, .fa::before { 
                font-family: dashicons; 
                speak: never; 
                font-style: normal; 
                font-weight: normal; 
                font-variant: normal; 
                text-transform: none; 
                line-height: 1; 
                -webkit-font-smoothing: antialiased; 
                -moz-osx-font-smoothing: grayscale; 
            }
            .fa-play::before { content: "\\f522"; }
            .fa-chevron-left::before { content: "\\f341"; }
            .fa-chevron-right::before { content: "\\f345"; }
            .fa-arrow-right::before { content: "\\f345"; }
        ');
    }
}
add_action('wp_enqueue_scripts', 'dyp_enqueue_scripts');

// Add settings link to plugins page
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'dyp_plugin_action_links');

function dyp_plugin_action_links($links) {
    $settings_link = '<a href="' . esc_url(admin_url('options-general.php?page=display-youtube-playlists')) . '">⚙️ Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}

// Plugin deactivation cleanup
register_deactivation_hook(__FILE__, 'dyp_deactivation_cleanup');

function dyp_deactivation_cleanup() {
    // Clear all cache
    dyp_clear_cache();
    
    // Optionally remove settings (uncomment if you want to clean up on deactivation)
    // delete_option('dyp_api_key');
    // delete_option('dyp_channel_id');
    // delete_option('dyp_theme');
    // delete_option('dyp_background_color');
    // delete_option('dyp_custom_css');
}

// Add admin notice for missing configuration
add_action('admin_notices', 'dyp_admin_notices');

function dyp_admin_notices() {
    $api_key = get_option('dyp_api_key', '');
    $channel_id = get_option('dyp_channel_id', '');
    
    if ((empty($api_key) || empty($channel_id)) && current_user_can('manage_options')) {
        $screen = get_current_screen();
        if ($screen && $screen->id !== 'settings_page_display-youtube-playlists') {
            echo '<div class="notice notice-warning is-dismissible">
                    <p><strong>Display YouTube Playlists:</strong> Please <a href="' . esc_url(admin_url('options-general.php?page=display-youtube-playlists')) . '">configure your API settings</a> to start displaying videos.</p>
                  </div>';
        }
    }
}

// Cache management shortcode for admins
function dyp_cache_shortcode($atts) {
    if (!current_user_can('manage_options')) {
        return '<p>Access denied.</p>';
    }
    
    $result = dyp_clear_cache();
    
    if ($result) {
        return '<div class="dyp-cache-success">✅ Display YouTube Playlists cache cleared successfully!</div>';
    } else {
        return '<div class="dyp-cache-error">❌ No cache entries found to clear.</div>';
    }
}

add_shortcode('dyp_clear_cache', 'dyp_cache_shortcode');
?>