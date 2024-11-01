<?php
/*
 *      Display full screen welcome capture popup
 */

function swift_welcome_capture() {
    $swift_settings = get_option('swift_settings');

    if (!isset($_COOKIE['wc_popup_close'])) {
        //Return if not enabled
        if (!isset($swift_settings['enable_welcome_capture']) || empty($swift_settings['enable_welcome_capture']))
            return;

        if (!empty($swift_settings['dont_show_on'])) {
            if (in_array('home', $swift_settings['dont_show_on'])) {
                if (is_front_page() && is_home())
                    return;
                else if (is_front_page())
                    return;
            }
            if (in_array('blog', $swift_settings['dont_show_on'])) {
                if (is_home() || is_single())
                    return;
            }
            if (in_array('cpt', $swift_settings['dont_show_on'])) {
                if (is_custom_post_type()) {
                    return;
                }
            }
            if (in_array('404', $swift_settings['dont_show_on'])) {
                if (is_404())
                    return;
            }
        }

        if (!empty($swift_settings['show_on_pages']) && $swift_settings['show_on_pages'] != 99) {
            if (is_page() && is_array($swift_settings['welcome_capture_exclude_pages'])) {
                if (in_array(get_the_ID(), $swift_settings['welcome_capture_exclude_pages'])) {
                    return;
                }
            }
        }
        if (!empty($swift_settings['show_on_post']) && $swift_settings['show_on_post'] == 99) {
            if (is_single()) {
                return;
            }
        }

        if ($swift_settings['wc_popup_bg_togggle'] == 1) {
            $bg_color = !empty($swift_settings['wc_bg_color']) ? $swift_settings['wc_bg_color'] : '#f16334';
        } else {
            $bg_color = !empty($swift_settings['wc_bg_img']) ? "url('" . $swift_settings['wc_bg_img'] . "') no-repeat 0 0;background-size:cover;" : '#f16334';
        }
        $text_color = !empty($swift_settings['wc_text_color']) ? $swift_settings['wc_text_color'] : '#fff';
        ?>
        <div id="welcomeCapture" class="welcome_capture" style="background:<?php echo $bg_color; ?> ">
            <div class="wc_close">
                <img src="<?php echo plugins_url('../images/popup-close.png', __FILE__); ?>" alt="close" onclick="hide_welcome_capture()"/>
            </div>
            <div class="wc_inner">
                <div class="wc_text" style="color:<?php echo $text_color; ?> ">
                    <?php echo stripslashes(esc_html($swift_settings['wc_body_text_content'])); ?>
                </div>
                <div class="wc_form">
                    <form name="FrmWCPopup" method="post" action="https://portal.swiftcrm.com/f/fhx.php">
                        <input class="name" type="text" name="name" id="name" placeholder="First name" />&nbsp;&nbsp;&nbsp;
                        <input class="email" id="email" type="email" required="" placeholder="Email address" name="email">&nbsp;&nbsp;&nbsp;
                        <input value="<?php echo (!empty($swift_settings['wc_form_id']) ? $swift_settings['wc_form_id'] : ''); ?>" name="formid" id="formid" type="hidden">
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
                        <!--<input class="submit" type="submit" value="<?php echo (!empty($swift_settings['wc_form_btn_text']) ? $swift_settings['wc_form_btn_text'] : 'Submit'); ?>">-->
                        <div id="WCBtnContainer" style="display: inline"></div>
                        <script type="text/javascript">
                    var button = document.createElement("button");
                    button.innerHTML = '<?php echo (!empty($swift_settings['wc_form_btn_text']) ? $swift_settings['wc_form_btn_text'] : 'Submit'); ?>';
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
            <div class="wc_bottom_close">
                <a href="javascript:;" onclick="hide_welcome_capture()">&caron;</a>
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
                    function hide_welcome_capture() {
                        jQuery('#welcomeCapture').fadeOut();
                        jQuery.cookie('wc_popup_close', '1', {expires: null, path: '/'});
                    }
        </script>
        <?php
    }
}

add_action('wp_footer', 'swift_welcome_capture', 10);