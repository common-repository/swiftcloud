<?php
/*
 *      Welcome capture multipage
 */
if (!function_exists('swift_welcome_capture_list_cb')) {

    function swift_welcome_capture_list_cb() {
        wp_enqueue_style('swiftcloud-colorpicker-style', plugins_url('../css/spectrum.css', __FILE__), '', '', '');
        wp_enqueue_script('swiftcloud-colorpicker', plugins_url('../js/spectrum.js', __FILE__), array('jquery'), '', true);

        wp_enqueue_style('sc-switch-css', SWIFTCLOUD__PLUGIN_URL . 'admin/css/sc_lc_switch.css', '', '', '');
        wp_enqueue_script('sc-switch-js', SWIFTCLOUD__PLUGIN_URL . 'admin/js/sc_lc_switch.min.js', array('jquery'), '', true);
        wp_enqueue_media();

        global $wpdb;
        $table_welcome_capture = $wpdb->prefix . 'swiftcloud_welcome_capture_list';
        ?>
        <div class="wrap">
            <div class="inner_content">
                <h2>Swift Welcome Capture List</h2><hr/>
                <?php
                if (isset($_GET['update']) && !empty($_GET['update'])) {
                    if ($_GET['update'] == 1) {
                        ?>
                        <div id="message" class="notice notice-success is-dismissible below-h2">
                            <p>Setting updated successfully.</p>
                        </div>
                        <?php
                    } else if ($_GET['update'] == 2) {
                        ?>
                        <div id="message" class="notice notice-success is-dismissible below-h2">
                            <p>Welcome Capture added successfully.</p>
                        </div>
                        <?php
                    } else if ($_GET['update'] == 3) {
                        ?>
                        <div id="message" class="notice notice-success is-dismissible below-h2">
                            <p>Welcome Capture deleted successfully.</p>
                        </div>
                        <?php
                    }
                }
                ?>
                <h2 class="nav-tab-wrapper" id="swift-wc-setting-tabs">
                    <a class="nav-tab custom-tab <?php echo (!isset($_GET['tab']) || $_GET['tab'] == "swiftcloud-wc-global-settings") ? 'nav-tab-active' : ''; ?>" id="swiftcloud-wc-global-settings-tab" href="#swiftcloud-wc-global-settings">Global Settings</a>
                    <a class="nav-tab custom-tab <?php echo (isset($_GET['tab']) && $_GET['tab'] == "swiftcloud-wc-specific-settings") ? 'nav-tab-active' : ''; ?>" id="swiftcloud-wc-specific-settings-tab" href="#swiftcloud-wc-specific-settings">Page Specific Welcome Captures</a>
                </h2>
                <div class="tabwrapper">
                    <div id="swiftcloud-wc-global-settings" class="panel <?php echo (!isset($_GET['tab']) || $_GET['tab'] == "swiftcloud-wc-global-settings") ? 'active' : ''; ?>">
                        <?php include 'swift-wc-global-settings.php'; ?>
                    </div>
                    <div id="swiftcloud-wc-specific-settings" class="panel <?php echo (isset($_GET['tab']) && $_GET['tab'] == "swiftcloud-wc-specific-settings") ? 'active' : ''; ?>">
                        <?php include 'swift-wc-specific-settings.php'; ?>
                    </div>
                </div>
            </div>

            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    // Aadd/Edit feed popup
                    jQuery(".swift-gwc-add-new,.swift-gwc-add-new-link,.swift_wc_edit").on('click', function(e) {
                        jQuery(".welcomeError").remove();

                        var modalID = jQuery(this).attr('data-modal');
                        var btnType = jQuery(this).attr('data-btn');
                        var modalTitleText = btnType === "edit" ? 'Edit Welcome Capture' : 'Add Welcome Capture';
                        var modalBtn = btnType === "edit" ? 'Update' : 'Add';

                        jQuery(".swift_gwc_title").text('');
                        jQuery(".swift_gwc_title").text(modalTitleText);
                        jQuery("#swift_gwc_submit").val(modalBtn);
                        jQuery("#swift_gwc_submit").text(modalBtn);

                        if (btnType === 'edit') {
                            e.preventDefault();
                            var wc_id = jQuery(this).attr('data-id');
                            var data = {
                                'action': 'swift_gwc_edit',
                                'data_id': wc_id,
                                'save_welcome_capture_list_options': jQuery('#save_welcome_capture_list_options').val()
                            };
                            jQuery.post(ajaxurl, data, function(response) {
                                var res = jQuery.parseJSON(response);

                                jQuery("#swift_wc_list_form_id").val(res['wc_form_id']);
                                jQuery("#swift_wc_list_form_btn_text").val(res['swift_wc_list_form_btn_text']);
                                jQuery("#swift_wc_list_form_btn_text").val(res['swift_wc_list_form_btn_text']);
                                jQuery("#wc_bg_img").val(res['swift_wc_list_bg_img']);

                                jQuery("#wp-swift_wc_list_body_text_id-wrap #swift_wc_list_body_text_id").html(res['swift_wc_list_content']);
                                jQuery("#wp-swift_wc_list_body_text_id-wrap #swift_wc_list_body_text_id").css("background", res['swift_wc_list_bg_color']);
                                jQuery("#wp-swift_wc_list_body_text_id-wrap #swift_wc_list_body_text_id").css("color", res['swift_wc_list_text_color']);

                                jQuery("#swift_wc_list_bg_color").spectrum("set", res['swift_wc_list_bg_color']);
                                jQuery("#swift_wc_list_text_color").spectrum("set", res['swift_wc_list_text_color']);

                                var bg_flag = res['swift_wc_list_bg_flag'];
                                if (bg_flag === '1') {
                                    jQuery('#swift_wc_list_bg').lcs_on();
                                    jQuery("#swift_wc_list_bg_color_wrap").fadeIn();
                                    jQuery("#swift_wc_list_bg_img_wrap").fadeOut();
                                } else {
                                    jQuery("#swift_wc_list_bg_img_wrap").fadeIn();
                                    jQuery("#swift_wc_list_bg_color_wrap").fadeOut();
                                }
                                jQuery("#swift_gwc_submit").before('<input type="hidden" name="wc_id" id="upd_wc_id" value="' + res['wc_id'] + '"  />');
                                jQuery(modalID).fadeIn();
                            });
                        } else {
                            jQuery(modalID).fadeIn();
                        }
                    });

                    //modal close
                    jQuery(".swift_gwc_close").on('click', function() {
                        jQuery('#frm_swift_gwc').trigger("reset");
                        jQuery(".welcomeError").remove();
                        jQuery("#swift_gwc_modal").fadeOut();
                    });

                    jQuery('.swift_global_welcome_capture_flag').lc_switch();
                    jQuery('body').delegate('.swift_global_welcome_capture_flag', 'lcs-on', function() {
                        jQuery(".global-capture-toggle").fadeIn();
                    });
                    jQuery('body').delegate('.swift_global_welcome_capture_flag', 'lcs-off', function() {
                        jQuery(".global-capture-toggle").fadeOut();
                    });


                    if (jQuery(".swift_wc_list_bg").length > 0) {
                        jQuery('.swift_wc_list_bg').lc_switch('Color', 'Image');
                        jQuery('body').delegate('.swift_wc_list_bg', 'lcs-on', function() {
                            jQuery("#swift_wc_list_bg_img_wrap").fadeOut();
                            jQuery("#swift_wc_list_bg_color_wrap").fadeIn();
                        });
                        jQuery('body').delegate('.swift_wc_list_bg', 'lcs-off', function() {
                            jQuery("#swift_wc_list_bg_color_wrap").fadeOut();
                            jQuery("#swift_wc_list_bg_img_wrap").fadeIn();
                        });
                    }

                    //Bg upload
                    var custom_uploader;
                    var thisthis;

                    jQuery('.upload_image').click(function(e) {
                        thisthis = $(this);
                        e.preventDefault();

                        //If the uploader object has already been created, reopen the dialog
                        if (custom_uploader) {
                            custom_uploader.open();
                            return;
                        }

                        //Extend the wp.media object
                        custom_uploader = wp.media.frames.file_frame = wp.media({
                            title: 'Choose Image',
                            button: {
                                text: 'Choose Image'
                            },
                            multiple: false
                        });

                        //When a file is selected, grab the URL and set it as the text field's value
                        custom_uploader.on('select', function() {
                            attachment = custom_uploader.state().get('selection').first().toJSON();
                            $(thisthis).parent().find('input[type="text"]').val(attachment.url);
                        });

                        //Open the uploader dialog
                        custom_uploader.open();
                    });

                    //form validation
                    jQuery(".welcomeError").remove();
                    jQuery("#frm_swift_gwc").submit(function(e) {
                        jQuery(".welcomeError").remove();
                        if (jQuery.trim(jQuery("#swift_wc_list_form_id").val()) === '') {
                            jQuery(".swift_gwc_content .form-table").before('<div id="" class="error welcomeError"><p>Form ID is Required to Enable This Function. Please visit <a href="https://SwiftCRM.com?pr=92">SwiftCloud.AI</a> (free or paid accounts will work) to generate this form.</p></div>');
                            jQuery("#swift_wc_list_form_id").focus();
                            e.preventDefault();
                        }
                    });

                    //Color picker
                    jQuery("#swift_wc_list_bg_color").spectrum({
                        preferredFormat: "hex",
                        color: "#f16334",
                        showAlpha: true,
                        showButtons: false,
                        showInput: true

                    });

                    jQuery("#swift_wc_list_text_color").spectrum({
                        preferredFormat: "hex",
                        color: "#fff",
                        showAlpha: true,
                        showButtons: false,
                        showInput: true
                    });

                    /* Delete Wc list */
                    jQuery(".swift_wc_delete").on('click', function(e) {
                        e.preventDefault();
                        if (confirm("Are you sure you want to delete this?")) {
                            var del_id = jQuery(this).attr('data-id');
                            if (del_id) {
                                jQuery(this).after('<input type="hidden" name="del_wc_id" value="' + del_id + '" />');
                                jQuery("#frm_wc_list").submit();
                            }
                        }
                    });
                });
                function hide_prv_welcome_capture() {
                    jQuery('#wc_prv_section').fadeOut();
                }
            </script>
        </div>
        <?php
    }

}


/*
 *      Get data
 *      Ajax callback functions
 */

/* edit feeds */
add_action('wp_ajax_swift_gwc_edit', 'swift_gwc_edit_callback');
add_action('wp_ajax_nopriv_imkt_edit_feeds', 'swift_gwc_edit_callback');
if (!function_exists('swift_gwc_edit_callback')) {

    function swift_gwc_edit_callback() {
        check_ajax_referer('save_welcome_capture_list_options', 'save_welcome_capture_list_options');
        if (isset($_POST['action']) && !empty($_POST['action']) && $_POST['action'] == 'swift_gwc_edit') {
            global $wpdb;
            $table_welcome_capture = $wpdb->prefix . 'swiftcloud_welcome_capture_list';

            $wc_id = sanitize_text_field($_POST['data_id']);

            if (!empty($wc_id)) {
                $get_wc_result = $wpdb->get_row("SELECT * FROM `$table_welcome_capture` WHERE `wc_id`=$wc_id", ARRAY_A);
                $new_get_wc_result['wc_id'] = esc_attr($get_wc_result['wc_id']);
                $new_get_wc_result['wc_headline'] = esc_attr($get_wc_result['wc_headline']);
                $new_get_wc_result['wc_form_id'] = esc_attr($get_wc_result['wc_form_id']);
                foreach (unserialize($get_wc_result['wc_data']) as $key => $wc_val) {
                    if ($key == 'swift_wc_list_content') {
                        $new_get_wc_result[$key] = stripslashes(esc_attr($wc_val));
                    } else {
                        $new_get_wc_result[$key] = esc_attr($wc_val);
                    }
                }
                print_r(json_encode($new_get_wc_result));
            }
        }
        wp_die();
    }

}

/* save data */
add_action("admin_init", "swift_wc_save_form_callback");
if (!function_exists('swift_wc_save_form_callback')) {

    function swift_wc_save_form_callback() {
        if (isset($_POST['save_welcome_capture_list_options']) && wp_verify_nonce($_POST['save_welcome_capture_list_options'], 'save_welcome_capture_list_options')) {

            global $wpdb;
            $table_welcome_capture = $wpdb->prefix . 'swiftcloud_welcome_capture_list';

            $swift_wc_form_id = sanitize_text_field($_POST['swift_wc_list_form_id']);
            $swift_welcome_list_options['swift_wc_list_form_btn_text'] = sanitize_text_field($_POST['swift_wc_list_form_btn_text']);
            $swift_welcome_list_options['swift_wc_list_bg_flag'] = sanitize_text_field((!empty($_POST['swift_wc_list_bg_flag'])) ? $_POST['swift_wc_list_bg_flag'] : 99);
            $swift_welcome_list_options['swift_wc_list_bg_img'] = sanitize_text_field($_POST['swift_wc_list_bg_img']);
            $swift_welcome_list_options['swift_wc_list_bg_color'] = sanitize_text_field($_POST['swift_wc_list_bg_color']);
            $swift_welcome_list_options['swift_wc_list_text_color'] = sanitize_text_field($_POST['swift_wc_list_text_color']);
            $swift_welcome_list_options['swift_wc_list_content'] = wp_kses_post($_POST['swift_wc_list_body_text']);
            $capture_data = serialize($swift_welcome_list_options);

            $headline = !empty($swift_welcome_list_options['swift_wc_list_content']) ? substr(strip_tags($swift_welcome_list_options['swift_wc_list_content']), 0, 30) . "...." : '';

            // Add
            if (isset($_POST['swift_gwc_submit']) && !empty($_POST['swift_gwc_submit']) && $_POST['swift_gwc_submit'] == 'Add') {
                $wc_insert = $wpdb->insert($table_welcome_capture, array(
                    'wc_headline' => $headline,
                    'wc_form_id' => $swift_wc_form_id,
                    'wc_data' => $capture_data,
                        ), array('%s', '%s', '%s')
                );

                if ($wc_insert) {
                    wp_redirect(admin_url("admin.php?page=swift_welcome_capture_list&tab=swiftcloud-wc-specific-settings&update=2"));
                    die;
                }
            }

            //update
            if (isset($_POST['swift_gwc_submit']) && !empty($_POST['swift_gwc_submit']) && $_POST['swift_gwc_submit'] == 'Update') {
                $upd_id = sanitize_text_field($_POST['wc_id']);

                $wc_update = $wpdb->update($table_welcome_capture, array(
                    'wc_headline' => $headline,
                    'wc_form_id' => $swift_wc_form_id,
                    'wc_data' => $capture_data,
                        )
                        , array('wc_id' => $upd_id), array('%s', '%s', '%s'), array('%d'));
                if ($wc_update) {
                    wp_redirect(admin_url("admin.php?page=swift_welcome_capture_list&tab=swiftcloud-wc-specific-settings&update=1"));
                    die;
                }
            }
        }


        /*
         *      Delete
         */
        if (isset($_POST['swift_wc_list_action']) && wp_verify_nonce($_POST['swift_wc_list_action'], 'swift_wc_list_action')) {
            global $wpdb;
            $table_welcome_capture = $wpdb->prefix . 'swiftcloud_welcome_capture_list';

            if (!empty($_POST['del_wc_id'])) {
                $del_wc_id = sanitize_text_field($_POST['del_wc_id']);

                $del_id = $wpdb->delete($table_welcome_capture, array('wc_id' => $del_wc_id), array('%d'));
            }
            if ($del_id) {
                wp_redirect(admin_url("admin.php?page=swift_welcome_capture_list&tab=swiftcloud-wc-specific-settings&update=3"));
                die;
            }
        }
    }

}