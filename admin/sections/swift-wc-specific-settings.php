<?php
// get all data
$get_wc_list = $wpdb->get_results("SELECT * FROM `$table_welcome_capture`");
?>
<div class="inner_content">
    <div class="add-new-btn-wrap">
        <button class="button button-orange swift-gwc-add-new" data-id="0" data-btn="add" data-modal="#swift_gwc_modal"><span class="dashicons dashicons-plus"></span> Add New</button>
    </div>
    <form id="frm_wc_list" method="post">
        <?php wp_nonce_field('swift_wc_list_action', 'swift_wc_list_action'); ?>
        <table class="widefat fixed striped tbl-global-capture-list">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="35%">Headline</th>
                    <th width="35%">Shortcode</th>
                    <th width="10%">Form ID</th>
                    <th width="15%">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($get_wc_list)) {
                    foreach ($get_wc_list as $wc_list) {
                        ?>
                        <tr>
                            <td><?php echo esc_attr($wc_list->wc_id); ?></td>
                            <td><?php echo esc_attr($wc_list->wc_headline); ?></td>
                            <td>[swiftcloud_welcomecapture id="<?php echo esc_attr($wc_list->wc_id); ?>"]</td>
                            <td><?php echo esc_attr($wc_list->wc_form_id); ?></td>
                            <td>
                                <a href="javascript:void(0);" name="swift_wc_edit" data-btn="edit" data-modal="#swift_gwc_modal" data-id="<?php echo esc_attr($wc_list->wc_id); ?>" class="swift-round-bg blue-bg swift_wc_edit" title="Edit"><span class="dashicons dashicons-edit"></span></a>
                                <a href="javascript:void(0);" name="swift_global_capture_delete" data-btn="delete" data-id="<?php echo esc_attr($wc_list->wc_id); ?>" class="swift-round-bg red-bg swift_wc_delete" title="Delete"><span class="dashicons dashicons-no"></span></a>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="5" align="center"><h3>No Data found... Why not <a href="javascript:void(0)" class="swift-gwc-add-new-link" data-id="0" data-btn="add" data-modal="#swift_gwc_modal" >click here</a> to add some now?</h3></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </form>
</div>

<div class="swift_gwc swift_gwc_modal" id="swift_gwc_modal" style="display: none;">
    <div class="swift_gwc_container">
        <form method="post" id="frm_swift_gwc" name="frm_swift_gwc">
            <div class="swift_gwc_header">
                <h2 class="swift_gwc_title">Add Welcome Capture</h2>
                <span class="dashicons dashicons-no swift_gwc_close"></span>
            </div>
            <div class="swift_gwc_content">
                <table class="form-table">
                    <tr>
                        <th><label for="swift_wc_list_form_id">Form ID number</label></th>
                        <td><input type="text" id="swift_wc_list_form_id" value="" class="" name="swift_wc_list_form_id"/></td>
                    </tr>
                    <tr>
                        <th><label for="swift_wc_list_form_btn_text">Form Button Text</label></th>
                        <td><input type="text" id="swift_wc_list_form_btn_text" value="" class="" name="swift_wc_list_form_btn_text"/></td>
                    </tr>
                    <tr>
                        <th><label>Popup background </label></th>
                        <td>
                            <input type="checkbox" value="1" name="swift_wc_list_bg_flag" id="swift_wc_list_bg" class="swift_wc_list_bg">
                        </td>
                    </tr>
                    <tr id="swift_wc_list_bg_img_wrap">
                        <th><label for="swift_wc_list_bg_img">Popup background image</label></th>
                        <td>
                            <input type="text" size="36" id="wc_bg_img" name="swift_wc_list_bg_img" value="" />
                            <input class="button primary upload_image" type="button" id="swift_wc_list_uploadimage" value="Upload Image" />
                            <br />Enter a URL or upload an image
                        </td>
                    </tr>
                    <tr id="swift_wc_list_bg_color_wrap" style="display: none;">
                        <th><label for="swift_wc_list_bg_color">Popup background color </label></th>
                        <td><input type="text" id="swift_wc_list_bg_color" value="" class="" name="swift_wc_list_bg_color" placeholder="#F16334"/></td>
                    </tr>

                    <tr>
                        <th><label for="swift_wc_list_text_color">Popup text color</label></th>
                        <td><input type="text" id="swift_wc_list_text_color" value="" class="" name="swift_wc_list_text_color" placeholder="#FFFFFF"/></td>
                    </tr>
                    <tr>
                        <th><label for="swift_wc_list_body_text">Popup Body Content</label></th>
                        <td>
                            <?php
                            $settings = array('media_buttons' => true, 'quicktags' => true, 'textarea_name' => 'swift_wc_list_body_text',);
                            wp_editor('', 'swift_wc_list_body_text_id', $settings)
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="swift_gwc_footer textright">
                <?php wp_nonce_field('save_welcome_capture_list_options', 'save_welcome_capture_list_options') ?>
                <button type="submit" name="swift_gwc_submit" id="swift_gwc_submit" value="Add" class="button button-primary" />Add</button>
            </div>
        </form>
    </div>
</div>