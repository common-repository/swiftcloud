<?php
/*
 *      Display full screen welcome capture popup
 *
 *      [swiftcloud_welcomecapture id="2"]
 */
add_shortcode("swiftcloud_welcomecapture", "swift_welcome_capture_specific");
if (!function_exists('swift_welcome_capture_specific')) {

    function swift_welcome_capture_specific($atts) {
        $op = '';
        $a = shortcode_atts(
                array(
            'id' => '',
                ), $atts);
        extract($a);
        $wc_popid = $id;
        if (empty($wc_popid)) {
            return;
        }

        if (!isset($_COOKIE['wc_specific_popup_close']) && empty($_COOKIE['wc_specific_popup_close'])) {
            $wc_specific_flag = get_option('swift_welcome_capture_list_flag', true);

            //Return if not enabled
            if ($wc_specific_flag == 99)
                return;

            global $wpdb;
            $table_welcome_capture = $wpdb->prefix . 'swiftcloud_welcome_capture_list';
            $wc_option = array();

            $wc_specific_dont_show_on = get_option('swift_wc_list_dont_show_on', true);
            $wc_specific_options = $wpdb->get_row("SELECT * FROM `$table_welcome_capture` WHERE `wc_id`=$wc_popid", ARRAY_A);

            if (!empty($wc_specific_options)) {
                /*  $wc_option :
                 *  - wc_id
                 *  - wc_headline
                 *  - wc_form_id
                 *  - swift_wc_list_form_btn_text
                 *  - swift_wc_list_bg_flag
                 *  - swift_wc_list_bg_img
                 *  - swift_wc_list_bg_color
                 *  - swift_wc_list_text_color
                 *  - swift_wc_list_content
                 */
                foreach ($wc_specific_options as $key => $wc_val) {
                    if ($key == 'wc_data') {
                        foreach (unserialize($wc_specific_options['wc_data']) as $key1 => $wc_val1) {
                            $wc_option[$key1] = $wc_val1;
                        }
                    } else {
                        $wc_option[$key] = $wc_val;
                    }
                }
            }

            if (!empty($wc_specific_dont_show_on)) {
                if (!empty($wc_specific_dont_show_on) && in_array('home', $wc_specific_dont_show_on)) {
                    if (is_front_page() && is_home())
                        return;
                    else if (is_front_page())
                        return;
                }
                if (!empty($wc_specific_dont_show_on) && in_array('blog', $wc_specific_dont_show_on)) {
                    if (is_home() || is_single())
                        return;
                }
                if (!empty($wc_specific_dont_show_on) && in_array('cpt', $wc_specific_dont_show_on)) {
                    if (is_custom_post_type()) {
                        return;
                    }
                }
                if (!empty($wc_specific_dont_show_on) && in_array('404', $wc_specific_dont_show_on)) {
                    if (is_404())
                        return;
                }
            }

            if ($wc_option['swift_wc_list_bg_flag'] == 1) {
                $bg_color = !empty($wc_option['swift_wc_list_bg_color']) ? $wc_option['swift_wc_list_bg_color'] : '#f16334';
            } else {
                $bg_color = !empty($wc_option['swift_wc_list_bg_img']) ? "url('" . $wc_option['swift_wc_list_bg_img'] . "') no-repeat 0 0;background-size:cover;" : '#f16334';
            }
            $text_color = !empty($wc_option['swift_wc_list_text_color']) ? $wc_option['swift_wc_list_text_color'] : '#fff';

            $wc_specific_form_id = (!empty($wc_option['wc_form_id']) ? $wc_option['wc_form_id'] : '');
            $wc_specific_btn_text = (!empty($wc_option['swift_wc_list_form_btn_text']) ? $wc_option['swift_wc_list_form_btn_text'] : 'Submit');
            ?>
            <div id="welcomeCaptureSpecific" class="welcome_capture_specific" style="background:<?php echo $bg_color; ?> ">
                <div class="wc_specific_close">
                    <img src="<?php echo plugins_url('../images/popup-close.png', __FILE__); ?>" alt="close" onclick="wc_hide_welcome_capture()"/>
                </div>
                <div class="wc_specific_inner">
                    <div class="wc_specific_text" style="color:<?php echo $text_color; ?> ">
                        <?php echo stripslashes(esc_html($wc_option['swift_wc_list_content'])); ?>
                    </div>
                    <div class="wc_specific_form">
                        <form name="FrmWCPopup" method="post" action="https://portal.swiftcrm.com/f/fhx.php">
                            <input class="name" type="text" name="name" id="name" placeholder="First name" />&nbsp;&nbsp;&nbsp;
                            <input class="email" id="email" type="email" required="" placeholder="Email address" name="email">&nbsp;&nbsp;&nbsp;
                            <input value="<?php echo $wc_specific_form_id; ?>" name="formid" id="formid" type="hidden">
                            <input id="SC_fh_timezone" name="timezone" type="hidden">
                            <input id="SC_fh_language" name="language" type="hidden">
                            <input id="SC_fh_capturepage" name="capturepage" type="hidden">
                            <input id="sc_lead_referer" type="hidden" value="" name="sc_lead_referer"/>
                            <input id="sc_referer_qstring" type="hidden" value="" name="sc_referer_qstring"/>
                            <input type="hidden" name="iSubscriber" value="817" >
                            <input type="hidden" name="vTags" id="vTags" value="#welcome-capture">
                            <input type="hidden" name="trackingvars" class="trackingvars" id="trackingvars" >
                            <input type="hidden" name="ip_address" id="ip_address" value="<?php echo $_SERVER['SERVER_ADDR']; ?>">
                            <input type="hidden" name="browser" id="SC_browser" value="<?php echo $_SERVER['HTTP_USER_AGENT']; ?>">
            <!--                            <input class="submit" type="submit" value="<?php echo $wc_specific_btn_text; ?>">-->
                            <div id="WCBtnContainer" style="display: inline"></div>
                            <script type="text/javascript">
                        var button = document.createElement("button");
                        button.innerHTML = '<?php echo $wc_specific_btn_text; ?>';
                        var body = document.getElementById("WCBtnContainer");
                        body.appendChild(button);
                        button.id = "WCBtnSubmit";
                        button.name = "WCBtnSubmit";
                        button.className = "submit";
                        button.value = 'submit';
                        button.type = 'submit';
                            </script>
                            <noscript>
                            <p style='color:red;font-size:18px;'>JavaScript must be enabled to submit this form. Please check your browser settings and reload this page to continue.</p>
                            </noscript>
                        </form>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                jQuery(document).ready(function() {
                    if (jQuery('#SC_fh_timezone').length > 0) {
                        jQuery('#SC_fh_timezone').val(jstz.determine().name());
                    }
                    if (jQuery('#SC_fh_capturepage').length > 0) {
                        jQuery('#SC_fh_capturepage').val(window.location.origin + window.location.pathname);
                    }
                    if (jQuery('#SC_fh_language').length > 0) {
                        jQuery('#SC_fh_language').val(window.navigator.userLanguage || window.navigator.language);
                    }
                    jQuery("#referer").val(document.URL);
                    /*check if cookie exists then add the values in variable*/
                    if (getCookie('compain_var')) {
                        jQuery('#trackingvars').val(getCookie('compain_var'));
                    }
                });
                function wc_hide_welcome_capture() {
                    jQuery('#welcomeCaptureSpecific').fadeOut();
                    jQuery.cookie('wc_specific_popup_close', '1', {expires: null, path: '/'});
                }
            </script>
            <?php
        }
    }

}