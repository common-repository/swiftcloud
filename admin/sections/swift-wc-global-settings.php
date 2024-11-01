<?php
if (isset($_POST['save_welcome_capture_list']) && wp_verify_nonce($_POST['save_welcome_capture_list'], 'save_welcome_capture_list')) {

    $swift_welcome_capture_list_flag_val = (!empty($_POST['swift_welcome_capture_list_flag'])) ? $_POST['swift_welcome_capture_list_flag'] : 99;
    $swift_wc_list_dont_show_on_val = sanitize_text_or_array_field($_POST['swift_wc_list_dont_show_on']);

    $update1 = update_option('swift_welcome_capture_list_flag', $swift_welcome_capture_list_flag_val);
    $update2 = update_option('swift_wc_list_dont_show_on', $swift_wc_list_dont_show_on_val);

    if ($update1 || $update2) {
        wp_redirect(admin_url("admin.php?page=swift_welcome_capture_list&tab=swiftcloud-wc-global-settings&update=1"));
        die;
    }
}

$swift_welcome_capture_list_flag = get_option('swift_welcome_capture_list_flag', true);
$swift_wc_list_dont_show_on = get_option('swift_wc_list_dont_show_on', true);
?>
<form name="frm_welcome_capture_list" id="frm_welcome_capture_list" method="post" enctype="multipart/form-data">
    <table class="form-table">
        <tr>
            <th><label for="swift_global_welcome_capture_flag">Global Welcome Capture</label></th>
            <td>
                <?php
                $swift_wc_on_off = ($swift_welcome_capture_list_flag == 1 ? 'checked="checked"' : "");
                $swift_toggle = ($swift_welcome_capture_list_flag == 1 ? 'block' : 'none');
                ?>
                <input type="checkbox" value="1" name="swift_welcome_capture_list_flag" id="swift_global_welcome_capture_flag" class="swift_global_welcome_capture_flag" <?php echo $swift_wc_on_off; ?> />
            </td>
        </tr>
    </table>
    <table class="form-table global-capture-toggle" style="display: <?php echo $swift_toggle; ?>;margin-top: 0;">
        <tr>
            <td style="padding-left: 0;" colspan="2"><a href="<?php echo admin_url('admin.php?page=swift_welcome_capture'); ?>">Global Welcome Capture Settings</a></td>
        </tr>
        <tr>
            <th>Don't show on </th>
            <td>
                <?php
                $checkedHome = $checkedBlog = $checked404 = $checkedCpt = "";
                if (!empty($swift_wc_list_dont_show_on) && is_array($swift_wc_list_dont_show_on)) {
                    $checkedHome = (in_array('home', $swift_wc_list_dont_show_on)) ? 'checked="checked"' : '';
                    $checkedBlog = (in_array('blog', $swift_wc_list_dont_show_on) ? 'checked="checked"' : '');
                    $checked404 = (in_array('404', $swift_wc_list_dont_show_on) ? 'checked="checked"' : '');
                    $checkedCpt = (in_array('cpt', $swift_wc_list_dont_show_on) ? 'checked="checked"' : '');
                }
                ?>
                <label for="swift_wc_dont_show_on1"><input type="checkbox" id="swift_wc_dont_show_on1" name="swift_wc_list_dont_show_on[]" value="home" <?php echo $checkedHome; ?> />Home Page</label>&nbsp;&nbsp;
                <label for="swift_wc_dont_show_on2"><input type="checkbox" id="swift_wc_dont_show_on2" name="swift_wc_list_dont_show_on[]" value="blog" <?php echo $checkedBlog; ?>/>Blog List / Category</label>&nbsp;&nbsp;
                <label for="swift_wc_dont_show_on3"><input type="checkbox" id="swift_wc_dont_show_on3" name="swift_wc_list_dont_show_on[]" value="404"  <?php echo $checked404; ?>/>404 Page</label>&nbsp;&nbsp;
                <label for="swift_wc_dont_show_on4"><input type="checkbox" id="swift_wc_dont_show_on4" name="swift_wc_list_dont_show_on[]" value="cpt"  <?php echo $checkedCpt; ?>/>Custom Post Type</label>
            </td>
        </tr>

    </table>
    <table class="form-table">
        <tr>
            <th>
                <?php wp_nonce_field('save_welcome_capture_list', 'save_welcome_capture_list'); ?>
                <input type="submit" class="button button-primary" name="global_welcome_capture_submit" value="Save Changes" />
            </th>
        </tr>
    </table>
</form>