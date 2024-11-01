<?php
/*
 *      SOCIAL TAB
 */

/*
 *     REGISTER SOCIAL MENU
 */
add_action('init', 'sc_register_social_menu');

function sc_register_social_menu() {
    register_nav_menu('sc_social', 'SwiftCloud Social');
}

function swiftcloud_social() {
    wp_enqueue_style('swiftcloud-fontawesome', SWIFTCLOUD__PLUGIN_URL . 'css/font-awesome.min.css', '', '4.5.0');
    wp_enqueue_style('swiftcloud-popup', plugins_url('swiftcloud/css/swiftcloud_social.css'), '', '', '');
    wp_enqueue_script('swift-widget-position', plugins_url('../js/swift_widget_position.js', __FILE__), array('jquery'), '', true);

    $swift_settings = get_option('swift_settings');

    if (!isset($swift_settings['enable_social']) || empty($swift_settings['enable_social']) || $swift_settings['enable_social'] == 0 || $swift_settings['enable_social'] == "")
        return;


    $swift_global_position_class_social = swiftcloud_global_position_class($swift_settings['social_widget_position']);
    $social_widget_position = !empty($swift_settings['social_widget_position']) ? $swift_settings['social_widget_position'] : 'left_center';
    ?>
    <div class="sc_social_popup swiftcloud_widget <?php echo $social_widget_position . " " . $swift_global_position_class_social; ?>" id="sc_social_popup" >
        <div class="sc_social_section">
            <?php
            $btn_bg_color = !empty($swift_settings['social_btn_background_color']) ? "background-color:" . $swift_settings['social_btn_background_color'] : '';
            $text_color = !empty($swift_settings['social_text_color']) ? "color:" . $swift_settings['social_text_color'] : '';

            $header_social_menu_arr = array();
            $menu_locations = get_theme_mod('nav_menu_locations');
            if (isset($menu_locations) && !empty($menu_locations)) {
                $menuid = "sc_social";
                $header_menu_arr = array();
                $header_social_menu = wp_get_nav_menu_items($menu_locations[$menuid]);
                if (empty($header_social_menu))
                    return;

                foreach ($header_social_menu as $hsk => $hsd) {
                    if ($hsd->menu_item_parent == 0) {
                        $header_social_menu_arr[$hsd->menu_item_parent][] = $hsd;
                    } else {
                        $header_social_menu_arr[$hsd->menu_item_parent][$hsk][] = $hsd;
                    }
                }
                if (!empty($header_social_menu_arr[0])) {
                    echo '<ul class="sc_social_nav">';
                    foreach ($header_social_menu_arr[0] as $hs) {
                        echo '<li><div class="sc-social-bg" style="' . $btn_bg_color . '"><a href="' . $hs->url . '" target="_blank" style="' . $text_color . '"></a></div></li>';
                    }
                    echo '</ul>';
                }
            }
            ?>
        </div>
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            //get center of window
            //var horizontalCenter = Math.floor(window.innerWidth / 2);

            var verticalCener = (jQuery(window).innerHeight() - (jQuery("#sc_social_popup").outerHeight())) / 2;
            jQuery(".left_center").css('top', verticalCener + "px");
            jQuery(".right_center").css('top', verticalCener + "px");
        });
    </script>
    <?php
}

add_action('wp_footer', 'swiftcloud_social', 10);
?>
