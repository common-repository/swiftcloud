<?php

function swift_popup_scroll_cb() {
    ?>
    <div class="wrap">
        <div class="inner_content">
            <h2>Scroll Popup</h2><hr/>
            <?php
            /* Save settings */
            
            if (isset($_POST['save_popups']) && wp_verify_nonce($_POST['save_popups'], 'save_popups')) {
                //Save feilds of scroll aware popup
                $swift_settings['width1'] = sanitize_text_field($_POST['swift_settings']['width1']);
                $swift_settings['height1'] = sanitize_text_field($_POST['swift_settings']['height1']);
                $swift_settings['enable_scroll'] = sanitize_text_field(!empty($_POST['swift_settings']['enable_scroll']) ? 1 : 0);
                $swift_settings['scroll_form_id'] = sanitize_text_field($_POST['swift_settings']['scroll_form_id']);
                $swift_settings['scroll_popup_content_flag'] = sanitize_text_field(!empty($_POST['swift_settings']['scroll_popup_content_flag']) ? 1 : 0);
                $swift_settings['sc_scroll_popup_content'] = wp_kses_post($_POST['swift_settings']['sc_scroll_popup_content']);
                $swift_settings['scroll_popup_headline'] = sanitize_text_field($_POST['swift_settings']['scroll_popup_headline']);

                $update = update_option('swift_settings', $swift_settings);
            }

            if (isset($update) && !empty($update)) {
                echo '<div id="message" class="updated below-h2"><p>Settings updated successfully!</p></div>';
            }
            
            $swift_settings = get_option('swift_settings');
            $scrollPopupOnOff = (isset($swift_settings['enable_scroll']) && !empty($swift_settings['enable_scroll']) && $swift_settings['enable_scroll'] == 1 ? 'checked="checked"' : "");
            $scrollPopupToggle = (isset($swift_settings['enable_scroll']) && !empty($swift_settings['enable_scroll']) && $swift_settings['enable_scroll'] == 1 ? 'display:block' : 'display:none');
            $contentFlag = (isset($swift_settings['scroll_popup_content_flag']) && !empty($swift_settings['scroll_popup_content_flag']) && $swift_settings['scroll_popup_content_flag'] == 1 ? 'checked="checked"' : "");
            ?>
            <form method="post" action="" id="frmScrollPopUp">
                <table class="form-table">
                    <tr>
                        <th colspan="2">Fire this popup if someone scrolls down 70% of the page-height</th>
                    </tr>
                    <tr>
                        <th><label for="popup-delay">Currently, the popup is </label></th>
                        <td>

                            <input type="checkbox" value="1" data-ontext="ON" data-offtext="OFF" name="swift_settings[enable_scroll]" id="enable_scroll" class="sc_switch enable_scroll" <?php echo $scrollPopupOnOff; ?>>
                        </td>
                    </tr>
                </table>
                <table class="form-table toggle-table" style="<?php echo $scrollPopupToggle; ?>">
                    <tr>
                        <th><label>with a width of</label></th>
                        <td><input type="text" value="<?php echo (!empty($swift_settings['width1']) ? esc_attr($swift_settings['width1']) : '360px'); ?>" class="" name="swift_settings[width1]"/> in pixels</td>
                    </tr>
                    <tr>
                        <th><label>and height of</label></th>
                        <td><input type="text" value="<?php echo (!empty($swift_settings['height1']) ? esc_attr($swift_settings['height1']) : ''); ?>" class="" name="swift_settings[height1]"/> in pixels.</td>
                    </tr>
                    <tr>
                        <th><label>Popup Headline</label></th>
                        <td><input type="text" value="<?php echo (isset($swift_settings['scroll_popup_headline']) && !empty($swift_settings['scroll_popup_headline']) ? ($swift_settings['scroll_popup_headline']) : ""); ?>" class="regular-text" name="swift_settings[scroll_popup_headline]" /></td>
                    </tr>
                    <tr>
                        <th><label>Popup content </label></th>
                        <td>
                            <input type="checkbox" value="1" data-ontext="HTML" data-offtext="Form ID" name="swift_settings[scroll_popup_content_flag]" id="scroll_popup_content_flag" class="scroll_popup_content_flag" <?php echo $contentFlag; ?>>
                        </td>
                    </tr>
                    <tr class="sc-scroll-content-formid" style="<?php echo (isset($swift_settings['scroll_popup_content_flag']) && $swift_settings['scroll_popup_content_flag'] == intval(0)) ? 'visibility: visible;' : 'display:none;'; ?>">
                        <th><label for="scrollPopupFormID">My scroll popup form ID # is</label></th>
                        <td>
                            <input type="text" value="<?php echo (isset($swift_settings['scroll_form_id']) && !empty($swift_settings['scroll_form_id']) ? esc_attr($swift_settings['scroll_form_id']) : "") ?>" id="scrollPopupFormID" class="" name="swift_settings[scroll_form_id]"/>
                        </td>
                    </tr>
                    <tr class="sc-scroll-content-html" style="<?php echo (isset($swift_settings['scroll_popup_content_flag']) && $swift_settings['scroll_popup_content_flag'] == 1) ? 'visibility: visible;' : 'display:none;'; ?>">
                        <th><label for="timed_popup_formID">My scroll popup HTML is</label></th>
                        <td>
                            <?php
                            $settings = array('editor_height' => 250, 'textarea_rows' => 12, 'media_buttons' => true, 'quicktags' => true, 'textarea_name' => 'swift_settings[sc_scroll_popup_content]');
                            $scroll_popup_content = isset($swift_settings['sc_scroll_popup_content']) && !empty($swift_settings['sc_scroll_popup_content']) ? stripslashes($swift_settings['sc_scroll_popup_content']) : "";
                            wp_editor($scroll_popup_content, 'sc_scroll_popup_content_id', $settings);
                            ?>
                        </td>
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
                jQuery('.enable_scroll').rcSwitcher().on({
                    'turnon.rcSwitcher': function(e, dataObj) {
                        jQuery(".toggle-table").fadeIn();
                    },
                    'turnoff.rcSwitcher': function(e, dataObj) {
                        jQuery(".toggle-table").fadeOut();
                    }
                });

                //popup content
                jQuery('.scroll_popup_content_flag').rcSwitcher({
                    width: 80, autoFontSize: true
                }).on({
                    'turnon.rcSwitcher': function(e, dataObj) {
                        jQuery(".sc-scroll-content-formid").hide();
                        jQuery(".sc-scroll-content-html").fadeIn();
                    },
                    'turnoff.rcSwitcher': function(e, dataObj) {
                        jQuery(".sc-scroll-content-html").hide();
                        jQuery(".sc-scroll-content-formid").fadeIn();
                    }
                });


                jQuery(".scrollError").remove();
                jQuery("#frmScrollPopUp").submit(function(e) {
                    jQuery(".scrollError").remove();
                    if (jQuery('.enable_scroll:checkbox').is(':checked')) {
                        if (!jQuery('.scroll_popup_content_flag:checkbox').is(':checked')) {
                            if (jQuery.trim(jQuery("#scrollPopupFormID").val()) === '') {
                                jQuery("#frmScrollPopUp").before('<div id="" class="error scrollError"><p>Form ID is Required to Enable This Function. Please visit <a href="https://SwiftCRM.com?pr=92">SwiftCloud.AI</a> (free or paid accounts will work) to generate this form.</p></div>');
                                jQuery("#scrollPopupFormID").focus();
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