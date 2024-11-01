<?php
if (!function_exists('swift_live_chat_cb')) {

    function swift_live_chat_cb() {
        wp_enqueue_style('swift-cloud-colorpicker-style', plugins_url('../css/spectrum.css', __FILE__), '', '', '');
        wp_enqueue_script('swift-cloud-colorpicker', plugins_url('../js/spectrum.js', __FILE__), array('jquery'), '', true);
        ?>
        <div class="wrap">
            <div class="inner_content">
                <h2>Chat</h2><hr/>
                <?php
                /* Save settings */
                $swift_settings = get_option('swift_settings');
                $error = false;
                if (isset($_POST['save_chat_options']) && wp_verify_nonce($_POST['save_chat_options'], 'save_chat_options')) {
                    if (empty($_POST['swift_settings']['chatbot_form_id'])) {
                        $error = true;
//                        return false;
                    } else {
                        $swift_settings['chat_onoff'] = (isset($_POST['swift_settings']['chat_onoff']) && !empty($_POST['swift_settings']['chat_onoff'])) ? 1 : 0;
//                    $swift_settings['chat_icon_color'] = (!empty($_POST['swift_settings']['chat_icon_color'])) ? esc_attr($_POST['swift_settings']['chat_icon_color']) : "";
//                    $swift_settings['chat_headline'] = (!empty($_POST['swift_settings']['chat_headline'])) ? esc_attr($_POST['swift_settings']['chat_headline']) : "Chat with our team!";
                        $swift_settings['chatbot_form_id'] = (!empty($_POST['swift_settings']['chatbot_form_id'])) ? sanitize_text_field($_POST['swift_settings']['chatbot_form_id']) : "";
                        $swift_settings['cookie_notice'] = (!empty($_POST['swift_settings']['cookie_notice'])) ? wp_kses($_POST['swift_settings']['cookie_notice'], array('a' => array('href' => array(),'title' => array()))) : 'This site uses cookies. By continuing to use this website, you agree to their use. To find out more, including how to control cookies, see here: <a href="https://SwiftCRM.com/privacy" target="_blank"> Cookie Policy</a>';
                        $update = update_option('swift_settings', $swift_settings);
                    }
                }

//                $chat_icon_color = (isset($swift_settings['chat_icon_color'])) ? $swift_settings['chat_icon_color'] : "";
//                $chat_headline = isset($swift_settings['chat_headline']) && !empty($swift_settings['chat_headline']) ? $swift_settings['chat_headline'] : "";
                $chatbot_form_id = isset($swift_settings['chatbot_form_id']) && !empty($swift_settings['chatbot_form_id']) ? esc_attr($swift_settings['chatbot_form_id']) : "";
                $cookie_notice = isset($swift_settings['cookie_notice']) && !empty($swift_settings['cookie_notice']) ? esc_attr($swift_settings['cookie_notice']) : 'This site uses cookies. By continuing to use this website, you agree to their use. To find out more, including how to control cookies, see here: <a href="https://SwiftCRM.com/privacy" target="_blank"> Cookie Policy</a>';

                if (isset($update) && !empty($update)) {
                    echo '<div id="message" class="updated below-h2"><p>Settings updated successfully!</p></div>';
                }
                if (isset($error) && !empty($error)) {
                    echo '<div id="" class="error timedError"><p>Form ID is Required to Enable This Function. Please visit <a href="https://SwiftCRM.com?pr=92">SwiftCloud.AI</a> (free or paid accounts will work) to generate this form.</p></div>';
                }
                ?>
                <form method="post" action="" id="FrmSwiftCloudLiveChat" >
                    <table class="form-table">
                        <tr>
                            <th>Chat is</th>
                            <td>
                                <?php $chatOnOff = (isset($swift_settings['chat_onoff']) && !empty($swift_settings['chat_onoff']) && $swift_settings['chat_onoff'] == 1 ? 'checked="checked"' : ""); ?>
                                <input type="checkbox" value="1" data-ontext="ON" data-offtext="OFF" name="swift_settings[chat_onoff]" id="chat_onoff" class="chat_onoff" <?php echo $chatOnOff; ?>>
                            </td>
                        </tr>
                    </table>
                    <table class="form-table toggle-fields" style="<?php echo ((isset($swift_settings['chat_onoff']) && !empty($swift_settings['chat_onoff']) && $swift_settings['chat_onoff'] == 1) ? 'display: block;' : 'display: none;'); ?>">
        <!--                        <tr>
                            <th>Color of chat icon: </th>
                            <td><input type="text" id="chat_icon_color" value="<?php echo $chat_icon_color; ?>" class="" name="swift_settings[chat_icon_color]" placeholder="#196ABC"/></td>
                        </tr>
                        <tr>
                            <th>Headline: </th>
                            <td><input type="text" id="chat_headline" value="<?php echo $chat_headline; ?>" class="regular-text" name="swift_settings[chat_headline]"/></td>
                        </tr>-->
                        <tr>
                            <th>Default Chat ID: </th>
                            <td><input type="text" id="chatbot_form_id" value="<?php echo $chatbot_form_id; ?>" class="regular-text" name="swift_settings[chatbot_form_id]"/></td>
                        </tr>
                        <tr>
                            <th>Cookie Notice: </th>
                            <td><textarea id="cookie_notice" class="regular-text" rows="5" cols="50" name="swift_settings[cookie_notice]"><?php echo stripslashes($cookie_notice); ?></textarea></td>
                        </tr>
                    </table>
                    <table class="form-table">
                        <tr>
                            <th>
                                <?php wp_nonce_field('save_chat_options', 'save_chat_options') ?>
                                <input type="submit" class="button button-primary" value="Save Changes" />
                            </th>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                jQuery('.chat_onoff:checkbox').rcSwitcher().on({
                    'turnon.rcSwitcher': function (e, dataObj) {
                        // to do on turning on a switch
                        jQuery('.toggle-fields').fadeIn();
                    },
                    'turnoff.rcSwitcher': function (e, dataObj) {
                        // to do on turning off a switch
                        jQuery('.toggle-fields').fadeOut();
                    }
                });

                //                jQuery("#chat_icon_color").spectrum({
                //                    preferredFormat: "hex",
                //                    color: "<?php echo (!empty($swift_settings['chat_icon_color']) ? $swift_settings['chat_icon_color'] : '#196ABC'); ?>",
                //                    showAlpha: true,
                //                    showButtons: false,
                //                    showInput: true
                //                });

                jQuery("#FrmSwiftCloudLiveChat").submit(function (e) {
                    jQuery(".timedError").remove();
                    if (jQuery.trim(jQuery("#chatbot_form_id").val()) === '') {
                        jQuery("#FrmSwiftCloudLiveChat").before('<div id="" class="error timedError"><p>Form ID is Required to Enable This Function. Please visit <a href="https://SwiftCRM.com?pr=92">SwiftCloud.AI</a> (free or paid accounts will work) to generate this form.</p></div>');
                        jQuery("#chatbot_form_id").focus();
                        e.preventDefault();
                    }
                });
            });
        </script>
        <?php
    }

}


/**
 *      Add Toggle into Public Box in all posts/pages.
 *      Page/Post restriction.
 */
add_action('post_submitbox_misc_actions', 'swiftcloud_add_public_chatbot_action');
if (!function_exists('swiftcloud_add_public_chatbot_action')) {

    function swiftcloud_add_public_chatbot_action($post) {
        global $post;

        if (isset($post->post_type) && !empty($post->post_type) && $post->post_type == 'page') {
            $value = get_post_meta($post->ID, 'swiftcloud_page_chat_id', true);
            ?>
            <div class="misc-pub-section public-member">
                <strong>SwiftCloud Chat ID Override</strong>&nbsp;
                <input type="text" value="<?php echo esc_attr($value); ?>" name="swiftcloud_page_chat_id" id="swiftcloud_page_chat_id" />
            </div>
            <?php
        }
    }

}

add_action('save_post', 'swiftcloud_save_chatbot_postdata');
if (!function_exists('swiftcloud_save_chatbot_postdata')) {

    function swiftcloud_save_chatbot_postdata($postid) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return false;
        if (!current_user_can('edit_page', $postid))
            return false;
        if (empty($postid))
            return false;

        if (!empty($_POST['swiftcloud_page_chat_id'])) {
            update_post_meta($postid, 'swiftcloud_page_chat_id', sanitize_text_field($_POST['swiftcloud_page_chat_id']));
        } else {
            update_post_meta($postid, 'swiftcloud_page_chat_id', '');
        }
    }

}

