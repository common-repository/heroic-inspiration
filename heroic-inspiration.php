<?php
/*
Plugin Name: Heroic Inspiration Plugin
Plugin URI: http://heroicinspiration.com 
Description: A plugin widget to provide inspiration.
Version: 1
Author: Nick Loadholtes
Author URI: https://ironboundsoftware.com 
*/


global $heroicinsp_widget_plugin_table;
global $heroicinsp_widget_plugin_db_version;
global $wpdb;
$heroicinsp_widget_plugin_table = $wpdb->prefix . 'heroicinsp_widget_plugin';
$heroicinsp_widget_plugin_db_version = '1.0';

register_activation_hook( __FILE__,  'heroicinsp_widget_plugin_install' );

function heroicinsp_widget_plugin_install() {
  global $wpdb;
  global $heroicinsp_widget_plugin_table;
  global $heroicinsp_widget_plugin_db_version;

  if ( $wpdb->get_var( $wpdb->prepare("show tables like '%s'", $heroicinsp_widget_plugin_table)) != $heroicinsp_widget_plugin_table ) {
      $sql = $wpdb->prepare("CREATE TABLE %s (". 
	     "id int NOT NULL AUTO_INCREMENT, ".
	     "user_text text NOT NULL, ".
	     "UNIQUE KEY id (id) ".
                            ")", $heroicinsp_widget_plugin_table);

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    add_option( "heroicinsp_widget_plugin_db_version", $heroicinsp_widget_plugin_db_version );
  }
}

class HeroicInspWidget extends WP_Widget {
  function HeroicInspWidget() {
    parent::WP_Widget( false, $name = 'Heroic Inspiration' );
  }

  function widget( $args, $instance ) {
    extract( $args );
    $title = apply_filters( 'widget_title', $instance['title'] );
    ?>

    <?php
	echo $before_widget;
    ?>

    <?php
      if ($title) {
	echo $before_title . $title . $after_title;
      }
    ?>

    <div class="heroicinsp_textbox">
         <div id="heroicinsp_quotediv">
               <h3></h3><b></b>
         </div>
    </div>
               <!-- Powered by Heroic Inspiration! https://heroicinspiration.com -->

     <?php
       echo $after_widget;
     ?>
     <?php
  }

  function update( $new_instance, $old_instance ) {
    return $new_instance;
  }

  function form( $instance ) {
    $title = esc_attr( $instance['title'] );
    ?>

    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?>
      <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
      </label>
    </p>
    <?php
  }
}

add_action( 'widgets_init', 'HeroicInspWidgetInit' );
function HeroicInspWidgetInit() {
  register_widget( 'HeroicInspWidget' );
}


add_action( 'wp_head', 'heroicinsp_widget_plugin_js_header' );

function heroicinsp_widget_plugin_js_header() {
  ?>
   <script type="text/javascript">
     //<![CDATA[
     jQuery(document).ready(function($) {
         var sample_quotes = [["Don't watch the clock; do what it does. Keep going.", "Sam Levenson"],
                              ["The harder the conflict, the more glorious the triumph.","Thomas Paine"],
                              ["It does not matter how slowly you go as long as you do not stop.","Confucius"]];
         var i = Math.floor(sample_quotes.length*Math.random());
         $("#heroicinsp_quotediv").empty();
         $("#heroicinsp_quotediv").append("<h3>" + sample_quotes[i][0] + "</h3><b>  --"+sample_quotes[i][1]+"</b>");
     });
     //]]>
   </script>
  <?php
}

?>
