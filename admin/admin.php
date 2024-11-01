<?php

/**
 * Plugin file. This file should ideally be used to work with the
 * administrative side of the WordPress site.
 */
// Add the options page and menu item.
ob_start();
if ((function_exists('session_status') && session_status() !== PHP_SESSION_ACTIVE) || !session_id()) {
    session_start();
}

add_action('admin_menu', 'swift_control_panel');

function swift_control_panel() {
    $icon_url = plugins_url('/images/swiftcloud.png', __FILE__);

    $page_hook = add_menu_page($page_title = 'SwiftCloud', $menu_title = 'SwiftCloud', $capability = 'manage_options', $menu_slug = 'swift_control_panel', 'swift_control_panel', $icon_url, $position = null);

    add_submenu_page($menu_slug, "Help & Setup", "Help & Setup", 'manage_options', $menu_slug, 'swift_control_panel_cb');
    add_submenu_page($menu_slug, "Timed", "Timed Popup", 'manage_options', "swift_popup_timed", 'swift_popup_timed_cb');
    add_submenu_page($menu_slug, "Scroll", "Scroll Popup", 'manage_options', "swift_popup_scroll", 'swift_popup_scroll_cb');
    add_submenu_page($menu_slug, "Exit", "Exit Popup", 'manage_options', "swift_popup_exit", 'swift_popup_exit_cb');
    add_submenu_page($menu_slug, "Lead Scoring", "Lead Scoring", 'manage_options', "swift_lead_scoring", 'swift_lead_scoring_cb');
    add_submenu_page($menu_slug, "Chat", "Chat", 'manage_options', "swift_live_chat", 'swift_live_chat_cb');
    add_submenu_page($menu_slug, "Multipass", "Multipass", 'manage_options', "swift_multipass", 'swift_multipass_cb');
    add_submenu_page($menu_slug, "Welcome Capture", "Welcome Capture", 'manage_options', "swift_welcome_capture", 'swift_welcome_capturecb');
    add_submenu_page($menu_slug, "Welcome Capture List", "Welcome Capture List", 'manage_options', "swift_welcome_capture_list", 'swift_welcome_capture_list_cb');
    add_submenu_page($menu_slug, "Call to Action Box", "Call to Action Box", 'manage_options', "swift_cta_box", 'swift_swift_cta_boxcb');
    add_submenu_page($menu_slug, "Polling", "Polling", 'manage_options', "swift_polling", 'swift_polling_cb');
    add_submenu_page($menu_slug, "Social", "Social", 'manage_options', "swift_social", 'swift_social_cb');
    add_submenu_page($menu_slug, "Track Results", "Track Results", 'manage_options', "swift_track_results", 'swift_track_result_cb');
    add_submenu_page($menu_slug, "Inline Offer Popup", "Inline Offer Popup", 'manage_options', "swift_inlineoffer_popup", 'swift_inlineoffer_popup_cb');

    //log page
    $page_hook_suffix = add_submenu_page('swift_control_panel', 'Local Form Capture', 'Local Form Capture', 'manage_options', 'sma_admin_dispplay_log', 'sma_admin_dispplay_log');
    add_submenu_page('swift_control_panel', 'Local Capture Settings', 'Local Capture Settings', 'manage_options', 'sma_admin_dispplay_log_settings', 'sma_admin_dispplay_log_settings');
    add_submenu_page("", "Log Detail", "Log Detail", 'manage_options', 'sma_admin_display_log_details', 'sma_admin_display_log_details');

    add_action('admin_print_scripts-' . $page_hook_suffix, 'sma_load_admin_scripts');
    //end log page

    add_submenu_page($menu_slug, "Updates & Tips", "Updates & Tips", 'manage_options', 'swift_dashboard', 'swift_dashboard_cb');
//styles and scripts
}

function my_enqueue($hook) {
    wp_enqueue_style('swift_cloud_admin_style', plugins_url('/css/sc_admin.css', __FILE__), '', '', '');
    wp_enqueue_style('swift-toggle-style', plugins_url('/css/sc_rcswitcher.css', __FILE__), '', '', '');
    wp_enqueue_script('swift-toggle', plugins_url('/js/sc_rcswitcher.js', __FILE__), array('jquery'), '', true);
    wp_enqueue_script('swift-cloud-toggle-custom', plugins_url('/js/sc_admin.js', __FILE__), array('jquery'), '', true);
}

add_action('admin_enqueue_scripts', 'my_enqueue');

include_once 'sections/swift_dashboard.php';
include_once 'sections/swift-control-panel.php';
include_once 'sections/swift-popup-timed.php';
include_once 'sections/swift-popup-scroll.php';
include_once 'sections/swift-popup-exit.php';
include_once 'sections/swift-lead-scoring.php';
include_once 'sections/swift-multipass.php';
include_once 'sections/swift-welcome-capture.php';
include_once 'sections/swift-welcome-capture-list.php';
include_once 'sections/swift-call-to-action.php';

include_once 'sections/live_chat.php';
include_once 'sections/polling.php';
include_once 'sections/sc_track_result.php';
include_once 'sections/sc_social.php';
include_once 'sections/swift-inlineoffer-popup.php';

include 'SwiftCloudLeadReport.php';

/** Other shortcode * */
/*
 *      Shortcode :[swiftcloud_confirmpage]
 */

function swiftcloudThanksPage_shortcode() {
    if (isset($_GET['c']) && !empty($_GET['c']) && isset($_GET['confirm']) && !empty($_GET['confirm']) && $_GET['confirm'] == 1) {
        $_SESSION['swiftcloud_capturedUser'] = sanitize_text_field($_GET['c']);
        if (isset($_SESSION['swiftcloud_redirectTo']) && !empty($_SESSION['swiftcloud_redirectTo'])) {
            echo esc_js('<script type="text/javascript">window.location.href="' . $_SESSION['swiftcloud_redirectTo'] . '"</script>');
        }
    }
}

add_shortcode('swiftcloud_confirmpage', 'swiftcloudThanksPage_shortcode');


/*
 *      Shortcode :[swiftcloud_welcome_name]
 *      - This shortcode display firtsname of user who successfuly registered in swiftform.
 *      - return : name
 */

function swiftcloud_welcome_name_shortcode() {
    if (isset($_SESSION['swiftcloud_welcome_name']) && !empty($_SESSION['swiftcloud_welcome_name'])) {
        return esc_attr($_SESSION['swiftcloud_welcome_name']);
    }
}

add_shortcode('swiftcloud_welcome_name', 'swiftcloud_welcome_name_shortcode');


/*
 *      This action get Firstname from swiftform returned url and set it to $_SESSION['swiftcloud_welcome_name'].
 *      ex. returned url: ?c=67233&confirm=1&firstname=BookSiteTEst; So the firstname is 'BookSiteTEst'.
 */
add_action('init', 'sc_get_first_name');

function sc_get_first_name() {
    if (isset($_GET['c']) && !empty($_GET['c']) && isset($_GET['confirm']) && !empty($_GET['confirm']) && $_GET['confirm'] == 1) {
        if (isset($_GET['firstname']) && !empty($_GET['firstname'])) {
            if (!isset($_COOKIE['onceCaptured'])) {
                setcookie("onceCaptured", 1, time() + 31556926, "/");
            }
            unset($_SESSION['swiftcloud_welcome_name']);
            $_SESSION['swiftcloud_welcome_name'] = ucfirst(esc_attr($_GET['firstname']));
        }
    }
}

/*
 *      Shortcode : [swiftcloud_topcapture bgimg="imgURL" bgcolor="bgColor" title="" videourl="URL" swiftformid="swift-form-id" imgurl="" btncaption=""]
 *      bgimg= add full url of image //set background image in page;
 *      bgcolor= add color code, ex: #ccc //set background color in page;
 *      title= add text to set title //set landing page title;
 *      videourl= add video url //set video in ifram;
 *      swiftformid= add swiftform id //set swift-form id;
 */

function swiftcloudTopcapture_shortcode($atts, $content = null) {
    wp_enqueue_style('sc-landingpage-style', plugins_url('/swiftcloud/css/landingpage.css'));
    $output = "";
    $scl = shortcode_atts(
            array(
        'bgimg' => '',
        'bgcolor' => '',
        'title' => '',
        'videourl' => '',
        'swiftformid' => '',
        'imgurl' => '',
        'btncaption' => ''
            ), $atts);
    extract($scl);
    //echo $bgimg . ' | ' . $bgcolor . ' | ' . $title . ' | ' . $videourl;

    $bgStyle = !empty($bgimg) ? 'url(' . $bgimg . ') no-repeat fixed center center / cover transparent' : $bgcolor;

    $output = '<div class="sc-landing-main" style="background:' . $bgStyle . ';"><div class="sc-wrapper">';
    $output .= '<div id="sc-landingpage"><div class="sc-content">';
    // title
    $output .= '<h1 class="sc-lending-title">' . $title . '</h1>';
    $output .= '<div class="pnl-left"><div class="form">';
    //video url
    if (!empty($videourl)) {
        $output .= '<iframe class="sc-landing-ifram"  height="315" src="' . $videourl . '" frameborder="0" allowfullscreen=""></iframe>';
    } elseif (!empty($imgurl)) {
        $output .= '<img class="sc-img" src="' . $imgurl . '" alt="' . $title . '"/>';
    }
    $output .= '</div></div>'; //left-part end
    $output .= '<div class="pnl-right"><div class="textElement" id="pnl-sc-landinpage-field">';
    if (!empty($swiftformid)) {
        $output .= do_shortcode('[swiftform id="' . $swiftformid . '"]');
    }
    $output .= '</div></div></div>'; //right-part end
    $output .= '<div class="sc-terms-wrap"><ul class="sc-landing-copyterms">';
    $output .= '<li>Copyright &copy; ' . date('Y') . '</li>';
    $output .= '</ul></div>'; //footer end
    $output .= '</div></div></div>';
    $output .= "<script type='text/javascript'>$(document).ready(function(){";
    if (!empty($btncaption)) {
        $output .= " $('#form_submit_btn').val('" . $btncaption . "'); ";
    }
    $output .= " $('head').append('<title>" . $title . "</title>') });</script>";

    return $output;
}

add_shortcode('swiftcloud_topcapture', 'swiftcloudTopcapture_shortcode');

if (!function_exists('sanitize_text_or_array_field')) {

    function sanitize_text_or_array_field($array_or_string) {
        if (is_string($array_or_string)) {
            $array_or_string = sanitize_text_field($array_or_string);
        } elseif (is_array($array_or_string)) {
            foreach ($array_or_string as $key => &$value) {
                if (is_array($value)) {
                    $value = sanitize_text_or_array_field($value);
                } else {
                    $value = sanitize_text_field($value);
                }
            }
        }

        return $array_or_string;
    }

}