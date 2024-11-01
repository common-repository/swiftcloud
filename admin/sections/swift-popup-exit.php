<?php

function swift_popup_exit_cb() {
    $settings = array('media_buttons' => true, 'textarea_rows' => 10, 'quicktags' => true, 'textarea_name' => 'swift_settings[delay]');
    ?>
    <div class="wrap">
        <div class="inner_content">
            <h2>Exit Popup</h2><hr/>
            <?php
            /* Save settings */
            $swift_settings = get_option('swift_settings');

            if (isset($_POST['save_popups']) && wp_verify_nonce($_POST['save_popups'], 'save_popups')) {
                $swift_settings['width2'] = sanitize_text_field($_POST['swift_settings']['width2']);
                $swift_settings['height2'] = sanitize_text_field($_POST['swift_settings']['height2']);
                $swift_settings['enable_exit'] = sanitize_text_field(!empty($_POST['swift_settings']['enable_exit']) ? 1 : 0);
                $swift_settings['form_id_exit'] = sanitize_text_field($_POST['swift_settings']['form_id_exit']);
                $swift_settings['exit_popup_content_flag'] = sanitize_text_field(!empty($_POST['swift_settings']['exit_popup_content_flag']) ? 1 : 0);
                $swift_settings['sc_exit_popup_content'] = wp_kses_post($_POST['swift_settings']['sc_exit_popup_content']);
                $swift_settings['exit_popup_headline'] = sanitize_text_field($_POST['swift_settings']['exit_popup_headline']);
                $swift_settings['exit_popup_custom_css'] = wp_kses_post($_POST['swift_settings']['exit_popup_custom_css']);

                $update = update_option('swift_settings', $swift_settings);
            }

            if (isset($update) && !empty($update)) {
                echo '<div id="message" class="notice is-dismissible notice-success below-h2"><p>Settings updated successfully!</p></div>';
            }

            $exitPopupOnOff = (isset($swift_settings['enable_exit']) && !empty($swift_settings['enable_exit']) && $swift_settings['enable_exit'] == 1 ? 'checked="checked"' : "");
            $exitPopupToggle = (isset($swift_settings['enable_exit']) && !empty($swift_settings['enable_exit']) && $swift_settings['enable_exit'] == 1 ? 'display:block' : 'display:none');
            $contentFlag = (isset($swift_settings['exit_popup_content_flag']) && !empty($swift_settings['exit_popup_content_flag']) && $swift_settings['exit_popup_content_flag'] == 1 ? 'checked="checked"' : "");
            ?>
            <form method="post" action="" id="frmExitPopUp">
                <table class="form-table">
                    <tr>
                        <th colspan="2">Fire this popup if someone mouses to leave the page,</th>
                    </tr>
                    <tr>
                        <th><label>Currently, the popup is</label></th>
                        <td>
                            <input type="checkbox" value="1" data-ontext="ON" data-offtext="OFF" name="swift_settings[enable_exit]" id="enable_exit" class="sc_switch enable_exit" <?php echo $exitPopupOnOff; ?>>
                        </td>
                    </tr>
                </table>
                <table class="form-table toggle-table" style="<?php echo $exitPopupToggle; ?>">
                    <tr>
                        <th><label>with a width of</label></th>
                        <td><input type="number" min="0" step="1" value="<?php echo (!empty($swift_settings['width2']) ? esc_attr($swift_settings['width2']) : "480"); ?>" class="" name="swift_settings[width2]"/>px</td>
                    </tr>
                    <tr>
                        <th><label>and height of</label></th>
                        <td><input type="number" min="0" step="1" value="<?php echo (!empty($swift_settings['height2']) ? esc_attr($swift_settings['height2']) : "360"); ?>" class="" name="swift_settings[height2]"/>px</td>
                    </tr>
                    <tr>
                        <th><label>Popup Headline</label></th>
                        <td><input type="text" value="<?php echo (isset($swift_settings['exit_popup_headline']) && !empty($swift_settings['exit_popup_headline']) ? esc_attr($swift_settings['exit_popup_headline']) : ""); ?>" class="regular-text" name="swift_settings[exit_popup_headline]"/></td>
                    </tr>
                    <tr>
                        <th><label>Popup content </label></th>
                        <td>
                            <input type="checkbox" value="1" data-ontext="HTML" data-offtext="Form ID" name="swift_settings[exit_popup_content_flag]" id="exit_popup_content_flag" class="exit_popup_content_flag" <?php echo $contentFlag; ?>>
                        </td>
                    </tr>
                    <tr class="sc-content-formid" style="<?php echo (isset($swift_settings['scroll_popup_content_flag']) && $swift_settings['exit_popup_content_flag'] == intval(0)) ? 'visibility: visible;' : 'display:none;'; ?>">
                        <th><label for="exitPopUpFormID">My exit popup form ID # is</label></th>
                        <td>
                            <input type="text" value="<?php echo (isset($swift_settings['form_id_exit']) && !empty($swift_settings['form_id_exit']) ? esc_attr($swift_settings['form_id_exit']) : "") ?>" id="exitPopUpFormID" class="" name="swift_settings[form_id_exit]"/>
                        </td>
                    </tr>
                    <tr class="sc-content-html" style="<?php echo (isset($swift_settings['scroll_popup_content_flag']) && $swift_settings['exit_popup_content_flag'] == 1) ? 'visibility: visible;' : 'display:none;'; ?>">
                        <th><label for="timed_popup_formID">My timed popup HTML is</label></th>
                        <td>
                            <?php
                            $settings = array('editor_height' => 250, 'textarea_rows' => 12, 'media_buttons' => true, 'quicktags' => true, 'textarea_name' => 'swift_settings[sc_exit_popup_content]');
                            $exit_popup_content = isset($swift_settings['sc_exit_popup_content']) && !empty($swift_settings['sc_exit_popup_content']) ? stripslashes($swift_settings['sc_exit_popup_content']) : "";
                            wp_editor($exit_popup_content, 'sc_exit_popup_content_id', $settings);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th><label>Custom CSS</label></th>
                        <td><textarea id="exitPopUpCustomCss" rows="8" cols="40" name="swift_settings[exit_popup_custom_css]"><?php echo isset($swift_settings['exit_popup_custom_css']) && !empty($swift_settings['exit_popup_custom_css']) ? esc_attr($swift_settings['exit_popup_custom_css']) : ""; ?></textarea></td>
                    </tr>
                </table>
                <table class="form-table">
                    <tr>
                        <th>
                            <?php wp_nonce_field('save_popups', 'save_popups') ?>
                            <input type="submit" class="button button-primary" value="Save Changes" />
                        </th>
                    </tr>
                </table>
            </form>
        </div>
        <script type="text/javascript">
            jQuery(document).ready(function() {
                jQuery('.enable_exit').rcSwitcher().on({
                    'turnon.rcSwitcher': function(e, dataObj) {
                        jQuery(".toggle-table").fadeIn();
                    },
                    'turnoff.rcSwitcher': function(e, dataObj) {
                        jQuery(".toggle-table").fadeOut();
                    }
                });

                //popup content
                jQuery('.exit_popup_content_flag').rcSwitcher({
                    width: 80, autoFontSize: true,
                }).on({
                    'turnon.rcSwitcher': function(e, dataObj) {
                        jQuery(".sc-content-formid").hide();
                        jQuery(".sc-content-html").fadeIn();
                    },
                    'turnoff.rcSwitcher': function(e, dataObj) {
                        jQuery(".sc-content-html").hide();
                        jQuery(".sc-content-formid").fadeIn();
                    }
                });

                jQuery(".exitError").remove();
                jQuery("#frmExitPopUp").submit(function(e) {
                    jQuery(".exitError").remove();
                    if (jQuery('.enable_exit:checkbox').is(':checked')) {
                        if (!jQuery('.exit_popup_content_flag:checkbox').is(':checked')) {
                            if (jQuery.trim(jQuery("#exitPopUpFormID").val()) === '') {
                                jQuery("#frmExitPopUp").before('<div id="" class="error exitError"><p>Form ID is Required to Enable This Function. Please visit <a href="https://SwiftCRM.com?pr=92">SwiftCloud.AI</a> (free or paid accounts will work) to generate this form.</p></div>');
                                jQuery("#exitPopUpFormID").focus();
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