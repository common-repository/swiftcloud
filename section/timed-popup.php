<?php

function swift_timed_popup() {
    wp_enqueue_script('swift-jquery-validate', plugins_url('../js/clipboard.min.js', __FILE__), array('jquery'), '', true);
    wp_enqueue_style('swiftcloud-fontawesome', plugins_url('../css/font-awesome.min.css', __FILE__), '', '');

    $swift_settings = get_option('swift_settings');
    $ajax_url = admin_url('admin-ajax.php');

    //Return if not enabled
    if (!isset($swift_settings['enable_time']) || empty($swift_settings['enable_time']) || $swift_settings['enable_time']==0 || $swift_settings['enable_time']=="")
        return;

    $timedContentFlag = $swift_settings['timed_popup_content_flag']; //0: form; 1: HTML
    $timedContainerWidth = !empty($swift_settings['width']) ? 'width:' . $swift_settings['width'] . ';' : '';
    $timedContainerHeight = !empty($swift_settings['height']) ? 'height:' . $swift_settings['height'] . ';' : '';
    $headline = $swift_settings['timed_popup_headline'];
    ?>

    <div class="swiftcloud_modal" id="timed-popup" style="display: none;">
        <div class="swiftcloud_modal_container" style="<?php echo $timedContainerWidth . " " . $timedContainerHeight; ?>">
            <div class="swiftcloud_modal_close"><img src="<?php echo SWIFTCLOUD__PLUGIN_URL . "images/close.png"; ?>" alt="close" /></div>
            <?php if (!empty($headline)) { ?>
                <div class="swiftcloud_modal_header">
                    <h3><?php echo esc_html($headline); ?></h3>
                </div>
            <?php } ?>
            <div class="swiftcloud_modal_content">
                <div class="sc_modal_col-12">
                    <?php
                    if ($timedContentFlag == intval(0)) {
                        if (empty($swift_settings['timed_form_id'])) {
                            ?>
                            <p class="sc_modal_error">Heads up! Your form will not display until you add a form ID number.</p>
                        <?php } else { ?>
                            <form id="FrmSCTimedPopup" method="post" name="FrmSCTimedPopup">
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
                                    <input type="hidden" name="formid" value="<?php echo $swift_settings['timed_form_id']; ?>" id="formid" />
                                    <input type="hidden" name="vTags" id="vTags" value="#timedpopup ">
                                    <input type="hidden" name="iSubscriber" value="817" >
                                    <input type="hidden" name="sc_referer_qstring" value="" id="sc_referer_qstring" />
                                    <?php wp_nonce_field('swift-cloud-timed-popup-nonce', 'sc_timed_popup_nonce'); ?>
                                    <button type="submit" name="submit_timepopup" id="submit_timepopup" class="sc_btn_orange" value="time_popup">Submit</button>
                                </div>
                            </form>
                            <?php
                        }
                    } else if ($timedContentFlag == 1) {
                        echo nl2br(esc_html($swift_settings['sc_timed_popup_content']));
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            var ajax_url = "<?php echo $ajax_url; ?>";

            //OPEN
            if (jQuery.cookie('dont_show_timed') != 1) {
                var $intrvl = <?php echo $swift_settings['delay'] ?> * 1000;
                openTimedbox($intrvl);
                jQuery.cookie('dont_show_timed', '1', {expires: 7, path: '/'});
            }

            //CLOSE POPUP
            jQuery(".swiftcloud_modal_close").on("click", function() {
                jQuery("#FrmSCTimedPopup").trigger("reset");
                jQuery("#timed-popup").fadeOut();
            });

            //SUBMIT POPUP FORM
            jQuery("form#FrmSCTimedPopup").validate({
                submitHandler: function() {
                    jQuery("#submit_timepopup").attr('disabled', 'disabled');
                    jQuery("#submit_timepopup").after('<i class="sc-loader fa fa-spinner fa-pulse fa-lg fa-fw"></i>');
                    var data = {
                        "action": "swiftcloud_timed_popup",
                        "formData": jQuery("#FrmSCTimedPopup").serialize(),
                        "sc_timed_popup_nonce": jQuery("#sc_timed_popup_nonce").val()
                    };
                    jQuery.post(ajax_url, data, function(response) {
                        jQuery(".sc-loader").remove();
                        jQuery("#submit_timepopup").removeAttr('disabled');
                        if (response == 1) {
                            jQuery("#submit_timepopup").after('<i class="fa fa-check fa-lg"></i>');
                            jQuery("#FrmSCTimedPopup").trigger("reset");
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
        function openTimedbox(interval) {
            setTimeout(function() {
                jQuery('#timed-popup').fadeIn();
            }, interval);
        }
    </script>
    <?php
}

add_action('wp_footer', 'swift_timed_popup', 10);
?>
