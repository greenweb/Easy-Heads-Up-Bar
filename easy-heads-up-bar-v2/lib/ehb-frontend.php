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
  }

  function ehu_fe_show_bar($i=1)
  {
    if($i>20){ exit; } // this is so we don't have ab infinite loop if there are no records
    $today = date("m/d/Y");
    
    if ( $_POST['home'] == '1' ) {
      $xtra = " AND show_where != 'interior'";
    }else{
      $xtra = " AND show_where != 'home'";
    }
    
    $xtra .= " ORDER BY RAND() LIMIT 0,1";
    $data = $this->ehu_bars_data('all',$xtra); 
    if(!$data) // check of data
    {
     exit;
    }else{ // show data
      //results
      $show_where = $data['show_where'];
      $start_date = $this->ehu_convert_dates($data['start_date']);
      $end_date   = $this->ehu_convert_dates($data['end_date']);
      
      // ===============
      // = Check dates =
      // ===============  
      if ($start_date != NULL ) {
        if(!$this->ehu_compare_str_dates($start_date,$today,"g")){
           $this->ehu_fe_show_bar(++$i);
           exit;
        }else{
          #echo " SD Good To GO "; // debug
        }
      }  // END Check Start Dates  
      
      if ($end_date != NULL ) {
        if(!$this->ehu_compare_str_dates($end_date,$today,"l")){
           $this->ehu_remove_expired($data['ehu_id']);
           $this->ehu_fe_show_bar(++$i);
           exit;
        }else{
          #echo " ED Good To GO "; // debug
        }
      }  // END Check End Dates
      
      $ehu_id         =  $data['ehu_id'];
      $ehu_message    =  $data['message'];
      $ehu_link_text  =  $data['link_text'];
      $ehu_link_url   =  $data['link_url'];
      $ehu_options    =  json_decode($data['options'],true);
      
      $ehu_bgColor    =  $ehu_options['bgColor'];
      $ehu_textColor  =  $ehu_options['textColor'];
      $ehu_linkColor  =  $ehu_options['linkColor'];
      
      $ehu_bar_style  = "background-color: $ehu_bgColor;";
      $ehu_bar_style .= "color: $ehu_textColor;font-size: 14px;";
      $ehu_bar_style .= "font-family: Verdana,Geneva;";
      $ehu_bar_style .= "color: $ehu_textColor;";   
      $ehu_bar_style .= "border-bottom: 4px solid #FFFFFF;";
      $ehu_bar_style .= "box-shadow: 0 1px 5px 0 rgba(0, 0, 0, 0.5);";
      $ehu_bar_style .= "padding: 0px; text-align:center;";
      $ehu_bar_style .= "width: 100%;z-index: 1000;";
      $ehu_bar_style .= "font-weight: normal;height: 30px;line-height: 30px;";
      $ehu_bar_style .= "margin: 0;overflow: visible;position: relative;";    
              
      $ehu_link_style = "color:$ehu_linkColor;";
      
      // open in new window
      $ehu_o_n_window = (isset($ehu_options['winTarget'])) ? $ehu_options['winTarget'] : 0 ;
      $ehu_target = ($ehu_o_n_window == 1 ) ? "target='_blank'" : "" ;
      $ehu_b_html  = '<div id="ehu_bar" style="'.$ehu_bar_style.'">';
      if( $ehu_message !='' || $ehu_message != null )
        $ehu_b_html .= '<span id="ehu_txt">'.$ehu_message.'</span>&nbsp;';
      if( ($ehu_link_text !='' || $ehu_link_text != null) && ($ehu_link_url !='' || $ehu_link_url != null)  )
        $ehu_b_html .= '<a id="ehu_link" style="'.$ehu_link_style.'" href="'.$ehu_link_url.'" '.$ehu_target.'>'.$ehu_link_text.'</a>';
      
      $ehu_b_html .= '</div>';
      
      echo($ehu_b_html);
      die('');
    }        
  } // End fun ehu_fe_show_bar()

  
}