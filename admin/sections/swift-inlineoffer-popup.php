<?php

function swift_inlineoffer_popup_cb() {
    ?>
    <div class="wrap">
        <!-- SwiftCloud User Guide -->
        <div class="inner_content">
            <h2>Inline Offer Popup</h2><hr/>
            <div>
                <p><?php _e('Add following shortcode to display inline popup.', 'swift-cloud'); ?></p>
                <input type="text" value='[swiftcloud_inlineoffer bgcolor="" id="" form_id="" PopupHeadline="" PopupImage="" PopupLidID="" PopupButton=""]Content goes here [swiftcloud_inlineoffer_capturedcontents]secured content goes here which will appear after user captured....[/swiftcloud_inlineoffer_capturedcontents][/swiftcloud_inlineoffer]' onclick="this.select();" style="width: 100%;" readonly="readonly"/>
                <p><b><?php _e('Attributes:', 'swift-cloud'); ?></b></p>
                <ul style="margin-left: 15px;">
                    <li><b><?php _e('bgcolor: ', 'swift-cloud'); ?></b><?php _e('Set background color of content', 'swift-cloud'); ?></li>
                    <li><b><?php _e('id: ', 'swift-cloud'); ?></b><?php _e('ID of whole content', 'swift-cloud'); ?></li>
                    <li><b><?php _e('form_id: ', 'swift-cloud'); ?></b><?php _e('Swift Form ID', 'swift-cloud'); ?></li>
                    <li><b><?php _e('PopupHeadline: ', 'swift-cloud'); ?></b><?php _e('Set popup headline', 'swift-cloud'); ?></li>
                    <!--<li><b><?php //_e('PopupLidID: ', 'swift-cloud'); ?></b><?php _e('', 'swift-cloud'); ?></li>-->
                    <li><b><?php _e('PopupImage: ', 'swift-cloud'); ?></b><?php _e('Add image in popup; only set image URL', 'swift-cloud'); ?></li>
                    <li><b><?php _e('PopupButton: ', 'swift-cloud'); ?></b><?php _e('Set button text', 'swift-cloud'); ?></li>
                </ul>
                <p><b>Example:</b></p>
                <p> [swiftcloud_inlineoffer bgcolor='#fbfcb9' id='idhere' form_id="1" PopupHeadline='Headline goes here' PopupImage="image url here" PopupLidID="000" PopupButton="Click Here"]<pre>Content goes here Click Here [swiftcloud_inlineoffer_capturedcontents]secured content goes here which will appear after user captured....[/swiftcloud_inlineoffer_capturedcontents]</pre>[/swiftcloud_inlineoffer]</p>
            </div>
        </div>
    </div>
<?php } ?>
