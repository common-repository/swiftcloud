<?php

function swift_polling_cb() {
    wp_enqueue_script('poll-chart-min', plugins_url('../js/Chart.min.js', __FILE__), '', '', true);
    ?>
    <div class="wrap">
        <div class="inner_content">
            <h2>Polling</h2><hr/>
            <?php
            $poll_max_answers = 5;
            $swift_settings = get_option('swift_settings');
            if (isset($_POST['save_polling']) && wp_verify_nonce($_POST['save_polling'], 'save_polling')) {
                $swift_settings['polling_enable'] = (isset($_POST['swift_settings']['polling_enable']) && !empty($_POST['swift_settings']['polling_enable'])) ? 1 : 0;
                $swift_settings['polling_show_on'] = sanitize_text_or_array_field($_POST['swift_settings']['polling_show_on']);
                $swift_settings['polling_open_after'] = sanitize_text_field($_POST['swift_settings']['polling_open_after']);
                $swift_settings['polling_question'] = sanitize_text_field($_POST['swift_settings']['polling_question']);
                $swift_settings['polling_widget_position'] = sanitize_text_field($_POST['swift_settings']['polling_widget_position']);
                $submitted_answer = array_filter(sanitize_text_or_array_field($_POST['swift_settings']['polling_answers']));
                $swift_settings['polling_answers'] = implode("/*/", $submitted_answer);

                $update = update_option('swift_settings', $swift_settings);
            }

            if (isset($update) && !empty($update)) {
                echo '<div id="message" class="updated below-h2"><p>Settings updated successfully!</p></div>';
            }
            if (isset($update_history) && !empty($update_history)) {
                echo '<div id="message" class="updated below-h2"><p>Poll reset. Now you can add new poll.</p></div>';
            }

            // get polling answers
            $polling_answer_arr = array();
            $polling_answers = (isset($swift_settings['polling_answers']) && !empty($swift_settings['polling_answers'])) ? esc_attr($swift_settings['polling_answers']) : "";
            if (isset($polling_answers) && !empty($polling_answers)) {
                $polling_answer_arr = @explode("/*/", $polling_answers);
            }
            ?>
            <div class="polling_left">
                <form name="frm_welcome_capture" id="frm_welcome_capture" method="post" enctype="multipart/form-data">
                    <table class="form-table">
                        <tr>
                            <th><label for="polling_enable">Polling is</label></th>
                            <td>
                                <?php $pollOnOff = (isset($swift_settings['polling_enable']) && !empty($swift_settings['polling_enable']) && $swift_settings['polling_enable'] == 1 ? 'checked="checked"' : ""); ?>
                                <input type="checkbox" value="1" data-ontext="ON" data-offtext="OFF" name="swift_settings[polling_enable]" id="polling_enable" class="polling_enable" <?php echo $pollOnOff; ?>>
                            </td>
                        </tr>
                        <tr class="hideMe" style="<?php echo ((isset($swift_settings['polling_enable']) && !empty($swift_settings['polling_enable']) && $swift_settings['polling_enable'] == 1) ? 'visibility: visible;' : 'display:none'); ?>">
                            <th>Show on</th>
                            <td>
                                <?php
                                $checkedHome = $checkedBlog = $checked404 = $checkedCpt = '';
                                if (!empty($swift_settings['polling_show_on'])) {
                                    $checkedHome = (in_array('home', $swift_settings['polling_show_on'])) ? 'checked="checked"' : '';
                                    $checkedBlog = (in_array('blog', $swift_settings['polling_show_on']) ? 'checked="checked"' : '');
                                    $checkedPages = (in_array('pages', $swift_settings['polling_show_on']) ? 'checked="checked"' : '');
                                    $checkedPost = (in_array('post', $swift_settings['polling_show_on']) ? 'checked="checked"' : '');
                                    $checked404 = (in_array('404', $swift_settings['polling_show_on']) ? 'checked="checked"' : '');
                                    $checkedCpt = (in_array('cpt', $swift_settings['polling_show_on']) ? 'checked="checked"' : '');
                                } else {
                                    $checkedPages = 'checked="checked"';
                                    $checkedPost = 'checked="checked"';
                                }
                                ?>
                                <label for="dont_show_on1"><input type="checkbox" id="dont_show_on1" name="swift_settings[polling_show_on][]" value="home" <?php echo $checkedHome; ?> />Home Page</label>&nbsp;&nbsp;
                                <label for="dont_show_on2"><input type="checkbox" id="dont_show_on2" name="swift_settings[polling_show_on][]" value="blog" <?php echo $checkedBlog; ?>/>Blog Page</label>&nbsp;&nbsp;
                                <label for="dont_show_on3"><input type="checkbox" id="dont_show_on3" name="swift_settings[polling_show_on][]" value="pages"  <?php echo $checkedPages; ?>/>Pages</label>&nbsp;&nbsp;
                                <label for="dont_show_on4"><input type="checkbox" id="dont_show_on4" name="swift_settings[polling_show_on][]" value="post"  <?php echo $checkedPost; ?>/>Posts</label>&nbsp;&nbsp;
                                <label for="dont_show_on5"><input type="checkbox" id="dont_show_on5" name="swift_settings[polling_show_on][]" value="404"  <?php echo $checked404; ?>/>404 Page</label>&nbsp;&nbsp;
                                <label for="dont_show_on6"><input type="checkbox" id="dont_show_on6" name="swift_settings[polling_show_on][]" value="cpt"  <?php echo $checkedCpt; ?>/>Custom Post Type</label>&nbsp;&nbsp;
                            </td>
                        </tr>
                        <tr class="hideMe" style="<?php echo ((isset($swift_settings['polling_enable']) && !empty($swift_settings['polling_enable']) && $swift_settings['polling_enable'] == 1) ? 'visibility: visible;' : 'display:none'); ?>">
                            <th><label for="polling_widget_position">Widget Position</label></th>
                            <td>
                                <select id="polling_widget_position" name="swift_settings[polling_widget_position]">
                                    <?php $polling_widget_position = (isset($swift_settings['polling_widget_position']) && !empty($swift_settings['polling_widget_position'])) ? $swift_settings['polling_widget_position'] : ""; ?>
                                    <option value="right" <?php echo ($polling_widget_position == 'right' ? 'selected="selected"' : ''); ?>>Right</option>
                                    <option value="center" <?php echo ($polling_widget_position == 'center' ? 'selected="selected"' : ''); ?>>Center</option>
                                    <option value="left" <?php echo ($polling_widget_position == 'left' ? 'selected="selected"' : ''); ?>>Left</option>
                                </select>
                            </td>
                        </tr>
                        <tr class="hideMe" style="<?php echo ((isset($swift_settings['polling_enable']) && !empty($swift_settings['polling_enable']) && $swift_settings['polling_enable'] == 1) ? 'visibility: visible;' : 'display:none'); ?>">
                            <th><label for="polling_open_after">Open after</label></th>
                            <td><input type="number" min="0" name="swift_settings[polling_open_after]" id="polling_open_after" value="<?php echo (isset($swift_settings['polling_open_after']) && !empty($swift_settings['polling_open_after'])) ? esc_attr($swift_settings['polling_open_after']) : ""; ?>" /> seconds</td>
                        </tr>
                        <tr class="hideMe" style="<?php echo ((isset($swift_settings['polling_enable']) && !empty($swift_settings['polling_enable']) && $swift_settings['polling_enable'] == 1) ? 'visibility: visible;' : 'display:none'); ?>">
                            <th><label for="polling_question">Question</label></th>
                            <td><textarea id="polling_question" placeholder="What is your favorite color?" rows="3" cols="50" class="" name="swift_settings[polling_question]"><?php echo (isset($swift_settings['polling_question']) && !empty($swift_settings['polling_question'])) ? esc_attr($swift_settings['polling_question']) : ""; ?></textarea></td>
                        </tr>
                        <tr class="hideMe" style="<?php echo ((isset($swift_settings['polling_enable']) && !empty($swift_settings['polling_enable']) && $swift_settings['polling_enable'] == 1) ? 'visibility: visible;' : 'display:none'); ?>">
                            <th>
                                <label for="polling_answers">Answers </label>
                                <input id="polling_ans_count" type="hidden" value="<?php echo (isset($swift_settings['polling_answers']) && !empty($swift_settings['polling_answers'])) ? count($swift_settings['polling_answers']) : ""; ?>"/>
                            </th>
                            <td id="polling_ans_td">
                                <?php if (isset($polling_answer_arr) && !empty($polling_answer_arr)): ?>
                                    <?php $poll_result_cnt = 1; ?>
                                    <?php foreach ($polling_answer_arr as $poll_ans): ?>
                                        <div class="polling_answer_container_<?php echo $poll_result_cnt; ?>">
                                            <input type="text" name="swift_settings[polling_answers][]" value="<?php echo esc_attr($poll_ans); ?>" class="poll_answer" >
                                            <?php if ($poll_result_cnt > 3): ?>
                                                <a href="#" class="remove_field" title="Remove Answer"><img src="<?php echo plugins_url("../images/sc_remove.png", __FILE__); ?>" alt="remove" /></a>
                                            <?php endif; ?>
                                            <?php $poll_result_cnt++; ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div><input type="text" name="swift_settings[polling_answers][]" placeholder="Red"></div>
                                    <div><input type="text" name="swift_settings[polling_answers][]" placeholder="Green"></div>
                                    <div><input type="text" name="swift_settings[polling_answers][]" placeholder="Blue"></div>
                                <?php endif; ?>

                                <div class="input_fields_wrap">
                                    <input type="button" class="polling_btn add_field_button" value="+ Add Answer / Option">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <?php wp_nonce_field('save_polling', 'save_polling') ?>
                                <input type="submit" class="button button-primary" value="Save Changes" />
                            </th>
                        </tr>
                    </table>
                </form>
            </div>
            <?php if (!empty($swift_settings['polling_result'])) { ?>
                <div class="polling_right">
                    <?php
                    $res_poll_chart = $swift_settings['polling_result'];
                    $poll_chart_data = array();
                    $pcnt = 1;
                    $polltotal = '';
                    if (!empty($res_poll_chart)) {
                        foreach ($res_poll_chart as $pdatakey => $pdata) {
                            $poll_chart_data[$pcnt]['label'] = $pdatakey;
                            $poll_chart_data[$pcnt]['value'] = $pdata;
                            $polltotal = $polltotal + $pdata;
                            $pcnt++;
                        }
                    }
                    ?>
                    <div class="poll_chart_div hideMe" style="<?php echo (($swift_settings['polling_enable'] == "1") ? 'visibility: visible;' : 'display:none'); ?>">
                        <canvas id="poll_chart_canvas" height="400" width="400"></canvas>
                        <div class="poll_result">
                            <p><strong>Total Answers: </strong><?php echo $polltotal; ?></p>
                            <?php
                            foreach ($poll_chart_data as $pdata) {
                                $pval = $pdata['value'] * 100 / $polltotal;
                                echo "<p><strong>" . $pdata['label'] . "</strong>: " . round($pval, 2) . "%</p>";
                            }
                            ?>
                        </div>
                        <div class="poll_flush">
                            <form id="frmPollFlush" method="post" action="" >
                                <?php wp_nonce_field('swift_flush_poll', 'swift_flush_poll') ?>
                                <input type="button" name="poll_data_flush" id="poll_data_flush" class="button button-primary" value="Flush Data & Start New Poll"/>
                            </form>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <!----------------POLL HISTORY ------------------->
            <?php
            if (isset($_POST['swift_flush_poll']) && wp_verify_nonce($_POST['swift_flush_poll'], 'swift_flush_poll')) {
                $swift_settings['poll_history_question'] = sanitize_text_field($swift_settings['polling_question']);
                $swift_settings['poll_history_answer'] = sanitize_text_field($swift_settings['polling_answers']);
                $swift_settings['poll_history_result'] = sanitize_text_field($swift_settings['polling_result']);
                $swift_settings['polling_question'] = '';
                $swift_settings['polling_answers'] = '';
                $swift_settings['polling_result'] = '';
                $update_history = update_option('swift_settings', $swift_settings);
                wp_redirect("?page=swift_polling");
            }
            ?>
            <?php if (!empty($swift_settings['poll_history_question']) && !empty($swift_settings['poll_history_answer']) && !empty($swift_settings['poll_history_result'])) { ?>
                <div class="poll_history">
                    <hr/>
                    <h2>Poll History</h2>
                    <?php
                    $res_poll_history_chart = $swift_settings['poll_history_result'];
                    $poll_history_chart_data = array();
                    $phcnt = 1;
                    $poll_history_total = '';
                    if (!empty($res_poll_history_chart)) {
                        foreach ($res_poll_history_chart as $phdatakey => $phdata) {
                            $poll_history_chart_data[$phcnt]['label'] = $phdatakey;
                            $poll_history_chart_data[$phcnt]['value'] = $phdata;
                            $poll_history_total = $poll_history_total + $phdata;
                            $phcnt++;
                        }
                    }
                    ?>
                    <div class="polling_history_left">
                        <table class="form-table">
                            <tr>
                                <th><label>Question: </label></th>
                                <td><?php echo esc_attr($swift_settings['poll_history_question']); ?></td>
                            </tr>
                            <tr>
                                <th><label>Answer(s): </label></th>
                                <td><?php echo str_replace("/*/", "<br />", esc_attr($swift_settings['poll_history_answer'])); ?></td>
                            </tr>
                            <tr>
                                <th><label>Total Answers: </label></th>
                                <td><?php echo $poll_history_total; ?></td>
                            </tr>
                            <tr>
                                <th>&nbsp;</th>
                                <td>
                                    <?php
                                    foreach ($poll_history_chart_data as $phdata) {
                                        $pval = $phdata['value'] * 100 / $poll_history_total;
                                        echo "<p><strong>" . $phdata['label'] . "</strong>: " . round($pval, 2) . "%</p>";
                                    }
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="polling_history_right">
                        <div class="poll_history_chart">
                            <canvas id="poll_history_chart_canvas" height="250" width="250"></canvas>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <script type="text/javascript">
            jQuery(document).ready(function($) {
            jQuery('.polling_enable:checkbox').rcSwitcher().on({
            'turnon.rcSwitcher': function(e, dataObj) {
            // to do on turning on a switch
            jQuery(".hideMe").show();
            },
                      'turnoff.rcSwitcher': function(e, dataObj) {
                      // to do on turning off a switch
                      jQuery(".hideMe").hide();
                      },
            });
            // add/remove polling answer
            var max_fields = <?php echo $poll_max_answers; ?> //maximum input boxes allowed
            var wrapper = $(".input_fields_wrap"); //Fields wrapper
            var add_button = $(".add_field_button"); //Add button ID

            $(add_button).on("click", function(e) { //on add input button click
            e.preventDefault();
            var x = $(".poll_answer").length; //initlal text box count
            if (x < max_fields) { //max input box allowed
            x++; //text box increment
            $(wrapper).append('<div><input type="text" name="swift_settings[polling_answers][]" class="poll_answer"/> <a href="#" class="remove_field" title="Remove Answer"><img src="<?php echo plugins_url("../images/sc_remove.png", __FILE__); ?>" alt="remove" /></a></div>'); //add input box
            } else {
            alert("You can not enter more than 5 answers.");
            }
            });
            $(".remove_field").click(function(e) {
            e.preventDefault();
            var polling_answer_container = $(this).parent().attr("class");
            $("." + polling_answer_container).remove();
            });
            $(wrapper).on("click", ".remove_field", function(e) { //user click on remove text
            e.preventDefault();
            $(this).parent('div').remove();
            //x--;
            });
            //poll flush form submit
            $("#poll_data_flush").click(function() {
            if (confirm('Heads up! If you change the questions and/or answers, we will save and then flush the data to restart the stats. Click Abort to keep this poll running, or Proceed to start a new one....')) {
            $("#frmPollFlush").submit();
            } else {

            }
            });
            }); //document.ready
    <?php if (!empty($res_poll_chart)) { ?>
                //Chart
                var poll_chart_data = [
        <?php if (!empty($poll_chart_data[1]['value']) && $poll_chart_data[1]['value'] > 0) { ?>
                    {
                    value: <?php echo $poll_chart_data[1]['value']; ?>,
                              color: "#F7464A",
                              label: "<?php echo $poll_chart_data[1]['label']; ?>"
                    },
        <?php } ?>
        <?php if (!empty($poll_chart_data[2]['value']) && $poll_chart_data[2]['value'] > 0) { ?>
                    {
                    value: <?php echo $poll_chart_data[2]['value']; ?>,
                              color: "#45A31F",
                              label: "<?php echo $poll_chart_data[2]['label']; ?>"
                    },
        <?php } ?>
        <?php if (!empty($poll_chart_data[3]['value']) && $poll_chart_data[3]['value'] > 0) { ?>
                    {
                    value: <?php echo $poll_chart_data[3]['value']; ?>,
                              color: "#5B90BF",
                              label: "<?php echo $poll_chart_data[3]['label']; ?>",
                    },
        <?php } ?>
        <?php if (!empty($poll_chart_data[4]['value']) && $poll_chart_data[4]['value'] > 0) { ?>
                    {
                    value: <?php echo $poll_chart_data[4]['value']; ?>,
                              color: "#ff5319",
                              label: "<?php echo $poll_chart_data[4]['label']; ?>"
                    },
        <?php } ?>
        <?php if (!empty($poll_chart_data[5]['value']) && $poll_chart_data[5]['value'] > 0) { ?>
                    {
                    value: <?php echo $poll_chart_data[5]['value']; ?>,
                              color: "#FAD160",
                              label: "<?php echo $poll_chart_data[5]['label']; ?>"
                    }
        <?php } ?>
                ]
                          var poll_chart_opiton = {};
    <?php } ?>
            //Poll History Chart
    <?php if (!empty($swift_settings['poll_history_result'])) { ?>
                var poll_history_chart_data = [

        <?php if (!empty($poll_history_chart_data[1]['value']) && $poll_history_chart_data[1]['value'] > 0) { ?>
                    {
                    value: <?php echo $poll_history_chart_data[1]['value']; ?>,
                              color: "#F7464A",
                              label: "<?php echo $poll_history_chart_data[1]['label']; ?>"
                    },
        <?php } ?>
        <?php if (!empty($poll_history_chart_data[2]['value']) && $poll_history_chart_data[2]['value'] > 0) { ?>
                    {
                    value: <?php echo $poll_history_chart_data[2]['value']; ?>,
                              color: "#45A31F",
                              label: "<?php echo $poll_history_chart_data[2]['label']; ?>"
                    },
        <?php } ?>
        <?php if (!empty($poll_history_chart_data[3]['value']) && $poll_history_chart_data[3]['value'] > 0) { ?>
                    {
                    value: <?php echo $poll_history_chart_data[3]['value']; ?>,
                              color: "#5B90BF",
                              label: "<?php echo $poll_history_chart_data[3]['label']; ?>",
                    },
        <?php } ?>
        <?php if (!empty($poll_history_chart_data[4]['value']) && $poll_history_chart_data[4]['value'] > 0) { ?>
                    {
                    value: <?php echo $poll_history_chart_data[4]['value']; ?>,
                              color: "#ff5319",
                              label: "<?php echo $poll_history_chart_data[4]['label']; ?>"
                    },
        <?php } ?>
        <?php if (!empty($poll_history_chart_data[5]['value']) && $poll_history_chart_data[5]['value'] > 0) { ?>
                    {
                    value: <?php echo $poll_history_chart_data[5]['value']; ?>,
                              color: "#FAD160",
                              label: "<?php echo $poll_history_chart_data[5]['label']; ?>"
                    }
        <?php } ?>
                ]
                          var poll_history_chart_opiton = {};
    <?php } ?>

            //Init Charts
            window.onload = function() {
    <?php if (!empty($res_poll_chart)) { ?>
                var poll_chart = document.getElementById("poll_chart_canvas").getContext("2d");
                var new_poll_chart = new Chart(poll_chart).Pie(poll_chart_data, poll_chart_opiton);
    <?php } ?>
    <?php if (!empty($swift_settings['poll_history_result'])) { ?>
                var poll_history_chart = document.getElementById("poll_history_chart_canvas").getContext("2d");
                var new_poll_history_chart = new Chart(poll_history_chart).Pie(poll_history_chart_data, poll_history_chart_opiton);
    <?php } ?>
            };
        </script>
    </div>
<?php } ?>
