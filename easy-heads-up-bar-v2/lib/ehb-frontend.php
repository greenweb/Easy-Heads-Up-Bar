<?php 
/**
* The admin class
*/
class ehbFrontend
{
  function __construct()
  {
    // Hook into the 'init' action
    #add_action( 'init', array($this,'ehb_add_post_type'), 0 );
    // Help Bar
    #add_action( 'contextual_help', array(&$this,'ehb_custom_help_tabs'), 10,3 );
    // heads_up_bar post updated messages
    #add_filter( 'post_updated_messages', array(&$this, 'ehb_updated_messages') );
    add_filter( 'the_content', array(&$this, 'ehu_show_bar') );
  }

  function ehu_show_bar()
  {
    $today = get_the_date();

    // WP_Query arguments
    $args = array(
      'post_type'              => 'heads_up_bar',
      'post_status'            => 'publish',
    );

    // The Query
    $query = new WP_Query( $args );

    $return = "<pre>".print_r($query,true)."</pre>";
    return $return;
  } // End fun ehu_fe_show_bar()

  
}
$ehbFrontend = new ehbFrontend();