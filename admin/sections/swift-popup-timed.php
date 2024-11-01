<?php

function swift_popup_timed_cb() {

    /* Save settings */
    $swift_settings = get_option('swift_settings');
    if (isset($_POST['save_popups']) && wp_verify_nonce($_POST['save_popups'], 'save_popups')) {
        $swift_settings['delay'] = sanitize_text_field($_POST['swift_settings']['delay']);
        $swift_settings['width'] = sanitize_text_field($_POST['swift_settings']['width']);
        $swift_settings['height'] = sanitize_text_field($_POST['swift_settings']['height']);
        $swift_settings['enable_time'] = sanitize_text_field(!empty($_POST['swift_settings']['enable_time']) ? 1 : 0);
        $swift_settings['timed_form_id'] = sanitize_text_field($_POST['swift_settings']['timed_form_id']);
        $swift_settings['timed_popup_content_flag'] = sanitize_text_field(!empty($_POST['swift_settings']['timed_popup_content_flag']) ? 1 : 0);
        $swift_settings['sc_timed_popup_content'] = wp_kses_post($_POST['swift_settings']['sc_timed_popup_content']);
        $swift_settings['timed_popup_headline'] = sanitize_text_field($_POST['swift_settings']['timed_popup_headline']);

        $update = update_option('swift_settings', $swift_settings);
    }
    ?>
    <div class="wrap">
        <div class="inner_content">
            <h2>Timed Popup </h2><hr/>
            <?php
            if (isset($update) && !empty($update)) {
                echo '<div id="message" class="updated below-h2"><p>Settings updated successfully!</p></div>';
            }

            $timedPopupOnOff = (isset($swift_settings['enable_time']) && !empty($swift_settings['enable_time']) && $swift_settings['enable_time'] == 1 ? 'checked="checked"' : "");
            $timedPopupToggle = (isset($swift_settings['enable_time']) && !empty($swift_settings['enable_time']) && $swift_settings['enable_time'] == 1 ? 'display:block' : 'display:none');
            $contentFlag = (isset($swift_settings['timed_popup_content_flag']) && !empty($swift_settings['timed_popup_content_flag']) && $swift_settings['timed_popup_content_flag'] == 1 ? 'checked="checked"' : "");
            ?>
            <form method="post" action="" id="frmTimedPopup">
                <table class="form-table">
                    <tr>
                        <th><label >Currently, the popup is </label></th>
                        <td>
                            <input type="checkbox" value="1" data-ontext="ON" data-offtext="OFF" name="swift_settings[enable_time]" id="enable_time" class="enable_time" <?php echo $timedPopupOnOff; ?>>
                        </td>
                    </tr>
                </table>
                <table class="form-table toggle-table" style="<?php echo $timedPopupToggle; ?>">
                    <tr>
                        <th><label >Fire this popup after </label></th>
                        <td><input type="text" value="<?php echo (!empty($swift_settings['delay']) ? esc_attr($swift_settings['delay']) : '12'); ?>" class="" name="swift_settings[delay]"/> seconds</td>
                    </tr>
                    <tr>
                        <th><label >with a width of</label></th>
                        <td><input type="text" value="<?php echo (!empty($swift_settings['width']) ? esc_attr($swift_settings['width']) : '720px'); ?>" class="" name="swift_settings[width]"/> in pixels</td>
                    </tr>
                    <tr>
                        <th><label >and height</label></th>
                        <td><input type="text" value="<?php echo (!empty($swift_settings['height']) ? esc_attr($swift_settings['height']) : ''); ?>" class="" name="swift_settings[height]"/> in pixels.</td>
                    </tr>
                    <tr>
                        <th><label>Popup Headline</label></th>
                        <td><input type="text" value="<?php echo (isset($swift_settings['timed_popup_headline']) && !empty($swift_settings['timed_popup_headline']) ? $swift_settings['timed_popup_headline'] : ""); ?>" class="regular-text" name="swift_settings[timed_popup_headline]" /></td>
                    </tr>
                    <tr>
                        <th><label>Popup content </label></th>
                        <td>
                            <input type="checkbox" value="1" data-ontext="HTML" data-offtext="Form ID" name="swift_settings[timed_popup_content_flag]" id="timed_popup_content_flag" class="timed_popup_content_flag" <?php echo $contentFlag; ?>>
                        </td>
                    </tr>
                    <tr class="sc-content-formid" style="<?php echo (isset($swift_settings['timed_popup_content_flag']) && $swift_settings['timed_popup_content_flag'] == intval(0)) ? 'visibility: visible;' : 'display:none;'; ?>">
                        <th><label for="timed_popup_formID">My timed popup form ID # is</label></th>
                        <td>
                            <input type="text" value="<?php echo isset($swift_settings['timed_form_id']) && !empty($swift_settings['timed_form_id']) ? esc_attr($swift_settings['timed_form_id']) : ""; ?>" class="" id="timed_popup_formID" name="swift_settings[timed_form_id]"/>
                        </td>
                    </tr>
                    <tr class="sc-content-html" style="<?php echo (isset($swift_settings['timed_popup_content_flag']) && $swift_settings['timed_popup_content_flag'] == 1) ? 'visibility: visible;' : 'display:none;'; ?>">
                        <th><label for="timed_popup_formID">My timed popup HTML is</label></th>
                        <td>
                            <?php
                            $settings = array('editor_height' => 250, 'textarea_rows' => 12, 'media_buttons' => true, 'quicktags' => true, 'textarea_name' => 'swift_settings[sc_timed_popup_content]');
                            $timed_popup_content = isset($swift_settings['sc_timed_popup_content']) && !empty($swift_settings['sc_timed_popup_content']) ? stripslashes($swift_settings['sc_timed_popup_content']) : "";
                            wp_editor($timed_popup_content, 'sc_timed_popup_content_id', $settings);
                            ?>
                        </td>
                    </tr>
                </table>
                <table class="form-table">
                    <tr>
                        <th>
                            <?php wp_nonce_field('save_popups', 'save_popups'); ?>
                            <input type="submit" class="button button-primary" value="Save Changes" />
                        </th>
                    </tr>
                </table>
            </form>
        </div>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery('.enable_time').rcSwitcher().on({
                    'turnon.rcSwitcher': function (e, dataObj) {
                        jQuery(".toggle-table").fadeIn();
                    },
                    'turnoff.rcSwitcher': function (e, dataObj) {
                        jQuery(".toggle-table").fadeOut();
                    }
                });
                //popup content
                jQuery('.timed_popup_content_flag').rcSwitcher({
                    width: 80, autoFontSize: true,
                }).on({
                    'turnon.rcSwitcher': function (e, dataObj) {
                        jQuery(".sc-content-formid").hide();
                        jQuery(".sc-content-html").fadeIn();
                    },
                    'turnoff.rcSwitcher': function (e, dataObj) {
                        jQuery(".sc-content-html").hide();
                        jQuery(".sc-content-formid").fadeIn();
                    }
                });

                jQuery(".timedError").remove();
                jQuery("#frmTimedPopup").submit(function (e) {
                    jQuery(".timedError").remove();
                    if (jQuery('.enable_time:checkbox').is(':checked')) {
                        if (!jQuery('.timed_popup_content_flag:checkbox').is(':checked')) {
                            if (jQuery.trim(jQuery("#timed_popup_formID").val()) === '') {
                                jQuery("#frmTimedPopup").before('<div id="" class="error timedError"><p>Form ID is Required to Enable This Function. Please visit <a href="https://SwiftCRM.com?pr=92">SwiftCloud.AI</a> (free or paid accounts will work) to generate this form.</p></div>');
                                jQuery("#timed_popup_formID").focus();
                                e.preventDefault();
                            }
                        }
                    }
                });
            });
        </script>
    </div>
    <?php
}
