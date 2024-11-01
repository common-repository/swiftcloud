<?php

/*
 *      Pre load data
 */

function swiftcloud_pre_load_data() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'sma_log';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		filename varchar(255) DEFAULT '' NOT NULL,
		date_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		name varchar(255) DEFAULT '' NOT NULL,
		email varchar(255) DEFAULT '' NOT NULL,
		phone varchar(255) DEFAULT '' NOT NULL,
		status TINYINT DEFAULT '0' NOT NULL,
                form_data TEXT, 
		UNIQUE KEY id (id)
	) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);

    //
    $table_name2 = $wpdb->prefix . 'sma_lead_report';
    $sql2 = "CREATE TABLE IF NOT EXISTS `$table_name2` (
                    `id` bigint(20) NOT NULL AUTO_INCREMENT,
                    `lead_date` date NOT NULL DEFAULT '0000-00-00',
                    `lead_pageid` int(11) NOT NULL,
                    `lead_cno` int(11) NOT NULL,
                    PRIMARY KEY (`id`)
                  ) $charset_collate ;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql2);

    //
    $table_welcome_capture = $wpdb->prefix . 'swiftcloud_welcome_capture_list';
    $tbl_welcome_capture_qry = "CREATE TABLE IF NOT EXISTS $table_welcome_capture (
		`wc_id` mediumint(9) NOT NULL AUTO_INCREMENT,
		`wc_headline` varchar(255) DEFAULT '' NOT NULL,
                `wc_form_id` VARCHAR( 20 ) NOT NULL,
		`wc_data` LONGTEXT NOT NULL,
		PRIMARY KEY (`wc_id`)
	) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($tbl_welcome_capture_qry);


    $exit_popup_content = '<div class="exit_popup_cta_buttons"><div class="exit_popup_cta_block"><a href="#" class="cta_yes exit-popup-btn-orange">Yes</a></div><div class="exit_popup_cta_block"><a href="#" class="cta_no close-exit-popup exit-popup-link-blue">I don\'t want this offer</a></div></div>';
    $exit_popup_custom_css = '
.exit_popup_cta_buttons{
    margin: 10px auto 0;
    width: 100%;
    float: left;
    text-align: center;
}
.exit_popup_cta_block{
    display: block;
    margin-bottom: 10px;
}
.exit-popup-btn-orange{
    background-color: #ff7200;
    color: #fff;
    padding: 8px 15px;
    font-size: 14px;
    font-weight: 700;
    border-radius: 3px;
    border: 1px solid #ff7200;
    text-decoration: none;
}
.exit-popup-btn-orange:hover{
    opacity: 0.8;
    color: #fff;
    text-decoration: none;
}
.exit-popup-link-blue{
    display: inline-block;
    text-decoration: underline;
    font-size: 10px;
    color: #196ABC;
}
.exit-popup-link-blue:hover{
    color: #333;
}';

    $get_swift_settings = get_option('swift_settings');
    $get_swift_settings['exit_popup_headline'] = empty($get_swift_settings['exit_popup_headline']) ? "Wait! Before you go..." : esc_html($get_swift_settings['exit_popup_headline']);
    $get_swift_settings['width2'] = empty($get_swift_settings['exit_popup_headline']) ? "480" : esc_html($get_swift_settings['width2']);
    $get_swift_settings['height2'] = empty($get_swift_settings['exit_popup_headline']) ? "360" : esc_html($get_swift_settings['height2']);
    $get_swift_settings['sc_exit_popup_content'] = empty($get_swift_settings['sc_exit_popup_content']) ? $exit_popup_content : esc_html($get_swift_settings['sc_exit_popup_content']);
    $get_swift_settings['exit_popup_custom_css'] = empty($get_swift_settings['exit_popup_custom_css']) ? $exit_popup_custom_css : esc_html($get_swift_settings['exit_popup_custom_css']);
    //cta options 
    $get_swift_settings['cta_show_on'][0] = 'posts';
    $get_swift_settings['cta_dont_show_on'][0] = 'home';
    $get_swift_settings['cta_dont_show_on'][1] = 'blog';
    $get_swift_settings['cta_dont_show_on'][2] = '404';
    $get_swift_settings['cta_html_bg_color'] = '#fff';
    $get_swift_settings['cta_html_font_color'] = '#000';
    //welcome capture options
    $get_swift_settings['show_on_post'][0] = 1;
    $get_swift_settings['dont_show_on'][0] = 'home';
    $get_swift_settings['dont_show_on'][1] = 'blog';
    $get_swift_settings['dont_show_on'][2] = '404';
    $get_swift_settings['cta_html_bg_color'] = '#f16334';
    $get_swift_settings['cta_html_font_color'] = '#fff';
    

    update_option('swift_settings', $get_swift_settings);
}

?>