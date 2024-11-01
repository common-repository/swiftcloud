<?php
/*
 *      Swift Cloud Lead Report(Dashboard widget)
 */
add_action('wp_dashboard_setup', 'swift_lead_report', 10, 5);

function swift_lead_report() {
    wp_add_dashboard_widget(
            'swift_cloud_lead_report', 'Swift Cloud Lead Report', 'swift_lead_report_output'
    );
}

function swift_lead_report_output() {
    wp_enqueue_script('chart-min', plugins_url('/js/Chart.min.js', __FILE__), '', '', true);
    wp_enqueue_style('swift-admin-style', plugins_url('/css/admin.css', __FILE__), '', '', '');

    global $post, $wpdb;
    $daycount = 0;

    $qry = 'SELECT lead_date, COUNT( * ) AS lead_count FROM ' . $wpdb->prefix . 'sma_lead_report WHERE month(curdate()) = month(lead_date) group by day(lead_date)';
    $get_data = $wpdb->get_results($qry);
    if (isset($get_data) && !empty($get_data)) {
        foreach ($get_data as $lead_report_date) {
            $x_axis[] = '"' . date('M jS', strtotime($lead_report_date->lead_date)) . '"';
            $y_axis[] = $lead_report_date->lead_count;
            $daycount++;
        }
        $x_axis = implode(",", $x_axis);
        $y_axis = implode(",", $y_axis);
    }
    ?>
    <div style="width: 100%">
        <?php if (!empty($get_data)) { ?>
            <div><canvas id="swiftCloudCanvas" height="500" width="500"></canvas></div>
            <div class="swift_summery_report">
                <div class="top-lead-page" >
                    <h3 class="top-lead-title"><strong>Top 5 Lead Pages:</strong></h3>
                    <?php
                    $qry1 = 'SELECT lead_pageid,COUNT(lead_pageid) as lead_pagecount FROM ' . $wpdb->prefix . 'sma_lead_report GROUP BY lead_pageid LIMIT 5';
                    $get_PageCount_data = $wpdb->get_results($qry1);
                    foreach ($get_PageCount_data as $page_count) {
                        ?>
                        <p class="top-lead-list"><a href="<?php echo get_permalink($page_count->lead_pageid); ?>" target="_blank"><?php echo get_the_title($page_count->lead_pageid) . ": " . $page_count->lead_pagecount; ?></a></p>
                    <?php } ?>
                </div><!-- /top-lead-page-->
                <div class="top-pages-viewed">
                    <h3 class="top-pages-title"><strong>Top Pages Viewed:</strong></h3>
                    <p class="top-pages-list">Coming soon..</p>
                </div>
            </div>
            <?php
        } else {
            echo "<div style='text-align:center'><h2>No data to report</h2> <br />Click <a href='admin.php?page=sma_admin_dispplay_log_settings'>here</a> to check your settings</div>";
        }
        ?>
    </div>
    <?php if (!empty($get_data)) { ?>
        <script type="text/javascript">
            var swiftcloud_leadreport_data = {
                labels: [<?php echo $x_axis; ?>],
                datasets: [
                    {
                        fillColor: "rgba(25, 106, 188,0.2)",
                        strokeColor: "rgba(25, 106, 188,1)",
                        pointColor: "rgba(25, 106, 188,1)",
                        pointStrokeColor: "rgba(25, 106, 188,1)",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(25, 106, 188,1)",
                        data: [<?php echo $y_axis; ?>]
                    }
                ]
            };

            var swiftcloud_leadreport_option = {
                animationEasing: "easeInOutExpo",
                scaleBeginAtZero: true,
                scaleShowGridLines: true,
                scaleShowVerticalLines: false,
                scaleGridLineColor: "rgba(0,0,0,0.2)",
                responsive: true,
                bezierCurve: false,
                pointDotRadius: 3,
                pointDotStrokeWidth: 1,
                pointHitDetectionRadius: 0,
                tooltipFillColor: "rgba(255,255,255,1)",
                tooltipFontColor: "#000",
                tooltipTitleFontStyle: "bold",
                tooltipCaretSize: 8,
                tooltipCornerRadius: 1
            };

            jQuery(document).ready(function () {
                var ctx = document.getElementById("swiftCloudCanvas").getContext("2d");
                window.swiftCloudLeadReport = new Chart(ctx).Line(swiftcloud_leadreport_data, swiftcloud_leadreport_option);
            });
        </script>
        <?php
    }
}

function sma_getLeadPageId() {
    //c=55604&confirm=1&firstname=Test+Test
    global $post, $wpdb;

    if (isset($_COOKIE['sma_lead_page_id']) && !empty($_COOKIE['sma_lead_page_id']) && isset($_GET['c']) && !empty($_GET['c']) && isset($_GET['confirm']) && !empty($_GET['confirm']) && $_GET['confirm'] == 1) {

        $today_date = date('Y-m-d');
        $pageid = sanitize_text_field($_COOKIE['sma_lead_page_id']);
        $cno = sanitize_text_field($_GET['c']);

        $qry = 'SELECT * FROM ' . $wpdb->prefix . 'sma_lead_report WHERE lead_cno =' . $cno;
        $getLeadReportData = $wpdb->get_results($qry);

        if (count($getLeadReportData) <= 0) {
            $wpdb->insert(
                    $wpdb->prefix . 'sma_lead_report', array('lead_date' => $today_date, 'lead_pageid' => $pageid, 'lead_cno' => $cno), array('%s', '%d', '%d', '%d')
            );
        }
        setcookie('sma_log_id', 0, time() - 3600);
    }
}

add_action('init', 'sma_getLeadPageId', 1);

function add_hidden_pageid() {
    global $post;
    if (isset($post) && !empty($post) && $post) {
        echo '<input type="hidden" name="sma_lead_page_id" id="sma_lead_page_id" value="' . esc_attr($post->ID) . '" />';
    }
}

add_action('wp_head', 'add_hidden_pageid', 2);
?>