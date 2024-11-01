<?php

function swift_social_cb() {
    wp_enqueue_style('swiftcloud-colorpicker-style', plugins_url('../css/spectrum.css', __FILE__), '', '', '');
    wp_enqueue_script('swiftcloud-colorpicker', plugins_url('../js/spectrum.js', __FILE__), array('jquery'), '', true);
    ?>
    <div class="wrap">
        <div class="inner_content">
            <h2>Social</h2><hr/>
            <?php
            $swift_settings = get_option('swift_settings');
            $menu_locations = get_theme_mod('nav_menu_locations');
            if (isset($_POST['save_socail_box']) && wp_verify_nonce($_POST['save_socail_box'], 'save_socail_box')) {
                $swift_settings['enable_social'] = (isset($_POST['swift_settings']['enable_social']) && !empty($_POST['swift_settings']['enable_social'])) ? 1 : 0;
                $swift_settings['social_btn_background_color'] = sanitize_text_field($_POST['swift_settings']['social_btn_background_color']);
                $swift_settings['social_text_color'] = sanitize_text_field($_POST['swift_settings']['social_text_color']);
                $swift_settings['social_widget_position'] = sanitize_text_field($_POST['swift_settings']['social_widget_position']);

                $update = update_option('swift_settings', $swift_settings);

                //save menu location
                if (!empty($menu_locations) && !empty($_POST['sc_social_location'])) {
                    $menu_locations['sc_social'] = sanitize_text_field($_POST['sc_social_location']);
                    set_theme_mod('nav_menu_locations', $menu_locations);
                }
            }
            if (isset($update) && !empty($update)) {
                echo '<div id="message" class="updated below-h2"><p>Settings updated successfully!</p></div>';
            }
            ?>
            <form name="frm_sc_social" id="frm_sc_social" method="post">
                <table class="form-table">
                    <tr>
                        <th><label for="enable_social">Social Icons</label></th>
                        <td>
                            <?php $sOnOff = (isset($swift_settings['enable_social']) && !empty($swift_settings['enable_social']) && $swift_settings['enable_social'] == 1 ? 'checked="checked"' : ""); ?>
                            <input type="checkbox" value="1" data-ontext="ON" data-offtext="OFF" name="swift_settings[enable_social]" id="enable_social" class="enable_social" <?php echo $sOnOff; ?>>
                        </td>
                    </tr>
                </table>
                <table class="form-table socialToggle" style="<?php echo ((isset($swift_settings['enable_social']) && !empty($swift_settings['enable_social']) && $swift_settings['enable_social'] == 1) ? 'display: block;' : 'display: none;'); ?>">
                    <tr>
                        <th><label for="sc_social_menu">SwiftCloud Social</label></th>
                        <td>
                            <select id="sc_social_location" name="sc_social_location">
                                <option value="">— Select a Menu —</option>
                                <?php
                                $allCreatedMenu = get_terms('nav_menu', array('hide_empty' => false));
                                if (!empty($allCreatedMenu)) {
                                    foreach ($allCreatedMenu as $aMenu) {
                                        $selectedMenu = $aMenu->term_id == $menu_locations['sc_social'] ? 'selected="selected"' : '';
                                        echo '<option ' . $selectedMenu . ' value="' . $aMenu->term_id . '"> ' . esc_attr($aMenu->name) . ' </option>';
                                    }
                                }
                                ?>
                            </select>
                            <br/>
                            <?php
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="sc_social_btn_bg_color">Button background color </label></th>
                        <td><input type="text" id="sc_social_btn_bg_color" value="<?php echo (isset($swift_settings['social_btn_background_color']) && !empty($swift_settings['social_btn_background_color'])) ? esc_attr($swift_settings['social_btn_background_color']) : ""; ?>" class="" name="swift_settings[social_btn_background_color]" placeholder="#196ABC"/></td>
                    </tr>
                    <tr>
                        <th><label for="sc_social_text_color">Text color </label></th>
                        <td><input type="text" id="sc_social_text_color" value="<?php echo isset($swift_settings['social_text_color']) && !empty($swift_settings['social_text_color']) ? esc_attr($swift_settings['social_text_color']) : ""; ?>" class="" name="swift_settings[social_text_color]" placeholder="#FFFFFF"/></td>
                    </tr>
                    <tr>
                        <th><label for="social_widget_position">Widget Position</label></th>
                        <td>
                            <select id="social_widget_position" name="swift_settings[social_widget_position]">
                                <?php $social_widget_position = (isset($swift_settings['social_widget_position']) && !empty($swift_settings['social_widget_position'])) ? esc_attr($swift_settings['social_widget_position']) : ""; ?>
                                <option value="left_center" class="left_center" <?php echo ($social_widget_position == 'left_center' ? 'selected="selected"' : ''); ?>>Left Middle</option>
                                <option value="right_center" class="right_center" <?php echo ($social_widget_position == 'right_center' ? 'selected="selected"' : ''); ?>>Right Middle</option>
                                <option value="right" class="right_bottom" <?php echo ($social_widget_position == 'right' ? 'selected="selected"' : ''); ?>>Bottom Right</option>
                                <option value="center" class="center_bottom" <?php echo ($social_widget_position == 'center' ? 'selected="selected"' : ''); ?>>Bottom Center</option>
                                <option value="left" class="left_bottom" <?php echo ($social_widget_position == 'left' ? 'selected="selected"' : ''); ?>>Bottom Left</option>
                            </select>
                        </td>
                    </tr>
                </table>
                <table class="form-table">
                    <tr>
                        <th>
                            <?php wp_nonce_field('save_socail_box', 'save_socail_box') ?>
                            <input type="submit" class="button button-primary" value="Save Changes" />
                        </th>
                    </tr>
                </table>
            </form>
            <div class="notes-wrap">
                <h3><?php _e('How to use:', 'swift-cloud'); ?></h3>
                <ol>
                    <li><?php _e('Please visit <a href="nav-menus.php">Appearance >> Menus</a>  and create a menu called "Social Widget".', 'swift-cloud'); ?></li>
                    <li><?php _e('Using "Custom Links" on the left, please add in the URLs of any social profiles you wish to link to. The icon will automatically match based on the URL.', 'swift-cloud'); ?></li>
                    <li><?php _e('Next, select "Social Widget" in the drop down above, choose a position, and you are all set up.', 'swift-cloud'); ?></li>
                </ol>

            </div>
        </div>
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            jQuery('.enable_social').rcSwitcher().on({
                'turnon.rcSwitcher': function(e, dataObj) {
                    jQuery('.socialToggle').fadeIn();
                },
                'turnoff.rcSwitcher': function(e, dataObj) {
                    jQuery('.socialToggle').fadeOut();
                },
            });

            //validation
            jQuery(".timedError").remove();
            jQuery("#frm_sc_social").submit(function(e) {
                jQuery(".timedError").remove();
                if (jQuery('.enable_social:checkbox').is(':checked')) {
                    if (jQuery.trim(jQuery("#sc_social_location").val()) === '') {
                        jQuery("#frm_sc_social").before('<div id="" class="error timedError"><p>SwiftCloud Social is required to enable this function.</p></div>');
                        jQuery("#sc_social_location").focus();
                        e.preventDefault();
                    }
                }
            });

            //btn bg color
            $("#sc_social_btn_bg_color").spectrum({
                preferredFormat: "hex",
                color: "<?php echo (!empty($swift_settings['social_btn_background_color']) ? $swift_settings['social_btn_background_color'] : '#196ABC'); ?>",
                showAlpha: true,
                showButtons: false
            });
            $("#sc_social_text_color").spectrum({
                preferredFormat: "hex",
                color: "<?php echo (!empty($swift_settings['social_text_color']) ? $swift_settings['social_text_color'] : '#FFFFFF'); ?>",
                showAlpha: true,
                showButtons: false
            });
        });
    </script>
    <?php
}
?>
