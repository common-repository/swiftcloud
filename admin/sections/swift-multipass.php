<?php

function swift_multipass_cb() {
    ?>
    <div class="wrap">
        <div class="inner_content">
            <h2>Multipass</h2><hr/>
            <?php
            /* Save settings */
            $swift_settings = get_option('swift_settings');

            if (isset($_POST['multipass']) && wp_verify_nonce($_POST['multipass'], 'multipass')) {
                $swift_settings['swiftcloud_mp_CapturePage'] = sanitize_text_field($_POST['swift_settings']['swiftcloud_mp_CapturePage']);
                $update = update_option('swift_settings', $swift_settings);
            }

            if (isset($update) && !empty($update)) {
                echo '<div id="message" class="updated below-h2"><p>Settings updated successfully!</p></div>';
            }
            ?>
            <form method="post" action="" >
                <table class="form-table">
                    <tr>
                        <td colspan="2"><?php _e("MultiPass cookies 1st time users, but once captured, let's them pass. To add a capture or landing redirector to any page or post, just add [swiftcloud_multipass] into the content", 'swift-cloud'); ?></td>
                    </tr>
                    <tr>
                        <th>MultiPass Capture Page:</th>
                        <td>
                            <select name="swift_settings[swiftcloud_mp_CapturePage]" id="swiftcloud_mp_CapturePage">
                                <option value="0">--Select Page--</option>
                                <?php
                                $args = array(
                                    'sort_order' => 'ASC',
                                    'sort_column' => 'post_title',
                                    'hierarchical' => 1,
                                    'post_type' => 'page',
                                    'post_status' => 'publish'
                                );
                                $pages = get_pages($args);
                                if ($pages) {
                                    $capturedPage = isset($swift_settings['swiftcloud_mp_CapturePage']) && !empty($swift_settings['swiftcloud_mp_CapturePage']) ? esc_attr($swift_settings['swiftcloud_mp_CapturePage']) : "";
                                    foreach ($pages as $page) {

                                        ?>
                                        <option <?php selected($capturedPage, $page->ID); ?> value="<?php echo $page->ID ?>"><?php echo $page->post_title ?></option>
                                        <?php
                                    }//First if
                                }// First loop
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            - Please add [swiftcloud_confirmpage] to whatever page the visitors see after capture to cookie them as captured.
                            <br/><br/>
                            <strong>Tip:</strong> You can capture visitors before redirecting to 3rd party URLs by appending "?redir=http://3rdPartyURLHere.com", i.e. http://<?php echo $_SERVER['HTTP_HOST']; ?>/bonuses?redir=https://SwiftCRM.Com. This will capture them before forwarding them along, unless they've already been captured before.
                            <br/><br/>
                            - [swiftcloud_welcome_name] shortcode display captured user's first name. for ex: thanks [swiftcloud_welcome_name],  so it say like "thanks Jon"
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2">
                            <?php wp_nonce_field('multipass', 'multipass') ?>
                            <input type="submit" class="button button-primary" value="Save Changes" />
                        </th>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    <?php
}

/*
 *      Shortcode : [swiftcloud_multipass]
 */

function swiftcloudMultiPass_shortcode() {
    if (!isset($_COOKIE['onceCaptured']) && empty($_COOKIE['onceCaptured'])) {
        if (isset($_SESSION['swiftcloud_capturedUser']) && !empty($_SESSION['swiftcloud_capturedUser'])) {

        } else {
            $_SESSION['swiftcloud_redirectTo'] = esc_url(get_permalink());
            $swift_settings = get_option('swift_settings');
            echo esc_js('<script type="text/javascript">window.location.href="' . get_page_link($swift_settings['swiftcloud_mp_CapturePage']) . '"</script>');
        }
    }
}

add_shortcode('swiftcloud_multipass', 'swiftcloudMultiPass_shortcode');