<?php

function sma_load_admin_scripts() {
    wp_enqueue_script('swiftcloud-timeago', plugins_url('/js/jquery.timeago.js', __FILE__), array('jquery'), '', true);
}

//Lod necessary javascript files and handlers
function sma_load_scripts() {

    wp_enqueue_script('sma-main', plugins_url('/js/sma.js', __FILE__), array('jquery'), '', true);
    $sma_settings = get_option('sma_settings');

    // Localize the script with new data
    $data = array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'form_id' => $sma_settings['form_id'],
        'file_field_id' => $sma_settings['file_field_id'],
        'name_field_id' => $sma_settings['name_field_id'],
        'email_field_id' => $sma_settings['email_field_id'],
        'phone_field_id' => $sma_settings['phone_field_id'],
        'submit_field_id' => $sma_settings['submit_field_id']
    );
    wp_localize_script('sma-main', 'sma_data', $data);
}

add_action('wp_enqueue_scripts', 'sma_load_scripts', 10);


include_once 'show-logs.php';
include_once 'log-setting-page.php';

/* Save Logs */

function sma_save_log_cb() {
    global $wpdb;
    $client_id = sanitize_text_field($_POST['client_id']);
    $table_name = $wpdb->prefix . "sma_log";

    if (isset($_COOKIE['sma_log_id'])) {
        $wpdb->update(
                $table_name, array(
            'filename' => $client_id,
            'date_time' => date('Y-m-d h:i:s'),
                ), array('id' => sanitize_text_field($_COOKIE['sma_log_id'])), array(
            '%s',
            '%s'
                ), array('%d')
        );
    } else {
        $wpdb->insert(
                $table_name, array(
            'filename' => $client_id,
            'date_time' => date('Y-m-d h:i:s'),
                ), array(
            '%s',
            '%s',
                )
        );
        $cookie_value = $wpdb->insert_id;
        setcookie('sma_log_id', $cookie_value, 0, "/");
    }
    echo esc_attr($_COOKIE['sma_log_id']);
    wp_die();
}

add_action('wp_ajax_sma_save_log', 'sma_save_log_cb');
add_action('wp_ajax_nopriv_sma_save_log', 'sma_save_log_cb');

function sma_save_log_name_cb() {
    global $wpdb;
    $client_name = sanitize_text_field($_POST['client_name']);
    $table_name = $wpdb->prefix . "sma_log";

    if (isset($_COOKIE['sma_log_id'])) {
        $wpdb->update(
                $table_name, array(
            'name' => $client_name,
            'date_time' => date('Y-m-d h:i:s'),
                ), array('id' => sanitize_text_field($_COOKIE['sma_log_id'])), array(
            '%s',
                ), array('%d')
        );
    } else {
        $wpdb->insert(
                $table_name, array(
            'name' => $client_name,
            'date_time' => date('Y-m-d h:i:s'),
                ), array(
            '%s',
            '%s',
                )
        );
        $cookie_value = $wpdb->insert_id;
        setcookie('sma_log_id', $cookie_value, 0, "/");
    }
    echo esc_attr($_COOKIE['sma_log_id']);
    wp_die();
}

add_action('wp_ajax_sma_save_log_name', 'sma_save_log_name_cb');
add_action('wp_ajax_nopriv_sma_save_log_name', 'sma_save_log_name_cb');

function sma_save_log_email_cb() {
    global $wpdb;
    $client_email = sanitize_text_field($_POST['client_email']);
    $table_name = $wpdb->prefix . "sma_log";

    if (isset($_COOKIE['sma_log_id'])) {
        $wpdb->update(
                $table_name, array(
            'email' => $client_email,
            'date_time' => date('Y-m-d h:i:s'),
                ), array('id' => sanitize_text_field($_COOKIE['sma_log_id'])), array(
            '%s',
                ), array('%d')
        );
    } else {
        $wpdb->insert(
                $table_name, array(
            'email' => $client_email,
            'date_time' => date('Y-m-d h:i:s'),
                ), array(
            '%s',
            '%s',
                )
        );
        $cookie_value = $wpdb->insert_id;
        setcookie('sma_log_id', $cookie_value, 0, "/");
    }
    echo esc_attr($_COOKIE['sma_log_id']);
    wp_die();
}

add_action('wp_ajax_sma_save_log_email', 'sma_save_log_email_cb');
add_action('wp_ajax_nopriv_sma_save_log_email', 'sma_save_log_email_cb');

function sma_save_log_phone_cb() {
    global $wpdb;
    $client_phone = sanitize_text_field($_POST['client_phone']);
    $table_name = $wpdb->prefix . "sma_log";

    if (isset($_COOKIE['sma_log_id'])) {
        $wpdb->update(
                $table_name, array(
            'phone' => $client_phone,
            'date_time' => date('Y-m-d h:i:s'),
                ), array('id' => sanitize_text_field($_COOKIE['sma_log_id'])), array(
            '%s',
            '%s',
                ), array('%d')
        );
    } else {
        $wpdb->insert(
                $table_name, array(
            'phone' => $client_phone,
            'date_time' => date('Y-m-d h:i:s'),
                ), array(
            '%s',
            '%s',
                )
        );
        $cookie_value = $wpdb->insert_id;
        setcookie('sma_log_id', $cookie_value, 0, "/");
    }

    echo esc_attr($_COOKIE['sma_log_id']);
    wp_die();
}

add_action('wp_ajax_sma_save_log_phone', 'sma_save_log_phone_cb');
add_action('wp_ajax_nopriv_sma_save_log_phone', 'sma_save_log_phone_cb');

function sma_save_log_complete_cb() {
    global $wpdb;
    $table_name = $wpdb->prefix . "sma_log";

    if (isset($_COOKIE['sma_log_id'])) {
        $wpdb->update(
                $table_name, array(
            'status' => 1,
            'date_time' => date('Y-m-d h:i:s'),
                ), array('id' => sanitize_text_field($_COOKIE['sma_log_id'])), array(
            '%d',
            '%s',
                ), array('%d')
        );
    }
    if (isset($_COOKIE['sma_log_id'])) {
        setcookie('sma_log_id', '', time() - 3600);
    }
    wp_die();
}

add_action('wp_ajax_sma_save_log_complete', 'sma_save_log_complete_cb');
add_action('wp_ajax_nopriv_sma_save_log_complete', 'sma_save_log_complete_cb');

/*
 *       get current page id for lead capture
 */

function sma_set_leadpage() {
    $page_id = sanitize_text_field($_POST['page_id']);
    if (isset($page_id) && !empty($page_id)) {
        setcookie('sma_lead_page_id', $page_id, 0, "/"); // end when session end
    }
    wp_die();
}

add_action('wp_ajax_sma_set_leadpage', 'sma_set_leadpage');
add_action('wp_ajax_nopriv_sma_set_leadpage', 'sma_set_leadpage');

function sma_save_local_capture() {
    $result['type'] = "fail";
    if (isset($_POST['action']) && !empty($_POST['action']) && $_POST['action'] == 'sma_save_local_capture') {
        global $wpdb;
        $table_name = $wpdb->prefix . "sma_log";

        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_text_field($_POST['email']);
        parse_str(sanitize_text_field($_POST['form_data']), $form_data);
        $serial_form_data = maybe_serialize($form_data);

        $wpdb->insert(
                $table_name, array(
            'name' => $name,
            'email' => $email,
            'form_data' => $serial_form_data,
            'date_time' => date('Y-m-d h:i:s'),
            'status' => 0
                ), array(
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
                )
        );
        $result['type'] = "success";
    }
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        $site_title = get_bloginfo('name');
        $subject = "New contact request has been received from " . $site_title;
        $body = 'New contact request has been received.<br><br>';
        $body .= 'Please check below details:<br><br>';

        if (isset($form_data) && !empty($form_data)) {
            foreach ($form_data as $form_key => $form_value) {
                $body .= $form_key . ': ' . $form_value . '<br>';
            }
        }

        $body .= '<br>From,<br>' . $site_title;
        $headers = array("Content-Type: text/html; charset=UTF-8", "From: " . $site_title . " <" . get_bloginfo('admin_email') . ">");
        wp_mail(get_bloginfo('admin_email'), $subject, $body, $headers);

        $result = json_encode($result);
        echo $result;
    } else {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    }
    wp_die();
}

add_action('wp_ajax_sma_save_local_capture', 'sma_save_local_capture');
add_action('wp_ajax_nopriv_sma_save_local_capture', 'sma_save_local_capture');
