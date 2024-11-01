<?php
$swift_settings = get_option('swift_settings');

function is_custom_post_type($post = NULL) {
    $all_custom_post_types = get_post_types(array('_builtin' => FALSE));

    // there are no custom post types
    if (empty($all_custom_post_types))
        return FALSE;

    $custom_types = array_keys($all_custom_post_types);
    $current_post_type = get_post_type($post);

    // could not detect current type
    if (!$current_post_type)
        return FALSE;

    return in_array($current_post_type, $custom_types);
}

if (isset($swift_settings['enable_cta_welcome_capture']) && !empty($swift_settings['enable_cta_welcome_capture'])) {
    $swift_settings = get_option('swift_settings');

    if (!empty($swift_settings['cta_local_html_content']) || !empty($swift_settings['cta_form_id'])) {
        remove_filter('the_content', 'wpautop');
        $br = false;
        add_filter('the_content', function( $content ) use ( $br ) {
                    return wpautop($content, $br);
                }, 10);

        add_filter('the_content', 'filter_call_to_action_box');

        function filter_call_to_action_box($content) {
            $swift_settings = get_option('swift_settings');
            $cta_show_flag = '';

            if (!empty($swift_settings['cta_show_on'])) {
                if (in_array('pages', $swift_settings['cta_show_on'])) {
                    if (is_page()) {
                        $cta_show_flag = "true";
                        if (!empty($swift_settings['cta_dont_show_on'])) {
                            if (in_array('home', $swift_settings['cta_dont_show_on'])) {
                                if (is_front_page() && is_home())
                                    $cta_show_flag = '';
                                else if (is_front_page())
                                    $cta_show_flag = '';
                            }
                        }
                    }
                }
                if (!empty($swift_settings['cta_show_on']) && in_array('posts', $swift_settings['cta_show_on'])) {
                    if (is_single())
                        $cta_show_flag = "true";
                }
                if (!empty($swift_settings['cta_dont_show_on']) && in_array('cpt', $swift_settings['cta_dont_show_on'])) {
                    // check if it is custom post type
                    if (is_custom_post_type())
                        $cta_show_flag = "";
                }
            }

            $cta_inline_style = '';
            if ($cta_show_flag == 'true') {
                if ($swift_settings['enable_cta_contents']) {
                    $cta_inline_style .=!empty($swift_settings['cta_html_bg_color']) ? "background:" . $swift_settings['cta_html_bg_color'] . ";" : '';
                $cta_inline_style .=!empty($swift_settings['cta_html_css']) ? trim($swift_settings['cta_html_css']) : '';
                }

                $strToAppend = '';
                $strToAppend.='<div class="cta-content" style="' . $cta_inline_style . '">';

                // check if html editor or swift form
                if ($swift_settings['enable_cta_contents']) {
                    $strToAppend.= stripslashes(($swift_settings['cta_local_html_content']));
                } else {
                    $strToAppend.='[swiftform id="' . $swift_settings['cta_form_id'] . '"]';
                    $strToAppend .= '<script type="text/javascript">';
                    $strToAppend .= 'jQuery(document).ready(function($) {';
                    $strToAppend .= 'var change_btn_txt = "' . $swift_settings['cta_form_btn_text'] . '";';
                    $strToAppend .= '$(".cta-content #form_submit_btn").val(change_btn_txt);';
                    $strToAppend .= '});';
                    $strToAppend .= '</script>';
                }
                $strToAppend.='</div>';
                $content = $content . $strToAppend;
            }
            return $content;
        }

    }
}
?>