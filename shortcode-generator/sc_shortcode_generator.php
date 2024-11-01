<?php

// hooks your functions into the correct filters
if (!function_exists("swiftcloud_add_mce_dropdown")) {

    function swiftcloud_add_mce_dropdown() {
        // check user permissions
        if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
            return;
        }
        // check if WYSIWYG is enabled
        if ('true' == get_user_option('rich_editing')) {
            add_filter('mce_external_plugins', 'swiftcloud_add_tinymce_plugin');
            add_filter('mce_buttons', 'swiftcloud_register_mce_button');
        }
    }

}
add_action('admin_head', 'swiftcloud_add_mce_dropdown');

// register new button in the editor
if (!function_exists("swiftcloud_register_mce_button")) {

    function swiftcloud_register_mce_button($buttons) {
        array_push($buttons, 'sc_mce_button');
        return $buttons;
    }

}

// the script will insert the shortcode on the click event
if (!function_exists("swiftcloud_add_tinymce_plugin")) {

    function swiftcloud_add_tinymce_plugin($plugin_array) {
        $plugin_array['sc_mce_button'] = plugins_url('swiftcloud/shortcode-generator/js/sc_shortcode_generator_dd.js'); //plugin_dir_url(__FILE__) . '../js/editor_dropdown.js';
        return $plugin_array;
    }

}
?>
