<?php

function swift_track_result_cb() {
    ?>
    <div class="wrap">
        <div class="inner_content">
            <h2>Results Tracking Pass-Through</h2><hr/>
            <!-- HTML -->
            <h3><?php _e('Why use this:', 'swift-cloud'); ?></h3>
            <p><?php _e('This powerful shortcode can help you get clarity and insight into what advertising is working and what is not. It will help convert incoming tracking-variables such as Google Analytics into session variables, so that any leads captured will show exactly what advertising brought that person in.', 'swift-cloud'); ?></p>
            <p><?php _e('Normally this is lost when visitors click around a few pages on an individual basis, and on the Google Analytics side displays only macro trends, not specific results. Furthermore, for non real time lead-based sales, this will allow goal tracking so your advertising can be held accountable.', 'swift-cloud'); ?></p>
            <h3><?php _e('How to use this', 'swift-cloud'); ?></h3>
            <p>Drop <label><b>[swiftcloud_tracking utm_source='' utm_medium='' utm_term='' utm_content='' utm_campaign='' site='']</b></label> shortcode in link for tracking.</p>
            <ul>
                <li><b>utm_source:</b> Source name, Default site name</li>
                <li><b>utm_medium:</b> Required; action medium, Ex: Button, Email,Link</li>
                <li><b>utm_term:</b> Optional; Keyword </li>
                <li><b>utm_content:</b> Optional; Content</li>
                <li><b>utm_campaign:</b> Required; Campaign name </li>
                <li><b>site:</b> Site URL</li>
            </ul>
        </div>
    </div>
    <?php
}

/*
 *       [swiftcloud_tracking utm_source='' utm_medium='' utm_term='' utm_content='' utm_campaign='']
 *          - utm_source: Source name, Default site name
 *          - utm_medium: Required; action medium, Ex: Button, Email,Link
 *          - utm_term: Optional; Keyword
 *          - utm_content: Optional; Content
 *          - utm_campaign: Required; Campaign name
 *          - site: Site URL
 *
 */

function shortcode_sc_tracking($tracking_atts) {
    if (empty($tracking_atts['utm_medium']) && empty($tracking_atts['utm_campaign'])) {
        return;
    }
    $op = '';

    extract(shortcode_atts(array(
        'utm_source' => '',
        'utm_medium' => '',
        'utm_term' => '',
        'utm_content' => '',
        'utm_campaign' => '',
        'site' => ''
                    ), $tracking_atts));

    $skj_site_name = $_SERVER['SERVER_NAME'];

    $refferer = '';
    $referer_val = (isset($_SESSION['swift_referer']) && !empty($_SESSION['swift_referer'])) ? "&referer=" . $_SESSION['swift_referer'] : '';
    $referer_qstring = (isset($_SESSION['swift_referer_qstring']) && !empty($_SESSION['swift_referer_qstring'])) ? $_SESSION['swift_referer_qstring'] : '';
    $refferer = !empty($referer_val) ? $referer_val . $referer_qstring : '';

    $utm_source = !empty($utm_source) ? "&utm_source=$utm_source" : "&utm_source=$skj_site_name";
    $utm_term = !empty($utm_term) ? "&utm_term=$utm_term" : "";
    $utm_content = !empty($utm_content) ? "&utm_content=$utm_content" : "";
    $site = !empty($site) ? "&site=$site" : "&site=" . home_url();
    $product_id = (get_option("product_id")) ? "&pr=".get_option("product_id") : "";

    //output
    $op.="?a=$skj_site_name$refferer";
    $op.="$utm_source&utm_medium=$utm_medium$utm_term$utm_content&utm_campaign=$utm_campaign$site$product_id";

    return esc_attr($op);
}

add_shortcode('swiftcloud_tracking', 'shortcode_sc_tracking');
?>
