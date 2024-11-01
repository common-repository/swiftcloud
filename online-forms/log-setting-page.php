<?php
/*
 *      Log setting page
 */

function sma_admin_dispplay_log_settings() {
    ?>
    <div class="wrap">
        <h2>Settings</h2>
        <?php
        if (isset($_POST['save_sma_form']) && wp_verify_nonce($_POST['save_sma'], 'save_sma')) {
            $update = update_option('sma_settings', sanitize_text_or_array_field($_POST['sma_settings']));
        }
        $sma_settings = get_option('sma_settings');
        ?>
        <div class="inner_content">
            <?php
            if (isset($update) && !empty($update)) {
                echo '<div id="message" class="updated below-h2"><p>Settings successfully updated!</p></div>';
            }
            ?>
            <br /><br />
            <form action="" method="post" >

                <label for="popup-delay">Form ID (i.e.&nbsp; &lt;form <strong>id="sma_form"</strong>&gt; &lt;/form&gt;)</label>
                <input type="text" value="<?php echo ((!empty($sma_settings['form_id'])) ? esc_attr($sma_settings['form_id']) : 'sma_form'); ?>" class="widefat" name="sma_settings[form_id]" placeholder="e.g. sma_form" /><br /><br />

                <label for="popup-delay">File field ID</label>
                <input type="text" value="<?php echo ((!empty($sma_settings['file_field_id'])) ? esc_attr($sma_settings['file_field_id']) : ''); ?>" class="widefat" name="sma_settings[file_field_id]" placeholder="e.g. clientID"/><br /><br />

                <label for="popup-delay">Name field ID</label>
                <input type="text" value="<?php echo ((!empty($sma_settings['name_field_id'])) ? esc_attr($sma_settings['name_field_id']) : 'name'); ?>" class="widefat" name="sma_settings[name_field_id]" placeholder="e.g. name"/><br /><br />

                <label for="popup-delay">Email field ID</label>
                <input type="text" value="<?php echo ((!empty($sma_settings['email_field_id'])) ? esc_attr($sma_settings['email_field_id']) : 'email'); ?>" class="widefat" name="sma_settings[email_field_id]" placeholder="e.g. email"/><br /><br />

                <label for="popup-delay">Phone field ID</label>
                <input type="text" value="<?php echo ((!empty($sma_settings['phone_field_id'])) ? esc_attr($sma_settings['phone_field_id']) : 'phone'); ?>" class="widefat" name="sma_settings[phone_field_id]" placeholder="e.g. phone"/><br /><br />

                <label for="popup-delay">Submit field ID</label>
                <input type="text" value="<?php echo ((!empty($sma_settings['submit_field_id'])) ? esc_attr($sma_settings['submit_field_id']) : 'sma_submit'); ?>" class="widefat" name="sma_settings[submit_field_id]" placeholder="e.g. sma_submit"/><br /><br />

                <label for="popup-delay">SwiftCloud Form ID</label>
                <input type="text" value="<?php echo ((!empty($sma_settings['swiftcloud_form_id'])) ? esc_attr($sma_settings['swiftcloud_form_id']) : ''); ?>" class="widefat" name="sma_settings[swiftcloud_form_id]" placeholder="12F34"/>

                <?php wp_nonce_field('save_sma', 'save_sma') ?><br /><br /><br />
                <input type="submit" name="save_sma_form" class="button button-primary" value="Save Changes" />
            </form>
            <?php _e('<p><strong>Note:*</strong> form ID must be unique</p>', 'swift-cloud'); ?><br/>

            <h2><?php _e('How to use', 'swift-cloud'); ?></h2>
            <ul>
                <li><?php _e('- Get <b>id</b> from the form field and set it to above appropriate field.', 'swift-cloud'); ?></li>
            </ul>
        </div>
    </div>
    <?php
}