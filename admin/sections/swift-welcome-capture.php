<?php

function swift_welcome_capturecb() {
    wp_enqueue_style('swiftcloud-colorpicker-style', plugins_url('../css/spectrum.css', __FILE__), '', '', '');
    wp_enqueue_script('swiftcloud-colorpicker', plugins_url('../js/spectrum.js', __FILE__), array('jquery'), '', true);

    wp_enqueue_media();

    $args = array(
        'sort_order' => 'ASC',
        'sort_column' => 'post_title',
        'post_type' => 'page',
        'post_status' => 'publish'
    );
    $pages = get_pages($args);
    ?>
    <div class="wrap">
        <div class="inner_content">
            <h2>Welcome Capture</h2><hr/>
            <?php
            $swift_settings = get_option('swift_settings');
            if (isset($_POST['save_welcome_capture']) && wp_verify_nonce($_POST['save_welcome_capture'], 'save_welcome_capture')) {
                $swift_settings['enable_welcome_capture'] = isset($_POST['swift_settings']['enable_welcome_capture']) && !empty($_POST['swift_settings']['enable_welcome_capture']) ? sanitize_text_field($_POST['swift_settings']['enable_welcome_capture']) : 0;
                $swift_settings['show_on_post'] = (isset($_POST['swift_settings']['show_on_post']) && !empty($_POST['swift_settings']['show_on_post'])) ? sanitize_text_field($_POST['swift_settings']['show_on_post']) : 99;
                $swift_settings['show_on_pages'] = (isset($_POST['swift_settings']['show_on_pages']) && !empty($_POST['swift_settings']['show_on_pages'])) ? sanitize_text_field($_POST['swift_settings']['show_on_pages']) : 99;
                $swift_settings['dont_show_on'] = (isset($_POST['swift_settings']['dont_show_on']) && !empty($_POST['swift_settings']['dont_show_on'])) ? sanitize_text_or_array_field($_POST['swift_settings']['dont_show_on']) : '';
                $swift_settings['wc_form_id'] = sanitize_text_field($_POST['swift_settings']['wc_form_id']);
                $swift_settings['wc_form_btn_text'] = sanitize_text_field($_POST['swift_settings']['wc_form_btn_text']);
                $swift_settings['wc_popup_bg_togggle'] = isset($_POST['swift_settings']['wc_popup_bg_togggle']) && !empty($_POST['swift_settings']['wc_popup_bg_togggle']) ? 1 : 0;
                $swift_settings['wc_bg_color'] = sanitize_text_field($_POST['swift_settings']['wc_bg_color']);
                $swift_settings['wc_bg_img'] = sanitize_text_field($_POST['swift_settings']['wc_bg_img']);
                $swift_settings['wc_text_color'] = sanitize_text_field($_POST['swift_settings']['wc_text_color']);
                $swift_settings['wc_body_text_content'] = sanitize_text_field($_POST['swift_settings']['wc_body_text_content']);
                $swift_settings['welcome_capture_exclude_pages'] = (isset($_POST['swift_settings']['welcome_capture_exclude_pages']) && !empty($_POST['swift_settings']['welcome_capture_exclude_pages'])) ? sanitize_text_or_array_field($_POST['swift_settings']['welcome_capture_exclude_pages']) : "";

                $update = update_option('swift_settings', $swift_settings);
            }
            if (isset($update) && !empty($update)) {
                echo '<div id="message" class="updated below-h2"><p>Settings updated successfully!</p></div>';
            }
            ?>
            <form name="frm_welcome_capture" id="frm_welcome_capture" method="post" enctype="multipart/form-data">
                <table class="form-table">
                    <tr>
                        <th><label for="enable_welcome_capture">Welcome Capture</label></th>
                        <td>
                            <?php $wcOnOff = (isset($swift_settings['enable_welcome_capture']) && !empty($swift_settings['enable_welcome_capture']) && $swift_settings['enable_welcome_capture'] == 1 ? 'checked="checked"' : ""); ?>
                            <input type="checkbox" value="1" data-ontext="ON" data-offtext="OFF" name="swift_settings[enable_welcome_capture]" id="enable_welcome_capture" class="enable_welcome_capture" <?php echo $wcOnOff; ?>>
                        </td>
                    </tr>
                    <tr>
                        <th>Show on </th>
                        <td>
                            <?php
                            $checkedPosts = (!empty($swift_settings['show_on_post'])) ? (($swift_settings['show_on_post'] == 1) ? 'checked="checked"' : '') : 'checked="checked"';
                            $checkedPages = (!empty($swift_settings['show_on_pages'])) ? (($swift_settings['show_on_pages'] == 1) ? 'checked="checked"' : '') : 'checked="checked"';
                            $excludePage_toggle = (isset($swift_settings['show_on_pages']) && !empty($swift_settings['show_on_pages']) && $swift_settings['show_on_pages'] == 1) ? 'visibility: visible' : 'display:none';
                            ?>
                            Post <input type="checkbox" value="1" data-ontext="ON" data-offtext="OFF" name="swift_settings[show_on_post]" id="show_on5" class="enable_welcome_capture" <?php echo $checkedPosts; ?>>&nbsp;&nbsp;
                            Page <input type="checkbox" value="1" data-ontext="ON" data-offtext="OFF" name="swift_settings[show_on_pages]" id="show_on6" class="" <?php echo $checkedPages; ?>>
                        </td>
                    </tr>
                    <tr id="exclude_page_row" style="<?php echo $excludePage_toggle; ?>">
                        <th>Exclude pages</th>
                        <td>
                            <select multiple="multiple" name="swift_settings[welcome_capture_exclude_pages][]">
                                <?php
                                if ($pages) {
                                    foreach ($pages as $page) {
                                        $selected = '';
                                        if (!empty($swift_settings['welcome_capture_exclude_pages'])) {
                                            if (in_array($page->ID, $swift_settings['welcome_capture_exclude_pages'])) {
                                                $selected = 'selected="selected"';
                                            }
                                        }
                                        ?>
                                        <option  <?php echo $selected; ?> value="<?php echo $page->ID ?>"><?php echo $page->post_title ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Don't show on </th>
                        <td>
                            <?php
                            $checkedHome = $checkedBlog = $checked404 = $checkedCpt = "";
                            if (!empty($swift_settings['dont_show_on'])) {
                                $checkedHome = (in_array('home', $swift_settings['dont_show_on'])) ? 'checked="checked"' : '';
                                $checkedBlog = (in_array('blog', $swift_settings['dont_show_on']) ? 'checked="checked"' : '');
                                $checked404 = (in_array('404', $swift_settings['dont_show_on']) ? 'checked="checked"' : '');
                                $checkedCpt = (in_array('cpt', $swift_settings['dont_show_on']) ? 'checked="checked"' : '');
                            }
                            ?>
                            <label for="dont_show_on1"><input type="checkbox" id="dont_show_on1" name="swift_settings[dont_show_on][]" value="home" <?php echo $checkedHome; ?> />Home Page</label>&nbsp;&nbsp;
                            <label for="dont_show_on2"><input type="checkbox" id="dont_show_on2" name="swift_settings[dont_show_on][]" value="blog" <?php echo $checkedBlog; ?>/>Blog List / Category</label>&nbsp;&nbsp;
                            <label for="dont_show_on3"><input type="checkbox" id="dont_show_on3" name="swift_settings[dont_show_on][]" value="404"  <?php echo $checked404; ?>/>404 Page</label>&nbsp;&nbsp;
                            <label for="dont_show_on4"><input type="checkbox" id="dont_show_on4" name="swift_settings[dont_show_on][]" value="cpt"  <?php echo $checkedCpt; ?>/>Custom Post Type</label>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="wc_form_id">Form ID number</label></th>
                        <td><input type="text" id="wc_form_id" value="<?php echo isset($swift_settings['wc_form_id']) && !empty($swift_settings['wc_form_id']) ? esc_attr($swift_settings['wc_form_id']) : ""; ?>" class="" name="swift_settings[wc_form_id]"/></td>
                    </tr>
                    <tr>
                        <th><label for="wc_form_btn_text">Form Button Text</label></th>
                        <td><input type="text" id="wc_form_btn_text" value="<?php echo isset($swift_settings['wc_form_btn_text']) && !empty($swift_settings['wc_form_btn_text']) ? esc_attr($swift_settings['wc_form_btn_text']) : ""; ?>" class="" name="swift_settings[wc_form_btn_text]"/></td>
                    </tr>
                    <tr>
                        <th><label for="wc_bg_color">Popup background </label></th>
                        <td>
                            <?php $popupBg = (isset($swift_settings['wc_popup_bg_togggle']) && !empty($swift_settings['wc_popup_bg_togggle']) && $swift_settings['wc_popup_bg_togggle'] == 1 ? 'checked="checked"' : ""); ?>
                            <input type="checkbox" value="1" data-ontext="Color" data-offtext="Image" name="swift_settings[wc_popup_bg_togggle]" id="wc_popup_bg_togggle" class="wc_popup_bg_togggle" <?php echo $popupBg; ?>>
                        </td>
                    </tr>
                    <tr id="wc_opt_bg_color" style="<?php echo ((isset($swift_settings['wc_popup_bg_togggle']) && !empty($swift_settings['wc_popup_bg_togggle']) && $swift_settings['wc_popup_bg_togggle'] == "1") ? 'visibility: visible;' : 'display:none'); ?>">
                        <th><label for="wc_bg_color">Popup background color </label></th>
                        <td><input type="text" id="wc_bg_color" value="<?php echo (isset($swift_settings['wc_bg_color']) && !empty($swift_settings['wc_bg_color']) ? esc_attr($swift_settings['wc_bg_color']) : ""); ?>" class="" name="swift_settings[wc_bg_color]" placeholder="#F16334"/></td>
                    </tr>
                    <tr id="wc_opt_bg_img" style="<?php echo ((isset($swift_settings['wc_popup_bg_togggle']) && !empty($swift_settings['wc_popup_bg_togggle'])) ? 'display:none;' : 'visibility: visible;'); ?>">
                        <th><label for="wc_bg_img">Popup background image</label></th>
                        <td>
                            <input type="text" size="36" id="wc_bg_img" name="swift_settings[wc_bg_img]" value="<?php echo (isset($swift_settings['wc_bg_img']) && !empty($swift_settings['wc_bg_img']) ? esc_attr($swift_settings['wc_bg_img']) : ""); ?>" />
                            <input class="button primary upload_image" type="button" id="cc_uploadimage" value="Upload Image" />
                            <br />Enter a URL or upload an image
                        </td>
                    </tr>
                    <tr>
                        <th><label for="wc_text_color">Popup text color</label></th>
                        <td><input type="text" id="wc_text_color" value="<?php echo (isset($swift_settings['wc_text_color']) && !empty($swift_settings['wc_text_color']) ? ($swift_settings['wc_text_color']) : ""); ?>" class="" name="swift_settings[wc_text_color]" placeholder="#FFFFFF"/></td>
                    </tr>
                    <tr>
                        <th><label for="wc_body_text">Popup Body Text</label></th>
                        <td>
                            <input style="display:none;" type="radio" class="" name="wc_body_text" value="html_content" />
                            <?php
                            $settings = array('media_buttons' => true, 'quicktags' => true, 'textarea_name' => 'swift_settings[wc_body_text_content]',);
                            $welcome_content = isset($swift_settings['wc_body_text_content']) && !empty($swift_settings['wc_body_text_content']) ? stripslashes($swift_settings['wc_body_text_content']) : "";
                            wp_editor($welcome_content, 'wc_body_text_id', $settings);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <?php wp_nonce_field('save_welcome_capture', 'save_welcome_capture') ?>
                            <input type="submit" class="button button-primary" value="Save Changes" />
                            <input type="button" class="button button-primary" value="Preview" id="wc_preview_popup" />
                        </th>
                    </tr>
                </table>
            </form>
        </div>
        <?php
        if (isset($swift_settings['wc_popup_bg_togggle']) && !empty($swift_settings['wc_popup_bg_togggle']) && $swift_settings['wc_popup_bg_togggle'] == 1) {
            $prv_bg_color = !empty($swift_settings['wc_bg_color']) ? $swift_settings['wc_bg_color'] : '#f16334';
        } else {
            $prv_bg_color = !empty($swift_settings['wc_bg_img']) ? "url('" . $swift_settings['wc_bg_img'] . "') no-repeat 0 0;background-size:cover;" : '#f16334';
        }
        $prv_text_color = !empty($swift_settings['wc_text_color']) ? $swift_settings['wc_text_color'] : '#fff';
        ?>
        <div id="wc_prv_section" style="background:<?php echo $prv_bg_color; ?> ">
            <div class="wc_prv_close">
                <img src="<?php echo plugins_url('../images/popup-close.png', __FILE__); ?>" id="wc_prv_closed" alt="close" onclick="hide_prv_welcome_capture()"/>
            </div>
            <div class="wc_prv_inner">
                <div class="wc_prv_text" style="color:<?php echo $text_color; ?> ">
                    <?php echo stripslashes(esc_attr($swift_settings['wc_body_text_content'])); ?>
                </div>
                <div class="wc_prv_form">
                    <input class="name" type="text" name="name" id="name" placeholder="First name" />&nbsp;&nbsp;&nbsp;
                    <input class="email" id="email" type="email" required="" placeholder="Email address" name="email">&nbsp;&nbsp;&nbsp;
                    <button id="wc_prv_btn" type="button"><?php echo (!empty($swift_settings['wc_form_btn_text']) ? esc_attr($swift_settings['wc_form_btn_text']) : 'Submit'); ?></button>
                </div>
            </div>
        </div>

        <script type="text/javascript">
                    jQuery(document).ready(function($) {
                        $("#wc_body_text_id").css("background", $("#wc_bg_color").val());
                        $("#wc_body_text_id").css("color", $("#wc_text_color").val());

                        jQuery('.enable_welcome_capture').rcSwitcher();

                        //exclude pages show/hide
                        jQuery("#show_on6").rcSwitcher({
                        }).on({
                            'turnon.rcSwitcher': function(e, dataObj) {
                                jQuery("#exclude_page_row").fadeIn();
                            },
                            'turnoff.rcSwitcher': function(e, dataObj) {
                                jQuery("#exclude_page_row").fadeOut();
                            }
                        });

                        jQuery('.wc_popup_bg_togggle:checkbox').rcSwitcher({
                            width: 70,
                        }).on({
                            'turnon.rcSwitcher': function(e, dataObj) {
                                // to do on turning on a switch
                                jQuery("#wc_opt_bg_img").hide();
                                jQuery("#wc_opt_bg_color").show();
                            },
                            'turnoff.rcSwitcher': function(e, dataObj) {
                                // to do on turning off a switch
                                jQuery("#wc_opt_bg_color").hide();
                                jQuery("#wc_opt_bg_img").show();
                            },
                        });

                        //Bg upload
                        var custom_uploader;
                        var thisthis;

                        $('.upload_image').click(function(e) {
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
                        jQuery("#frm_welcome_capture").submit(function(e) {
                            $(".welcomeError").remove();
                            if (jQuery('.enable_welcome_capture:checkbox').is(':checked')) {
                                if (jQuery.trim(jQuery("#wc_form_id").val()) === '') {
                                    jQuery("#frm_welcome_capture").before('<div id="" class="error welcomeError"><p>Form ID is Required to Enable This Function. Please visit <a href="https://SwiftCRM.com?pr=92">SwiftCloud.AI</a> (free or paid accounts will work) to generate this form.</p></div>');
                                    jQuery("#wc_form_id").focus();
                                    e.preventDefault();
                                }
                            }
                        });

                        $("#wc_bg_color").spectrum({
                            preferredFormat: "hex",
                            color: "<?php echo (!empty($swift_settings['wc_bg_color']) ? $swift_settings['wc_bg_color'] : '#f16334'); ?>",
                            showAlpha: true,
                            showButtons: false
                        });
                        $("#wc_text_color").spectrum({
                            preferredFormat: "hex",
                            color: "<?php echo (!empty($swift_settings['wc_text_color']) ? $swift_settings['wc_text_color'] : '#fff'); ?>",
                            showAlpha: true,
                            showButtons: false
                        });
                        $("#wc_preview_popup").on('click', function() {
                            $("#wc_prv_section").fadeIn();
                            $("#wc_prv_section").css("background-color", $("#wc_bg_color").val());
                            $(".wc_prv_text").css("color", $("#wc_text_color").val());
                            $(".wc_prv_text").html($("#wc_body_text_id").val());
                            $("#wc_prv_btn").val($("#wc_form_btn_text").val());
                        });
                        $("#wc_bg_color").change(function() {
                            $("#wc_body_text_id").css("background", $(this).val());
                        });
                        $("#wc_text_color").change(function() {
                            $("#wc_body_text_id").css("color", $(this).val());
                        });
                    });
                    function hide_prv_welcome_capture() {
                        jQuery('#wc_prv_section').fadeOut();
                    }
        </script>
    </div>
    <?php
}