<?php 
/**
* The admin class
*/
class ehbFrontend
{
  function __construct()
  {
    add_filter( 'the_content', array(&$this, 'ehu_show_bar') );
  }

  function ehu_show_bar()
  {
    $today = get_the_date();
    $return = "";
    // WP_Query arguments
    $args = array(
      'post_type'              => 'heads_up_bar',
      'post_status'            => 'publish',
    );

    // The Query
    $query = new WP_Query( $args );
  // The Loop
  if ( $query->have_posts() ) {
    while ( $query->have_posts() ) {
      $query->the_post();
      $return .= get_the_title();
    }
  } else {
    // no posts found
  }
  return $return;
  // Restore original Post Data
  wp_reset_postdata();
   // $return = "<pre>".print_r($query,true)."</pre>";
  } // End fun ehu_fe_show_bar()
}
$ehbFrontend = new ehbFrontend();