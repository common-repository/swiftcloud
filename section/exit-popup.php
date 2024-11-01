<?php

function swift_exit_popup() {
    wp_enqueue_script('swift-jquery-validate', plugins_url('../js/clipboard.min.js', __FILE__), array('jquery'), '', true);
    wp_enqueue_style('swiftcloud-fontawesome', plugins_url('../css/font-awesome.min.css', __FILE__), '', '');

    $swift_settings = get_option('swift_settings');
    $ajax_url = admin_url('admin-ajax.php');

    //Return if off
    if (!isset($swift_settings['enable_exit']) || empty($swift_settings['enable_exit']) || $swift_settings['enable_exit'] == 0 || $swift_settings['enable_exit'] == "")
        return;

    $contentFlag = !isset($swift_settings['exit_popup_content_flag']) ? 0 : 1; //0: form; 1: HTML
    $containerWidth = !empty($swift_settings['width2']) ? 'width:' . $swift_settings['width2'] . 'px;' : '480px';
    $containerHeight = !empty($swift_settings['height2']) ? 'height:' . $swift_settings['height2'] . 'px;' : '360px';
    $headline = isset($swift_settings['exit_popup_headline']) && !empty($swift_settings['exit_popup_headline']) ? $swift_settings['exit_popup_headline'] : "";
    ?>
    <div class="swiftcloud_modal" id="exit-popup" style="display: none;">
        <div class="swiftcloud_modal_container shake" style="<?php echo $containerWidth . " " . $containerHeight; ?>">
            <div class="swiftcloud_modal_close"><img src="<?php echo SWIFTCLOUD__PLUGIN_URL . "images/close.png"; ?>" alt="close" /></div>
            <?php if (!empty($headline)) { ?>
                <div class="swiftcloud_modal_header">
                    <img src="<?php echo SWIFTCLOUD__PLUGIN_URL . "images/stop.png"; ?>" class="exit_popup_stop_img" alt="close" /><h3><?php echo $headline; ?></h3>
                </div>
            <?php } ?>
            <div class="swiftcloud_modal_content">
                <div class="sc_modal_col-12">
                    <?php
                    if ($contentFlag == intval(0)) {
                        if (empty($swift_settings['form_id_exit'])) {
                            ?>
                            <p class="sc_modal_error">Heads up! Your form will not display until you add a form ID number.</p>
                        <?php } else { ?>
                            <form id="FrmSCExitPopup" method="post" name="FrmSCExitPopup">
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
                                    <input type="hidden" name="formid" value="<?php echo $swift_settings['form_id_exit']; ?>" id="formid" />
                                    <input type="hidden" name="vTags" id="vTags" value="#exitpopup">
                                    <input type="hidden" name="iSubscriber" value="817" >
                                    <input type="hidden" name="sc_referer_qstring" value="" id="sc_referer_qstring" />
                                    <?php wp_nonce_field('swift-cloud-exit-popup-nonce', 'sc_exit_popup_nonce'); ?>
                                    <button type="submit" name="submit_exitpopup" id="submit_exitpopup" class="sc_btn_orange" value="exit_popup">Submit</button>
                                </div>
                            </form>
                            <?php
                        }
                    } else if ($contentFlag == 1) {
                        echo nl2br(stripslashes($swift_settings['sc_exit_popup_content']));
                    }
                    ?>
                    <style type="text/css">
    <?php echo $swift_settings['exit_popup_custom_css']; ?>
                    </style>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            var ajax_url = "<?php echo $ajax_url; ?>";
            //CLOSE POPUP
            jQuery(".swiftcloud_modal_close,.close-exit-popup").on("click", function () {
                jQuery("#FrmSCExitPopup").trigger("reset");
                jQuery("#exit-popup").fadeOut();
            });

            //OPEN POPUP
            jQuery('body').mouseleave(function (e) {
                if (jQuery.cookie('dont_show_exit') != 1) {
                    jQuery.cookie('dont_show_exit', '1', {expires: 7, path: '/'});
                    jQuery("#FrmSCExitPopup").trigger("reset");
                    jQuery('#exit-popup').fadeIn();
                }
            });

            //SUBMIT POPUP FORM
            jQuery("form#FrmSCExitPopup").validate({
                submitHandler: function () {
                    jQuery("#submit_exitpopup").attr('disabled', 'disabled');
                    jQuery("#submit_exitpopup").after('<i class="sc-loader fa fa-spinner fa-pulse fa-lg fa-fw"></i>');
                    var data = {
                        "action": "swiftcloud_exit_popup",
                        "formData": jQuery("#FrmSCExitPopup").serialize(),
                        "sc_exit_popup_nonce": jQuery("#sc_exit_popup_nonce").val()
                    };
                    jQuery.post(ajax_url, data, function (response) {
                        jQuery(".sc-loader").remove();
                        jQuery("#submit_exitpopup").removeAttr('disabled');
                        if (response == 1) {
                            jQuery("#submit_exitpopup").after('<i class="fa fa-check fa-lg"></i>');
                            jQuery("#FrmSCExitPopup").trigger("reset");
                            jQuery(".swiftcloud_modal").fadeOut();
                        }
                    });
                },
                rules: {
                    name: "required",
                    email: {
                        required: true,
                        email: true,
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

            //set modal content height
            setTimeout(function () {
                var modal_conainer_height = jQuery("#exit-popup .swiftcloud_modal_container").height();
                var modal_header_height = jQuery("#exit-popup .swiftcloud_modal_header").height() + 33;
                var modal_content_height = jQuery("#exit-popup .swiftcloud_modal_content").outerHeight();
                if (modal_content_height >= modal_conainer_height) {
                    modal_content_height = modal_conainer_height - modal_header_height;
                    jQuery("#exit-popup .swiftcloud_modal_content").css('height', modal_content_height);
                    jQuery("#exit-popup .swiftcloud_modal_content").css('overflow-y', 'scroll');
                    jQuery("#exit-popup .swiftcloud_modal_content").css('margin-right', 0);
                }
            }, 2000);
        });
    </script>
    <?php
}

add_action('wp_footer', 'swift_exit_popup', 10);
?>
