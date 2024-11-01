<?php

function swift_scroll_popup() {
    wp_enqueue_script('swift-jquery-validate', plugins_url('../js/clipboard.min.js', __FILE__), array('jquery'), '', true);
    wp_enqueue_style('swiftcloud-fontawesome', plugins_url('../css/font-awesome.min.css', __FILE__), '', '');

    $swift_settings = get_option('swift_settings');
    $ajax_url = admin_url('admin-ajax.php');

    //Return if not enabled
    if (!isset($swift_settings['enable_scroll']) || empty($swift_settings['enable_scroll']) || $swift_settings['enable_scroll'] == 0 || $swift_settings['enable_scroll'] == "")
        return;
//    if (isset($swift_settings) && !empty($swift_settings) && array_key_exists('enable_scroll', $swift_settings))
//        return;

    $scrollContentFlag = !isset($swift_settings['scroll_popup_content_flag']) ? 0 : 1; //0: form; 1: HTML
    $scrollContainerWidth = !empty($swift_settings['width1']) ? 'width:' . $swift_settings['width1'] . ';' : '';
    $scrollContainerHeight = !empty($swift_settings['height1']) ? 'height:' . $swift_settings['height1'] . ';' : '';
    $headline = isset($swift_settings['scroll_popup_headline']) && !empty($swift_settings['scroll_popup_headline']) ? $swift_settings['scroll_popup_headline'] : "";
    ?>
    <div class="swiftcloud_modal" id="scroll-popup" style="display: none;">
        <div class="swiftcloud_modal_container" style="<?php echo $scrollContainerWidth . " " . $scrollContainerHeight; ?>">
            <div class="swiftcloud_modal_close"><img src="<?php echo SWIFTCLOUD__PLUGIN_URL . "images/close.png"; ?>" alt="close" /></div>
            <?php if (!empty($headline)) { ?>
                <div class="swiftcloud_modal_header">
                    <h3><?php echo esc_html($headline); ?></h3>
                </div>
            <?php } ?>
            <div class="swiftcloud_modal_content">
                <div class="sc_modal_col-12">
                    <?php
                    if ($scrollContentFlag == intval(0)) {
                        if (empty($swift_settings['scroll_form_id'])) {
                            ?>
                            <p class="sc_modal_error">Heads up! Your form will not display until you add a form ID number.</p>
                        <?php } else { ?>
                            <form id="FrmSCScrollPopup" method="post" name="FrmSCScrollPopup">
                                <div class="sc_modal_form_group">
                                    <label for="name" class="sc_modal_control_label">Name</label>
                                    <input type="text" class="sc_modal_form_control" name="name" id="name" />
                                </div>
                                <div class="sc_modal_form_group">
                                    <label for="email" class="sc_modal_control_label">Email</label>
                                    <input type="text" class="sc_modal_form_control" name="email" id="email" />
                                </div>
                                <div class="sc_modal_form_group sc_form_submit">
                                    <input type="hidden" name="ip_address" id="ip_address" value="<?php echo $_SERVER['SERVER_ADDR']; ?>">
                                    <input type="hidden" name="browser" id="SC_browser" value="<?php echo $_SERVER['HTTP_USER_AGENT'] ?>">
                                    <input type="hidden" name="trackingvars" class="trackingvars" id="trackingvars" >
                                    <input type="hidden" name="timezone" value="" id="SC_fh_timezone" class="SC_fh_timezone">
                                    <input type="hidden" name="language" id="SC_fh_language" class="SC_fh_language" value="" >
                                    <input type="hidden" name="capturepage" id="SC_fh_capturepage" class="SC_fh_capturepage" value="">
                                    <input type="hidden" name="sc_lead_referer" id="sc_lead_referer" value=""/>
                                    <input type="hidden" name="formid" value="<?php echo $swift_settings['scroll_form_id']; ?>" id="formid" />
                                    <input type="hidden" name="vTags" id="vTags" value="#scrollpopup">
                                    <input type="hidden" name="iSubscriber" value="817" >
                                    <input type="hidden" name="sc_referer_qstring" value="" id="sc_referer_qstring" />
                                    <?php wp_nonce_field('swift-cloud-scroll-popup-nonce', 'sc_scroll_popup_nonce'); ?>
                                    <button type="submit" name="submit_scrollpopup" id="submit_scrollpopup" class="sc_btn_orange" value="scroll_popup">Submit</button>
                                </div>
                            </form>
                            <?php
                        }
                    } else if ($scrollContentFlag == 1) {
                        echo nl2br(esc_html($swift_settings['sc_scroll_popup_content']));
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            var ajax_url = "<?php echo $ajax_url; ?>";
            //OPEN POPUP
            jQuery(window).scroll(function(e) {
                if ($(window).scrollTop() >= ($(document).height() - $(window).height()) * 0.7) {
                    if (jQuery.cookie('dont_show_scroll') != 1) {
                        jQuery.cookie('dont_show_scroll', '1', {expires: 7, path: '/'});
                        jQuery("#FrmSCScrollPopup").trigger("reset");
                        jQuery('#scroll-popup').fadeIn();
                    }
                }
            });

            //CLOSE POPUP
            jQuery(".swiftcloud_modal_close").on("click", function() {
                jQuery("#FrmSCScrollPopup").trigger("reset");
                jQuery("#scroll-popup").fadeOut();
            });

            //SUBMIT POPUP FORM
            jQuery("form#FrmSCScrollPopup").validate({
                submitHandler: function() {
                    jQuery("#submit_scrollpopup").attr('disabled', 'disabled');
                    jQuery("#submit_scrollpopup").after('<i class="sc-loader fa fa-spinner fa-pulse fa-lg fa-fw"></i>');
                    var data = {
                        "action": "swiftcloud_scroll_popup",
                        "formData": jQuery("#FrmSCScrollPopup").serialize(),
                        "sc_scroll_popup_nonce": jQuery("#sc_scroll_popup_nonce").val()
                    };
                    jQuery.post(ajax_url, data, function(response) {
                        jQuery(".sc-loader").remove();
                        jQuery("#submit_scrollpopup").removeAttr('disabled');
                        if (response == 1) {
                            jQuery("#submit_scrollpopup").after('<i class="fa fa-check fa-lg"></i>');
                            jQuery("#FrmSCScrollPopup").trigger("reset");
                            jQuery(".swiftcloud_modal").fadeOut();
                        }
                    });
                },
                rules: {
                    name: "required",
                    email: {
                        required: true,
                        email: true
                    }
                },
                messages: {
                    name: "Name is required.",
                    email: {
                        required: "Email is required.",
                        email: "Please enter a valid email address"
                    }
                }
            });
        });
    </script>
    <?php
}

add_action('wp_footer', 'swift_scroll_popup', 10);
?>
