<?php

ob_start();
if ((function_exists('session_status') && session_status() !== PHP_SESSION_ACTIVE) || !session_id()) {
    session_start();
}

// set referer

function skj_set_referer() {
    $referer = isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "";
    $referer_base_url = '';
    if (!empty($referer)) {
        $parts = parse_url($referer);

        if ($parts) {
            $referer_base_url = $parts["scheme"] . "://" . $parts["host"];
            if ($_SERVER['QUERY_STRING']) {
                $referer_q_string = "&" . $_SERVER['QUERY_STRING'];
            }
        }
    }
    if (home_url() != $referer_base_url) {
        // set refferer session
        if (isset($_SESSION['swift_referer'])) {
            unset($_SESSION['swift_referer']);
        }
        if (!empty($referer)) {
            $_SESSION['swift_referer'] = $referer;
        }
        // set qurey string session
        if (isset($_SESSION['swift_referer']) && !empty($_SESSION['swift_referer']) && !empty($referer_q_string)) {
            if (isset($_SESSION['swift_referer_qstring'])) {
                unset($_SESSION['swift_referer_qstring']);
            }
            $_SESSION['swift_referer_qstring'] = sanitize_text_field($referer_q_string);
        } else {
            unset($_SESSION['swift_referer_qstring']);
        }
    }
}

add_action('init', 'skj_set_referer');

function skj_append_referer() {
    $js = '';
    if (isset($_SESSION['swift_referer']) && !empty($_SESSION['swift_referer'])) {
        $js.= '<script type="text/javascript">jQuery(document).ready(function() {';
        $js.= 'jQuery("input[name=\'sc_lead_referer\']").val("' . $_SESSION['swift_referer'] . '");';

        if (isset($_SESSION['swift_referer_qstring']) && !empty($_SESSION['swift_referer_qstring'])) {
            $js.= 'jQuery("input[name=\'sc_referer_qstring\']").val("' . $_SESSION['swift_referer_qstring'] . '");';
        }
        $js.= '});</script>';
        echo ($js);
    }
}

add_action('wp_footer', 'skj_append_referer', 25);
?>