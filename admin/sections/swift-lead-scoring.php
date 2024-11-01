<?php

function swift_lead_scoring_cb() {
    ?>
    <div class="wrap">
        <div class="inner_content">
            <h2>Lead Scoring</h2><hr/>
            <h3><?php _e('Usage instructions:', 'swift-cloud'); ?></h3>
            <p><?php _e('Drop shortcode <label><b>[swiftcloud_lead_scoring name="SetName" value="SetValue"]</b></label> on to any page-body and set cookie for 1 year.', 'swift-cloud'); ?></p>
            <p><?php _e('name = Cookie name<br/>value = Cookie value', 'swift-cloud'); ?></p>
        </div>
    </div>
    <?php
}