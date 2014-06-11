<?php 
/**
* The admin class
*/
class ehbFrontend
{
  function __construct()
  {
    #add_filter( 'the_content', array(&$this, 'ehu_show_bar') );
    add_filter( 'wp_footer', array(&$this, 'ehu_show_bar') );
    add_action( 'wp_enqueue_scripts', array(&$this, 'ehu_load_scripts') );
  }

  function ehu_show_bar()
  {
    // Set up the vars
    global $ehb_meta_prefix;
    $prefix     = $ehb_meta_prefix;
    $bar_html   = "";
    $bar_array  = "";
    $today      = current_time('m/d/Y');
    $tomorrow   = date('m/d/Y', strtotime($today) + 86400);
    $frontpage  = is_front_page();
    $i = 0;

    // WP_Query arguments
    $args = array(
      'post_type'   => 'heads_up_bar',
      'post_status' => 'publish',
    );

    // The Query
    $query = new WP_Query( $args );
    // The Loop
    if ( $query->have_posts() ) {
      while ( $query->have_posts() ) {
        $query->the_post();
        $bar_ID = $query->post->ID;

        $show = true;
        $show_where = get_post_meta( $bar_ID,"{$prefix}show_where", true );
        if( ($show_where == 'home' ) && (!$frontpage) ) $show=false;
        if( ($show_where == 'interior' ) && ($frontpage) ) $show=false;        
        if( $show )
        {
          $start_date = get_post_meta( $bar_ID,"{$prefix}start_date", true ); 
          $start_date = ($start_date=="") ? $today : $start_date ;   
          $check_start_date = ehu_check_date($start_date,  $today);
          if(false !== $check_start_date)
          {
            $end_date       = get_post_meta( $bar_ID,"{$prefix}end_date", true );
            $end_date       = ($end_date=="") ? $tomorrow : $end_date ;
            $check_end_date = ehu_check_date( $today, $end_date);
            if(false !== $check_end_date)
            {
              $bar_content      = get_post_meta( $bar_ID,"{$prefix}bar_content",       true );
              $bar_location     = get_post_meta( $bar_ID,"{$prefix}bar_location",      true );
                if($bar_location=='top')
                {
                  $bar_border_locatoin = "bottom";
                }else{
                  $bar_border_locatoin = "top";
                }
              $bar_bg_color     = get_post_meta( $bar_ID,"{$prefix}bar_bg_color",      true );
              $bar_border_color = get_post_meta( $bar_ID,"{$prefix}bar_border_color",  true );
              $bar_text_color   = get_post_meta( $bar_ID,"{$prefix}text_color",        true );
              $bar_link_color   = get_post_meta( $bar_ID,"{$prefix}link_color",        true );
              $bar_hide         = get_post_meta( $bar_ID,"{$prefix}hide_bar",          true );
              // lets build a bar workshop ;)
              $bar_html .= "<div id='ehu-bar'";
              $bar_html .= " data-bar-link-color='{$bar_link_color}'";
              $bar_html .= " data-bar-location='{$bar_location}'";
              $bar_html .= " data-hide-bar='{$bar_hide}'";
              $bar_html .= " style='background-color:{$bar_bg_color};border-{$bar_border_locatoin}: 4px solid {$bar_border_color};padding: 6px;'>";
              
              $bar_html .= "  <div id='ehu-close-button' ";
              $bar_html .=      "style='display:block;";
              $bar_html .=      "float:right;";
              $bar_html .=      "background-color:{$bar_text_color};";
              $bar_html .=      "color:{$bar_bg_color};";
              $bar_html .=      "padding:6px 10px;";
              $bar_html .=      "margin-top:-2px;";
              $bar_html .=      "margin-right:10px;";
              $bar_html .=      "font-weight:bolder;";
              $bar_html .=      "cursor:pointer;'>";
              $bar_html .=      "X</div>";

              $bar_html .= "  <div style='display:block;color:{$bar_text_color};padding:2px;margin:0 auto;width: 90%;'>";
              $bar_html .=      apply_filters('the_content', $bar_content); 
              $bar_html .= "  </div>";
              $bar_html .= "  <br style='clear:both;height:1px;'>";
              $bar_html .= "</div>";
              $bar_html .= "  <div id='ehu-open-button' ";
              $bar_html .=      "style='display:inline;";
              $bar_html .=      "visibility: hidden;";
              $bar_html .=      "position: fixed;";
              $bar_html .=      "z-index: 100001;";
              $bar_html .=      "{$bar_location}:4px;";
              $bar_html .=      "right: 16px;";
              $bar_html .=      "background-color:{$bar_text_color};";
              $bar_html .=      "color:{$bar_bg_color};";
              $bar_html .=      "padding:6px 10px;";
              $bar_html .=      "font-weight:bold;";
              $bar_html .=      "cursor:pointer;'>";
              $bar_html .=      "HB</div>";
              $bar_array[$i] = $bar_html; $i++;
              //reset the $bar_html
              $bar_html = "";
            }
          } // end if(false !== $check_start_date)
        } // end if($how)
      }// end while loop 
    } else {
      // no posts found
    }
    if (is_array($bar_array) && !empty($bar_array)) 
    {
      $random_bar = array_random($bar_array);
      echo $random_bar;      
    }
    // Restore original Post Data
    wp_reset_postdata();
  } // End fun ehu_show_bar()


  /**
   * Load the scripts
   */
  function ehu_load_scripts() {
    $ehb_js_url     = EHB_URL. 'js/ehu.js';
    wp_enqueue_script('ehb_js_url',$ehb_js_url,array('jquery'), EHB_VERSION,true );
    // wp_enqueue_style( 'style-name', get_stylesheet_uri() );
  }




}
$ehbFrontend = new ehbFrontend();