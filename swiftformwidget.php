<?php

/*
  Plugin Name: SwiftCloud
  Plugin URL: https://SwiftCRM.Com/
  Description: Easy instant embed of https://SwiftCRM.Com?pr=92 forms via shortcode - example: [swiftform id="123] (replace the 123 with your form ID number), or Appearance >> Widgets.
  Version: 2.2
  Author: Roger Vaughn, Sajid Javed, Tejas Hapani
  Author URI: https://SwiftCRM.Com/
  Text Domain: swiftcloud
 */

// Make sure we don't expose any info if called directly
if (!function_exists('add_action')) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

define('SWIFTCLOUD_VERSION', '2.2');
define('SWIFTCLOUD__MINIMUM_WP_VERSION', '5.7');
define('SWIFTCLOUD__PLUGIN_URL', plugin_dir_url(__FILE__));
define('SWIFTCLOUD__PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SWIFTCLOUD_PLUGIN_PREFIX', 'swiftcloud_');

register_activation_hook(__FILE__, 'sma_install');

//Load admin modules
require_once('admin/admin.php');
require_once('online-forms/online-forms.php');

register_deactivation_hook(__FILE__, 'sma_uninstall');

function sma_uninstall() {
    wp_clear_scheduled_hook('swiftcloud_api_post');
}

function sma_install() {
    if (version_compare($GLOBALS['wp_version'], SWIFTCLOUD__MINIMUM_WP_VERSION, '<')) {
        add_action('admin_notices', 'swiftcloud_version_admin_notice');

        function swiftcloud_version_admin_notice() {
            echo '<div class="notice notice-error is-dismissible sc-admin-notice"><p>' . sprintf(esc_html__('SwiftCloud %s requires WordPress %s or higher.', 'swiftcloud'), SWIFTCLOUD_VERSION, SWIFTCLOUD__MINIMUM_WP_VERSION) . '</p></div>';
        }

        add_action('admin_init', 'swiftcloud_deactivate_self');

        function swiftcloud_deactivate_self() {
            if (isset($_GET["activate"]))
                unset($_GET["activate"]);
            deactivate_plugins(plugin_basename(__FILE__));
        }

        return;
    }

    update_option('sm_db_version', SWIFTCLOUD_VERSION);
    swiftcloud_pre_load_data();

    if (!wp_next_scheduled('swiftcloud_api_post')) {
        wp_schedule_event(time(), 'hourly', 'swiftcloud_api_post');
    }
}

add_action('plugins_loaded', 'sm_update_db_check');

function sm_update_db_check() {
    if (get_option("sm_db_version") != SWIFTCLOUD_VERSION) {
        sma_install();
    }
}

//Enqueue scripts and styles.
function swift_enqueue_scripts_styles() {
    wp_enqueue_style('swiftcloud-popup-custom', plugins_url('/css/public.css', __FILE__), '', '', '');
    wp_enqueue_script('swiftcloud-custom-script', plugins_url('/js/swiftcloud-custom-script.js', __FILE__), '', '', true);
    wp_enqueue_script('swiftcloud-cookie', plugins_url('/js/jquery.cookie.js', __FILE__), array('jquery'), '', true);
    wp_enqueue_script('swiftcloud-bootstrap', plugins_url('/js/bootstrap.min.js', __FILE__), array('jquery'), '3.3.5', true);
    wp_enqueue_script('swift-form-jstz', SWIFTCLOUD__PLUGIN_URL . "js/jstz.min.js", '', '', true);
}

add_action('wp_enqueue_scripts', 'swift_enqueue_scripts_styles');

include_once 'shortcode-generator/sc_shortcode_generator.php';
include_once 'section/swiftcloud_preload_data.php';
include_once 'section/swiftform-widget-init.php';
include_once 'section/timed-popup.php';
include_once 'section/scroll-popup.php';
include_once 'section/exit-popup.php';
include_once 'section/welcome-capture.php';
include_once 'section/welcome-capture-specific.php';
include_once 'section/polling-front-end.php';
include_once 'section/call_to_action_box.php';
include_once 'section/lead_scoring.php';
include_once 'section/track_result.php';
include_once 'section/social.php';
include_once 'section/inlineoffer-popup.php';
include_once 'section/sc_callbacks.php';
include_once 'section/live_chat.php';
include_once 'section/embed_form.php';

function curl_redirect_exec($ch, &$redirects, $curlopt_header = false) {
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $data = curl_exec($ch);

    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($http_code == 301 || $http_code == 302) {
        list($header) = explode("\r\n\r\n", $data, 2);

        $matches = array();
        preg_match("/(Location:|URI:)[^(\n)]*/", $header, $matches);
        $url = trim(str_replace($matches[1], "", $matches[0]));

        $url_parsed = parse_url($url);
        if (isset($url_parsed)) {
            curl_setopt($ch, CURLOPT_URL, $url);
            $redirects++;
            return curl_redirect_exec($ch, $redirects, $curlopt_header);
        }
    }

    if ($curlopt_header) {
        return $data;
    } else {
        list($body) = explode("\r\n\r\n", $data, 2);
        return $body;
    }
}

add_action('swiftcloud_api_post', 'do_swiftcloud_api_post');

function do_swiftcloud_api_post() {
    global $wpdb;
    $table_name = $wpdb->prefix . "sma_log";
    $fLog = $wpdb->get_results("SELECT * FROM $table_name WHERE status=0 ORDER BY `id` ASC LIMIT 1");
    if (isset($fLog[0]) && !empty($fLog[0])) {
        if (!empty($fLog[0]->form_data)) {
            $fData = @unserialize($fLog[0]->form_data);
            if (isset($fData) && !empty($fData)) {
                $sma_settings = get_option('sma_settings');
                $form_id = $sma_settings['swiftcloud_form_id'];
                if (!empty($form_id)) {
                    $fData['formid'] = $form_id;
                    $fData['referer'] = home_url();
                    $args = array(
                        'body' => $fData,
                        'timeout' => '5',
                        'redirection' => '5',
                        'httpversion' => '1.0',
                        'blocking' => true,
                        'headers' => array(),
                        'cookies' => array(),
                    );
                    wp_remote_post('https://portal.swiftcrm.com/f/fhx.php', $args);
                    $wpdb->update($table_name, array('status' => 1), array('id' => $fLog[0]->id), array('%d'), array('%d'));
                    echo "1";
                }
            }
        }
    }
}
