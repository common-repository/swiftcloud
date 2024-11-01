<?php
/*
 *      Polling Frontend
 *
 */

function swift_polling_front_end() {
    if (!isset($_COOKIE['poll_submitted'])) {
        wp_enqueue_script('swift-widget-position', plugins_url('../js/swift_widget_position.js', __FILE__), array('jquery'), '', true);

        $swift_settings = get_option('swift_settings');
        if (empty($swift_settings['polling_question']) && empty($swift_settings['polling_answers']))
            return;

        if (!isset($swift_settings['polling_enable']) || empty($swift_settings['polling_enable']) || $swift_settings['polling_enable'] == 0 || $swift_settings['polling_enable'] == "")
            return;

        $polling_show_on_flag = '';

        if (isset($swift_settings['polling_show_on']) && !empty($swift_settings['polling_show_on'])) {
            if (in_array('home', $swift_settings['polling_show_on'])) {
                if (is_front_page() && is_home())
                    $polling_show_on_flag = "true";
                else if (is_front_page())
                    $polling_show_on_flag = "true";
            }
            if (in_array('blog', $swift_settings['polling_show_on'])) {
                if (is_home())
                    $polling_show_on_flag = "true";
            }
            if (in_array('404', $swift_settings['polling_show_on'])) {
                if (is_404())
                    $polling_show_on_flag = "true";
            }
            if (in_array('pages', $swift_settings['polling_show_on'])) {
                if (is_page() && !is_front_page() && !is_home())
                    $polling_show_on_flag = "true";
            }
            if (in_array('post', $swift_settings['polling_show_on'])) {
                if (is_single())
                    $polling_show_on_flag = "true";
            }
        }

        $swift_global_position_class_polling = swiftcloud_global_position_class($swift_settings['polling_widget_position']);


        if ($polling_show_on_flag == "true") {
            ?>
            <div class="swift_polling_front swiftcloud_widget <?php echo $swift_global_position_class_polling; ?>" style="<?php //echo $polling_widget_position;          ?>">
                <div class="swift_polling_title">
                    <h2><?php echo ucfirst(esc_html($swift_settings['polling_question'])); ?></h2>
                    <span class="swift_polling_widget_toggle">-</span>
                </div>
                <div class="swift_polling_content">

                    <div class="swift_polling_ans">
                        <form name="FrmSwiftPolling" id="FrmSwiftPolling" method="post">
                            <?php
                            $sp_ans = explode("/*/", esc_html($swift_settings['polling_answers']));
                            foreach ($sp_ans as $sp_ans) {
                                ?>
                                <label for="polling_ans_<?php echo $sp_ans; ?>" class="label_poll_answer"><input type="radio" class="polling_ans" id="polling_ans_<?php echo $sp_ans; ?>" name="swift_polling_answer" value="<?php echo $sp_ans; ?>" /> <?php echo esc_html($sp_ans); ?></label>
                            <?php } ?>
                        </form>
                    </div>
                    <p class="swift_polling_poweredby"><a href="https://SwiftCRM.Com/" target="_blank">Powered by SwiftCloud Surveys</a></p>
                </div>
            </div>
            <script>
                jQuery(document).ready(function() {
                    var polling_show_after = '<?php echo $swift_settings['polling_open_after']; ?>';
                    polling_show_after = polling_show_after * 1000;
                    if (polling_show_after !== '') {
                        jQuery('.swift_polling_front').delay(polling_show_after).fadeIn();
                    } else {
                        jQuery('.swift_polling_front').fadeIn();
                    }

                    jQuery(".swift_polling_title").on("click", function() {
                        jQuery(".swift_polling_content").slideToggle('slow', function() {
                            jQuery(".swift_polling_title .swift_polling_widget_toggle").text(jQuery(this).is(':visible') ? "-" : "+");
                        });
                    });

                    jQuery(".polling_ans").on("click", function() {
                        if (jQuery('input[name=swift_polling_answer]:checked').length != 0) {
                            var data = {
                                'action': 'swift_poll_submit',
                                'swift_poll_ans': jQuery(".polling_ans:checked").val()
                            };
                            var ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>'
                            jQuery.post(ajax_url, data, function(response) {
                                jQuery('input[name=swift_polling_answer]').attr("disabled", "disabled");
                                if (jQuery.trim(response) == "poll_submitted") {
                                    jQuery(".swift_polling_content").html("<div class='poll_success'><i class='fa fa-check-square'></i> Thanks!</div>");
                                    setTimeout(function() {
                                        jQuery('.swift_polling_front').remove();
                                    }, 5000);
                                } else {
                                    jQuery('input[name=swift_polling_answer]').removeAttr("disabled");
                                }
                            });
                        } else {
                            jQuery("#FrmSwiftPolling").before("<p class='validatoin_err'>Please select any one answer.</p>")
                        }
                    });
                });
            </script>
            <?php
        }
    }
}

add_action('wp_footer', 'swift_polling_front_end', 10);

add_action('wp_ajax_swift_poll_submit', 'swift_poll_submit_callback');
add_action('wp_ajax_nopriv_swift_poll_submit', 'swift_poll_submit_callback');

function swift_poll_submit_callback() {
    if (isset($_POST['action']) && !empty($_POST['action']) && $_POST['action'] == 'swift_poll_submit') {
        if (!empty($_POST['swift_poll_ans'])) {
            $poll_final_res = "poll_unchanged";
            $swift_settings = get_option('swift_settings');
            $swift_poll_result = (isset($swift_settings['polling_result']) && !empty($swift_settings['polling_result'])) ? $swift_settings['polling_result'] : array();
            $new_swift_poll_result = array();

            $answers = @explode("/*/", $swift_settings['polling_answers']);
            if (isset($answers) && !empty($answers)) {
                foreach ($answers as $answer) {
                    if ($_POST['swift_poll_ans'] == $answer) {
                        if (array_key_exists($_POST['swift_poll_ans'], $swift_poll_result)) {
                            $curr_rate = $swift_poll_result[$answer];
                            $curr_rate++;
                            $new_swift_poll_result[$answer] = $curr_rate;
                        } else {
                            $new_swift_poll_result[$answer] = 1;
                        }
                    } else {
                        if (array_key_exists($answer, $swift_poll_result)) {
                            $curr_rate = $swift_poll_result[$answer];
                            $new_swift_poll_result[$answer] = $curr_rate;
                        } else {
                            $new_swift_poll_result[$answer] = 0;
                        }
                    }
                }
                $swift_settings['polling_result'] = $new_swift_poll_result;
                if (update_option('swift_settings', $swift_settings)) {
                    $poll_final_res = "poll_submitted";
                    setcookie('poll_submitted', '1');
                } else {
                    $poll_final_res = "poll_unchanged";
                }
            }
        }
    }
    echo $poll_final_res;
    wp_die();
}

/* set global position class in swift corner widgets
 *  return : position class name
 */
if (!function_exists('swiftcloud_global_position_class')) {

    function swiftcloud_global_position_class($position) {
        switch ($position) {
            case 'left': {
                    return 'swift_left_bottom';
                    break;
                }
            case 'right': {
                    return 'swift_right_bottom';
                    break;
                }
            case 'center': {
                    return 'swift_center_bottom';
                    break;
                }

            case 'right_center': {
                    return 'swift_right_center';
                    break;
                }
            case 'left_center': {
                    return 'swift_left_center';
                    break;
                }

            case 'left_top': {
                    return 'swift_left_top';
                    break;
                }
            case 'right_top': {
                    return 'swift_right_top';
                    break;
                }
            case 'center_top': {
                    return 'swift_center_top';
                    break;
                }
        }
    }

}