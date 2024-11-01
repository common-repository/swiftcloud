<?php

function swiftcloud_inlineoffer_popup($atts, $content = null) {
    global $post;

    wp_enqueue_script('swift-jquery-validate', plugins_url('../js/clipboard.min.js', __FILE__), array('jquery'), '', true);
    wp_enqueue_style('swiftcloud-fontawesome', plugins_url('../css/font-awesome.min.css', __FILE__), '', '');

    $a = shortcode_atts(
            array(
        'bgcolor' => '',
        'id' => '',
        'form_id' => '',
        'popupheadline' => '',
        'popupimage' => '',
        'popupbutton' => ''
            ), $atts);
    extract($a);

    $background_color = "";
    $PopupHeadline = "";
    $PopupImage = "";
    $PopupButton = "";

    $background_color = !empty($bgcolor) ? "background-color: " . $bgcolor . ";" : "";
    $PopupHeadline = (!empty($popupheadline)) ? '<h3 class="sc_modal_title">' . $popupheadline . '</h3>' : '<h3>Sign Up</h3>';
    $PopupButton = (!empty($popupbutton)) ? $popupbutton : "Sign Up >>";
    $PopupImage = !empty($popupimage) ? '<img src="' . $popupimage . '" alt="popup_image" style="width:100%;"/>' : '';


    $popup = '';
    $popup .= '<div class="swiftcloud_modal inlineoffer_popup" id="inlineoffer_popup">
                <div class="swiftcloud_modal_container">
                    <div class="swiftcloud_modal_header">
                        <h3>' . $PopupHeadline . '</h3>
                        <div class="swiftcloud_modal_close"><i class="fa fa-times-circle-o fa-lg"></i></div>
                    </div>
                    <div class="swiftcloud_modal_content">
                        <div class="sc_modal_col-6">
                            ' . $PopupImage . '
                        </div>';

    $popup .= '<div class="sc_modal_col-6">';
    if (empty($form_id)) {
        $popup .= '<p class="sc_modal_error">Heads up! Your form will not display until you add a form ID number.</p>';
    } else {
        $popup .= '<form id="FrmSwiftCloudOffer" method="post" name="FrmSwiftCloudOffer" class="form-horizontal">
                    <div class="sc_modal_form_group">
                        <label for="offer_input_name" class="sc_modal_control_label">Name</label>
                        <input type="text" class="sc_modal_form_control" name="name" id="name" />
                    </div>
                    <div class="sc_modal_form_group">
                        <label for="offer_input_email" class="sc_modal_control_label">Email</label>
                        <input type="text" class="sc_modal_form_control" name="email" id="email" />
                    </div>
                    <div class="sc_modal_form_group sc_form_submit">
                        <input type="hidden" name="ip_address" id="ip_address" value="' . $_SERVER['SERVER_ADDR'] . '">
                        <input type="hidden" name="browser" id="SC_browser" value="' . $_SERVER['HTTP_USER_AGENT'] . '">
                        <input type="hidden" name="trackingvars" class="trackingvars" id="trackingvars" >
                        <input type="hidden" name="timezone" value="" id="SC_fh_timezone" class="SC_fh_timezone">
                        <input type="hidden" name="language" id="SC_fh_language" class="SC_fh_language" value="" >
                        <input type="hidden" name="capturepage" id="SC_fh_capturepage" class="SC_fh_capturepage" value="">
                        <input type="hidden" name="sc_lead_referer" id="sc_lead_referer" value=""/>
                        <input type="hidden" name="formid" value="' . $form_id . '" id="formid" />
                        <input type="hidden" name="vTags" id="vTags" value="#inlinepopup ">
                        <input type="hidden" name="iSubscriber" value="817" >
                        <input type="hidden" name="sc_referer_qstring" value="" id="sc_referer_qstring" />

                        ' . wp_nonce_field('swift-cloud-inline-nonce', 'sc_inlinepopup_nonce') . '
                        <button type="submit" name="submit_offer" id="submit_offer"  class="sc_btn_orange" value="inline_offer">' . $PopupButton . ' <i class="fa fa-send"></i></button>
                    </div>
                </form>';
    }
    $popup .= '           </div>
                    </div>
                </div>
            </div>';


    $returner .= '<div id="' . $id . '" class="clearfix inline_offer_wrap" style="' . $background_color . '">';
    $returner .= '<div class="inline-offer-content">' . do_shortcode($content) . '</div>';
    $returner .= $popup;
    $returner .= '</div>';

    $ajax_url = admin_url('admin-ajax.php');

    $returner .= '<script type="text/javascript">
                    jQuery(document).ready(function() {
                        //MODAL CLOSE
                        jQuery(".swiftcloud_modal_close").on("click",function(){
                            jQuery("#FrmSwiftCloudOffer").trigger("reset");
                            jQuery(".swiftcloud_modal").fadeOut();
                        });

                        //add modal trigger attribute in a link in content
                        jQuery(".inline-offer-content").find("a").attr("data-modal",".inlineoffer_popup");
                        jQuery(".inline-offer-content").find("a").addClass("swiftcloud_modal_open");

                        jQuery(".swiftcloud_modal_open").on("click",function(e){
                            var openModal = jQuery(this).attr("data-modal");
                            jQuery(openModal).fadeIn();
                            e.preventDefault();
                        });

                        jQuery("form#FrmSwiftCloudOffer").validate({
                            submitHandler: function() {
                                var data = {
                                        "action": "swiftcloud_inline_popup",
                                        "formData": jQuery("#FrmSwiftCloudOffer").serialize(),
                                        "sc_inlinepopup_nonce": jQuery("#sc_inlinepopup_nonce").val(),
                                        "current_post_id":' . $post->ID . '
                                    };

                                jQuery("#submit_offer i").removeClass("fa fa-send");
                                jQuery("#submit_offer i").addClass("fa fa-spinner fa-pulse fa-lg fa-fw");

                                jQuery.post("' . $ajax_url . '", data, function(response) {
                                    if(response==1){
                                        jQuery("#submit_offer i").removeClass("fa fa-spinner fa-pulse fa-lg fa-fw");
                                        jQuery("#submit_offer i").addClass("fa fa-send");

                                        jQuery("#FrmSwiftCloudOffer").trigger("reset");
                                        jQuery(".swiftcloud_modal").fadeOut();
                                        jQuery("#swiftcloud_captured_content_container").show();
                                    }
                                });
                            },
                            rules: {
                                name: "required",
                                email: {
                                    required: true,
                                    email: true,
                                },
                            },
                            messages: {
                                name: "Name is required.",
                                email: {
                                    required: "Email is required.",
                                    email: "Please enter a valid email address"
                                },
                            }
                        });
                    });
                </script>';
    return $returner;
}

add_shortcode("swiftcloud_inlineoffer", "swiftcloud_inlineoffer_popup");

/* Expand content */

function swiftcloud_inlineoffer_capturedcontents($atts, $content = null) {
    global $post;

    $show_content_flag = isset($_COOKIE['swift_inline_popup_flag_' . $post->ID]) && !empty($_COOKIE['swift_inline_popup_flag_' . $post->ID]) && $_COOKIE['swift_inline_popup_flag_' . $post->ID] == 1 ? 'block' : 'none';
    $returner .= '<div id="swiftcloud_captured_content_container" style="display:' . $show_content_flag . ';">' . $content . '</div>';

    return $returner;
}

add_shortcode("swiftcloud_inlineoffer_capturedcontents", "swiftcloud_inlineoffer_capturedcontents");

/* Send popup form to SwiftForm */
add_action('wp_ajax_swiftcloud_inline_popup', 'swiftcloud_inline_popup_callback');
add_action('wp_ajax_nopriv_swiftcloud_inline_popup', 'swiftcloud_inline_popup_callback');

function swiftcloud_inline_popup_callback() {
    check_ajax_referer('swift-cloud-inline-nonce', 'sc_inlinepopup_nonce');
    parse_str(sanitize_text_field($_POST['formData']), $form_data);
    $current_post_id = sanitize_text_field($_POST['current_post_id']);
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
    setcookie('swift_inline_popup_flag_' . $current_post_id, '1', time() + (10 * 365 * 24 * 60 * 60), "/", '');
    echo "1";
    wp_die();
}

?>
