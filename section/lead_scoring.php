<?php

/*
 *      [swiftcloud_lead_scoring name="homebuyer" value="+50"]
 *      - This shortcode set cookie.
 *          - name  = cookie name
 *          - value = cookie value
 

function shortcode_sc_lead_scoring($ls_atts) {
    $op = '';
    extract(shortcode_atts(array('name' => '', 'value' => ''), $ls_atts));
    $cookie_name = "sc_lead_scoring";
    $cookie_value_arr = array();

    if (!empty($name) && !empty($value)) {
        if (isset($_COOKIE['sc_lead_scoring']) && !empty($_COOKIE['sc_lead_scoring'])) {
            $aa = stripslashes($_COOKIE['sc_lead_scoring']);
            $bb = unserialize($aa);
            $cookie_value_arr[$name] = $value;
            $cc = array_merge($bb, $cookie_value_arr);

            $finalval = serialize($cc);
            setcookie($cookie_name, $final_val, time() + 31556926, '/');
        } else {
            $cookie_value_arr[$name] = $value;

            $final_val = serialize($cookie_value_arr);
            setcookie($cookie_name, $final_val, time() + 31556926, '/');
        }
    }
}
*/
//add_shortcode('swiftcloud_lead_scoring', 'shortcode_sc_lead_scoring');
?>
