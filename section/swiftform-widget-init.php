<?php

/**
 *  Create widget to showing swift form
 */
class swiftform_widget_Init extends WP_Widget {

    var $ErrorMessage = 'Form ID is required to display form!';

    public function __construct() {
        $widget_ops = array(
            'classname' => 'widget_swiftform',
            'description' => __('SwiftForm Widget Setup'),
            'customize_selective_refresh' => true,
            add_shortcode('swiftform', array($this, 'display_swiftform'))
        );
        parent::__construct('swiftform', __('SwiftForm'), $widget_ops);
    }

    function display_swiftform($atts, $content = '') {
        $atts = shortcode_atts(
                array(
            'id' => '',
                ), $atts);

        extract($atts);

        if (empty($id))
            return $this->ErrorMessage;

        if (function_exists('curl_init')) {
            $readFormUrl = 'https://swiftcloud.io/f/' . $id . "/";
            $ch = curl_init();
            $timeout = 5;
            curl_setopt($ch, CURLOPT_URL, $readFormUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $text = curl_exec($ch);
            curl_close($ch);
        } else {
            // curl library is not installed so we better use something else
        }
        return $text;
    }

    public function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '&nbsp;' : $instance['title']);
        $formID = empty($instance['formID']) ? '' : $instance['formID'];

        # Before the widget
        echo $before_widget;

        # The title
        if ($title)
            echo $before_title . $title . $after_title;

        # Make the widget show the form
        if ($formID != "") {

            $formData = array('id' => $formID);

            $text = $this->display_swiftform($formData);

            global $iSubscriberId;
            if ($iSubscriberId > 0)
                $text = preg_replace('/name="iSubscriberId"  value="(\d*)"/', 'name="iSubscriberId"  value="' . $iSubscriberId . '"', $text);

            echo $text;
        }else {
            echo $this->ErrorMessage;
        }

        # After the widget
        echo $after_widget;
    }

    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags(stripslashes($new_instance['title']));
        $instance['formID'] = strip_tags(stripslashes($new_instance['formID']));
        return $instance;
    }

    public function form($instance) {
        //Defaults
        $instance = wp_parse_args((array) $instance, array('title' => '', 'formID' => ''));

        $title = htmlspecialchars($instance['title']);
        $formID = htmlspecialchars($instance['formID']);
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

        <p><label for="<?php echo $this->get_field_id('formID'); ?>"><?php _e('Form ID:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('formID'); ?>" name="<?php echo $this->get_field_name('formID'); ?>" type="text" value="<?php echo $formID; ?>" /></p>
        <?php
    }

}

add_action('widgets_init', function() {
            register_widget('swiftform_widget_Init');
        }
);
?>