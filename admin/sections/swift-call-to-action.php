<?php

function swift_swift_cta_boxcb() {
    wp_enqueue_style('swiftcloud-colorpicker-style', plugins_url('../css/spectrum.css', __FILE__), '', '', '');
    wp_enqueue_script('swiftcloud-colorpicker', plugins_url('../js/spectrum.js', __FILE__), array('jquery'), '', true);
    ?>
    <div class="wrap">
        <div class="inner_content">
            <h2>Call To Action Post-Content Offer Box</h2><hr/>
            <?php
            $swift_settings = get_option('swift_settings');
            if (isset($_POST['save_cta_box']) && wp_verify_nonce($_POST['save_cta_box'], 'save_cta_box')) {
                $swift_settings['enable_cta_welcome_capture'] = (isset($_POST['swift_settings']['enable_cta_welcome_capture']) && !empty($_POST['swift_settings']['enable_cta_welcome_capture'])) ? 1 : 0;
                $swift_settings['cta_show_on'] = sanitize_text_or_array_field($_POST['swift_settings']['cta_show_on']);
                $swift_settings['cta_dont_show_on'] = sanitize_text_or_array_field($_POST['swift_settings']['cta_dont_show_on']);
                $swift_settings['cta_form_id'] = sanitize_text_field($_POST['swift_settings']['cta_form_id']);
                $swift_settings['cta_form_btn_text'] = sanitize_text_field($_POST['swift_settings']['cta_form_btn_text']);
                $swift_settings['enable_cta_contents'] = sanitize_text_field($_POST['swift_settings']['enable_cta_contents']);
                $swift_settings['cta_local_html_content'] = sanitize_text_field($_POST['swift_settings']['cta_local_html_content']);
                $swift_settings['cta_html_bg_color'] = sanitize_text_field($_POST['swift_settings']['cta_html_bg_color']);
                $swift_settings['cta_html_font_color'] = sanitize_text_field($_POST['swift_settings']['cta_html_font_color']);
                $swift_settings['cta_html_css'] = sanitize_text_field($_POST['swift_settings']['cta_html_css']);

                $update = update_option('swift_settings', $swift_settings);
            }
            if (isset($update) && !empty($update)) {
                echo '<div id="message" class="updated below-h2"><p>Settings updated successfully!</p></div>';
            }
            ?>
            <form name="frm_cta_box" id="frm_cta_box" method="post">
                <table class="form-table">
                    <tr>
                        <th><label for="enable_cta_welcome_capture">Call to Action Post-Content Offer</label></th>
                        <td>
                            <?php $ctaOnOff = (isset($swift_settings['enable_cta_welcome_capture']) && !empty($swift_settings['enable_cta_welcome_capture']) && $swift_settings['enable_cta_welcome_capture'] == 1 ? 'checked="checked"' : ""); ?>
                            <input type="checkbox" value="1" data-ontext="ON" data-offtext="OFF" name="swift_settings[enable_cta_welcome_capture]" id="enable_cta_welcome_capture" class="enable_cta_welcome_capture" <?php echo $ctaOnOff; ?>>
                        </td>
                    </tr>
                    <tr>
                        <th>Show on</th>
                        <td>
                            <?php
                            if (!empty($swift_settings['cta_show_on'])) {
                                $checkedPage = (in_array('pages', $swift_settings['cta_show_on'])) ? 'checked="checked"' : '';
                                $checkedPost = (in_array('posts', $swift_settings['cta_show_on']) ? 'checked="checked"' : '');
                            } else {
                                $checkedPage = '';
                                $checkedPost = '';
                            }
                            ?>
                            <label for="show_on1"><input type="checkbox" id="show_on1" name="swift_settings[cta_show_on][]" value="pages" <?php echo $checkedPage; ?>/>Pages</label>&nbsp;&nbsp;
                            <label for="show_on2"><input type="checkbox" id="show_on2" name="swift_settings[cta_show_on][]" value="posts" <?php echo $checkedPost; ?>/>Posts</label>&nbsp;&nbsp;
                        </td>
                    </tr>
                    <tr>
                        <th>Don't show on </th>
                        <td>
                            <?php
                            if (!empty($swift_settings['cta_dont_show_on'])) {
                                $checkedHome = (in_array('home', $swift_settings['cta_dont_show_on'])) ? 'checked="checked"' : '';
                                $checkedBlog = (in_array('blog', $swift_settings['cta_dont_show_on']) ? 'checked="checked"' : '');
                                $checked404 = (in_array('404', $swift_settings['cta_dont_show_on']) ? 'checked="checked"' : '');
                                $checkedCpt = (in_array('cpt', $swift_settings['cta_dont_show_on']) ? 'checked="checked"' : '');
                            } else {
                                $checkedHome = '';
                                $checkedBlog = '';
                                $checked404 = '';
                                $checkedCpt = '';
                            }
                            ?>
                            <label for="cta_dont_show_on1"><input type="checkbox" id="cta_dont_show_on1" name="swift_settings[cta_dont_show_on][]" value="home" <?php echo $checkedHome; ?>/>Home Page</label>&nbsp;&nbsp;
                            <label for="cta_dont_show_on2"><input type="checkbox" id="cta_dont_show_on2" name="swift_settings[cta_dont_show_on][]" value="blog" <?php echo $checkedBlog; ?>/>Blog Page</label>&nbsp;&nbsp;
                            <label for="cta_dont_show_on3"><input type="checkbox" id="cta_dont_show_on3" name="swift_settings[cta_dont_show_on][]" value="404"  <?php echo $checked404; ?>/>404 Page</label>&nbsp;&nbsp;
                            <label for="cta_dont_show_on4"><input type="checkbox" id="cta_dont_show_on4" name="swift_settings[cta_dont_show_on][]" value="cpt"  <?php echo $checkedCpt; ?>/>Custom Post Type</label>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="enable_cta_contents">Contents</label></th>
                        <td>
                            <?php $cta_contentOnOff = (isset($swift_settings['enable_cta_contents']) && !empty($swift_settings['enable_cta_contents']) && $swift_settings['enable_cta_contents'] == 1 ? 'checked="checked"' : ""); ?>
                            <input type="checkbox" value="1" data-ontext="Local HTML" data-offtext="Form ID" name="swift_settings[enable_cta_contents]" id="enable_cta_contents" class="enable_cta_contents" <?php echo $cta_contentOnOff; ?>>
                        </td>
                    </tr>
                    <tr class="show-sc-form" style="<?php echo ((isset($swift_settings['enable_cta_contents']) && $swift_settings['enable_cta_contents'] == "") ? 'visibility: visible;' : 'display:none'); ?>">
                        <th><label for="cta_form_id">Form ID number</label></th>
                        <td><input type="text" id="cta_form_id" value="<?php echo isset($swift_settings['cta_form_id']) && !empty($swift_settings['cta_form_id']) ? esc_attr($swift_settings['cta_form_id']) : ""; ?>" class="" name="swift_settings[cta_form_id]"/></td>
                    </tr>
                    <tr class="show-sc-form" style="<?php echo ((isset($swift_settings['enable_cta_contents']) && $swift_settings['enable_cta_contents'] == "") ? 'visibility: visible;' : 'display:none'); ?>">
                        <th><label for="cta_form_btn_text">Form Button Text</label></th>
                        <td><input type="text" id="cta_form_btn_text" value="<?php echo isset($swift_settings['cta_form_btn_text']) && !empty($swift_settings['cta_form_btn_text']) ? esc_attr($swift_settings['cta_form_btn_text']) : ""; ?>" class="" name="swift_settings[cta_form_btn_text]"/></td>
                    </tr>

                    <tr class="show-local-html"  style="<?php echo ((isset($swift_settings['enable_cta_contents']) && !empty($swift_settings['enable_cta_contents']) && $swift_settings['enable_cta_contents'] == "1") ? 'visibility: visible;' : 'display:none'); ?>">
                        <th><label for="cta_local_html">Local HTML</label></th>
                        <td>
                            <input style="display:none;" type="radio" class="" name="cta_local_html" value="html_content" />
                            <?php
                            $settings = array('media_buttons' => true, 'quicktags' => true, 'textarea_name' => 'swift_settings[cta_local_html_content]');
                            $cta_content = isset($swift_settings['cta_local_html_content']) && !empty($swift_settings['cta_local_html_content']) ? stripslashes($swift_settings['cta_local_html_content']) : "";
                            wp_editor($cta_content, 'cta_local_html_id', $settings);
                            ?>
                        </td>
                    </tr>
                    <tr class="show-local-html"  style="<?php echo ((isset($swift_settings['enable_cta_contents']) && !empty($swift_settings['enable_cta_contents']) && $swift_settings['enable_cta_contents'] == "1") ? 'visibility: visible;' : 'display:none'); ?>">
                        <th><label for="cta_html_bg_color">HTML Background Color</label></th>
                        <td><input type="text" id="cta_html_bg_color" value="<?php echo esc_attr($swift_settings['cta_html_bg_color']) ?>" class="" name="swift_settings[cta_html_bg_color]" placeholder="#FFFFFF"/></td>
                    </tr>
                    <tr class="show-local-html"  style="<?php echo ((isset($swift_settings['enable_cta_contents']) && !empty($swift_settings['enable_cta_contents']) && $swift_settings['enable_cta_contents'] == "1") ? 'visibility: visible;' : 'display:none'); ?>">
                        <th><label for="cta_html_font_color">HTML Font Color</label></th>
                        <td><input type="text" id="cta_html_font_color" value="<?php echo esc_attr($swift_settings['cta_html_font_color']) ?>" class="" name="swift_settings[cta_html_font_color]" placeholder="#000"/></td>
                    </tr>
                    <tr class="show-local-html"  style="<?php echo ((isset($swift_settings['enable_cta_contents']) && !empty($swift_settings['enable_cta_contents']) && $swift_settings['enable_cta_contents'] == "1") ? 'visibility: visible;' : 'display:none'); ?>">
                        <th><label for="cta_html_css">HTML Custom CSS</label></th>
                        <td>
                            <textarea id="cta_html_css" class="" name="swift_settings[cta_html_css]" rows="6" cols="50"><?php echo (isset($swift_settings['cta_html_css']) && !empty($swift_settings['cta_html_css']) ? esc_attr($swift_settings['cta_html_css']) : ""); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <?php wp_nonce_field('save_cta_box', 'save_cta_box') ?>
                            <input type="submit" class="button button-primary" value="Save Changes" />
                            <input type="button" class="button button-primary show-local-html" value="Preview" id="cta_preview_popup" style="<?php echo ((isset($swift_settings['enable_cta_contents']) && $swift_settings['enable_cta_contents'] == "1") ? 'visibility: visible;' : 'display:none'); ?>" />
                        </th>
                    </tr>
                </table>
            </form>
        </div>

        <?php
        /*         * *** Preview sectin **** */
        $prv_bg_color = !empty($swift_settings['cta_html_bg_color']) ? esc_attr($swift_settings['cta_html_bg_color']) : '#fff';
        $prv_text_color = !empty($swift_settings['cta_html_font_color']) ? esc_attr($swift_settings['cta_html_font_color']) : '#000';
        $prv_custom_css = !empty($swift_settings['cta_html_css']) ? esc_attr($swift_settings['cta_html_css']) : '';
        ?>
        <div id="cta_prv_section" style="background:<?php echo $prv_bg_color; ?>;color:<?php echo $prv_text_color; ?>;<?php echo $prv_custom_css; ?>">
            <div class="cta_prv_inner">
                <?php echo stripslashes(esc_html($swift_settings['cta_local_html_content'])); ?>
            </div>
        </div>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                jQuery('.enable_cta_welcome_capture').rcSwitcher();
                jQuery('.enable_cta_contents:checkbox').rcSwitcher({width: 100}).on({
                    'turnon.rcSwitcher': function(e, dataObj) {
                        jQuery(".show-local-html").show();
                        jQuery(".show-sc-form").hide();
                    },
                    'turnoff.rcSwitcher': function(e, dataObj) {
                        jQuery(".show-sc-form").fadeIn();
                        jQuery(".show-local-html").fadeOut();
                        jQuery("#cta_prv_section").fadeOut();
                    }
                });

                $("#wp-cta_local_html_id-wrap #cta_local_html_id").css("background", $("#cta_html_bg_color").val());
                $("#wp-cta_local_html_id-wrap #cta_local_html_id").css("color", $("#cta_html_font_color").val());

                //form validation
                jQuery(".ctaError").remove();
                jQuery("#frm_cta_box").submit(function(e) {
                    $(".ctaError").remove();
                    if (jQuery('.enable_cta_welcome_capture:checkbox').is(':checked')) {
                        if (!jQuery('.enable_cta_contents:checkbox').is(':checked')) {
                            if (jQuery.trim(jQuery("#cta_form_id").val()) === '') {
                                jQuery("#frm_cta_box").before('<div id="" class="error ctaError"><p>Form ID is Required to Enable This Function. Please visit <a href="https://SwiftCRM.com?pr=92">SwiftCloud.AI</a> (free or paid accounts will work) to generate this form.</p></div>');
                                jQuery("#cta_form_id").focus();
                                e.preventDefault();
                            }
                        }
                    }
                });
                // change wp_editor's bg color
                jQuery("#cta_html_bg_color").change(function() {
                    jQuery("body.mceContentBody").css("background", jQuery(this).val());
                    //preview section
                    jQuery("#cta_prv_section").css("background", jQuery(this).val());
                });
                jQuery("#scal_cta_html_font_color").change(function() {
                    jQuery("#wp-scal_cta_local_html_id-wrap #scal_cta_local_html_id").css("color", jQuery(this).val());
                    //preview section
                    jQuery("#scal_cta_prv_section").css("color", jQuery(this).val());
                });
                //
                $("#cta_html_bg_color").spectrum({
                    preferredFormat: "hex",
                    color: "<?php echo (!empty($swift_settings['cta_html_bg_color']) ? $swift_settings['cta_html_bg_color'] : '#fff'); ?>",
                    showAlpha: true,
                    showButtons: false,
                    showInput: true
                });
                $("#cta_html_font_color").spectrum({
                    preferredFormat: "hex",
                    color: "<?php echo (!empty($swift_settings['cta_html_font_color']) ? $swift_settings['cta_html_font_color'] : '#000'); ?>",
                    showAlpha: true,
                    showButtons: false,
                    showInput: true
                });

                // change wp_editor's bg color
                $("#cta_html_bg_color").change(function() {
                    $("#cta_prv_section").css("background-color", $(this).val());
                    $("#cta_local_html_id").css("background", $(this).val());
                });
                $("#cta_html_font_color").change(function() {
                    $("#cta_prv_section").css("color", $(this).val());
                    $("#cta_local_html_id").css("color", $(this).val());
                });

                /*Preview*/
                $("#cta_preview_popup").on("click", function() {
                    $("#cta_prv_section").fadeIn();
                });
                $(".form-table").on("change", function() {
                    $("#cta_prv_section").hide();
                });


            });
        </script>
    </div>
    <?php
}