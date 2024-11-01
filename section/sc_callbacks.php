<?php

/*
 *      Ajax callback
 */

/* Send exit popup form to SwiftForm */
add_action('wp_ajax_swiftcloud_exit_popup', 'swiftcloud_exit_popup_callback');
add_action('wp_ajax_nopriv_swiftcloud_exit_popup', 'swiftcloud_exit_popup_callback');

if (!function_exists('swiftcloud_exit_popup_callback')) {

    function swiftcloud_exit_popup_callback() {
        check_ajax_referer('swift-cloud-exit-popup-nonce', 'sc_exit_popup_nonce');
        parse_str(sanitize_text_field($_POST['formData']), $form_data);
        $form_data['referer'] = home_url();
        $args = array(
            'body' => $form_data,
            'timeout' => '5',
            'redirection' => '5',
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array(),
            'cookies' => array(),
        );
        wp_remote_post('https://portal.swiftcrm.com/f/fhx.php', $args);

        echo "1";
        wp_die();
    }

}

/* Send scroll popup form to SwiftForm */
add_action('wp_ajax_swiftcloud_scroll_popup', 'swiftcloud_scroll_popup_callback');
add_action('wp_ajax_nopriv_swiftcloud_scroll_popup', 'swiftcloud_scroll_popup_callback');

if (!function_exists('swiftcloud_scroll_popup_callback')) {

    function swiftcloud_scroll_popup_callback() {
        check_ajax_referer('swift-cloud-scroll-popup-nonce', 'sc_scroll_popup_nonce');
        parse_str(sanitize_text_field($_POST['formData']), $form_data);
        $form_data['referer'] = home_url();
        $args = array(
            'body' => $form_data,
            'timeout' => '5',
            'redirection' => '5',
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array(),
            'cookies' => array(),
        );
        wp_remote_post('https://portal.swiftcrm.com/f/fhx.php', $args);
        echo "1";
        wp_die();
    }

}

/* Send timed popup form to SwiftForm */
add_action('wp_ajax_swiftcloud_timed_popup', 'swiftcloud_timed_popup_callback');
add_action('wp_ajax_nopriv_swiftcloud_timed_popup', 'swiftcloud_timed_popup_callback');

if (!function_exists('swiftcloud_timed_popup_callback')) {

    function swiftcloud_timed_popup_callback() {
        check_ajax_referer('swift-cloud-timed-popup-nonce', 'sc_timed_popup_nonce');
        parse_str(sanitize_text_field($_POST['formData']), $form_data);
        $form_data['referer'] = home_url();
        $args = array(
            'body' => $form_data,
            'timeout' => '5',
            'redirection' => '5',
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array(),
            'cookies' => array(),
        );
        wp_remote_post('https://portal.swiftcrm.com/f/fhx.php', $args);
        echo "1";
        wp_die();
    }

}
?>
