<?php 
/**
* The admin class
*/
class ehbAdmin
{
  function __construct()
  {
    // Hook into the 'init' action
    add_action( 'init', array($this,'ehb_add_post_type'), 0 );
    // Help Bar
    add_action( 'contextual_help', array(&$this,'ehb_custom_help_tabs'), 10,3 );
    // heads_up_bar post updated messages
    add_filter( 'post_updated_messages', array(&$this, 'ehb_updated_messages') );
  }
  
  function ehb_custom_help_tabs($contextual_help, $screen_id, $screen) 
  {
    // easy-sign-up_page_ehb_view_simple_data_page
    if ( 'edit-heads_up_bar' !== $screen->id ) // Return early if we're not on the esu options page.
      return;
    // Setup help tab args.
    $making_use_of_txt =  '<h3>'.__('Using Easy Heads Up Bar','ehb_lang').'</h3>';
    $ehb_forum_text  = "<h3>". __("Do you need more help?",'ehb_lang')."</h3><p>". __("If you need support or want to suggest improvements to the plugin please visit the ",'ehb_lang');
    $ehb_forum_text .= '<a href="http://wordpress.org/support/plugin/easy-sign-up/">'.__("plugin's support forum",'ehb_lang').'</a></p>';

    $making_use_of_args = array(
      'id'      => 'ehb_making_use_of_tab', //unique id for the tab
      'title'   => __('Heads Up Bar','ehb_lang'), //unique visible title for the tab
      'content' => $making_use_of_txt.$ehb_forum_text  //actual help text
    );
    // Setup extra feed args
    $ehb_extras_args = array(
      'id'      => 'ehb_extras_tab', //unique id for the tab
      'title'   => __('Easy Extras','ehb_lang'), //unique visible title for the tab
      'content' => ehb_pro(),  //actual help text
    );
    // Setup News args
    $ehb_extras_news_args = array(
      'id'      => 'ehb_news_tab', //unique id for the tab
      'title'   => __('News','ehb_lang'), //unique visible title for the tab
      'content' => ehb_news(),  //actual help text
    );
    // Add the help tabs.
    $making_use_of_tabs   = $screen->add_help_tab( $making_use_of_args );
    $ehb_extras_tabs      = $screen->add_help_tab( $ehb_extras_args );
    $ehb_extras_news_tabs = $screen->add_help_tab( $ehb_extras_news_args );
  }
  // Register Custom Post Type
  function ehb_add_post_type() {
    $labels = array(
      'name'                => _x( 'Heads up Bars', 'Post Type General Name', 'ehb_lang' ),
      'singular_name'       => _x( 'Heads up Bar', 'Post Type Singular Name', 'ehb_lang' ),
      'menu_name'           => __( 'Heads up Bar', 'ehb_lang' ),
      'parent_item_colon'   => __( 'NA', 'ehb_lang' ),
      'all_items'           => __( 'All Heads up Bars', 'ehb_lang' ),
      'view_item'           => __( 'View Heads up Bar', 'ehb_lang' ),
      'add_new_item'        => __( 'Add New Heads up Bar', 'ehb_lang' ),
      'add_new'             => __( 'New Heads up Bar', 'ehb_lang' ),
      'edit_item'           => __( 'Edit Heads up Bar', 'ehb_lang' ),
      'update_item'         => __( 'Update Heads up Bar', 'ehb_lang' ),
      'search_items'        => __( 'Search bars', 'ehb_lang' ),
      'not_found'           => __( 'No bars found', 'ehb_lang' ),
      'not_found_in_trash'  => __( 'No bars found in Trash', 'ehb_lang' ),
    );

    $args = array(
      'label'               => __( 'heads_up_bar', 'ehb_lang' ),
      'description'         => __( 'Easy Heads up Bars', 'ehb_lang' ),
      'labels'              => $labels,
      'supports'            => array( 'title' ),
      'taxonomies'          => array(),
      'hierarchical'        => false,
      'public'              => false,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_nav_menus'   => false,
      'show_in_admin_bar'   => true,
      'menu_position'       => 100,
      'menu_icon'           => 'dashicons-megaphone',
      'can_export'          => true,
      'has_archive'         => false,
      'exclude_from_search' => true,
      'publicly_queryable'  => false,
      'rewrite'             => false,
      'capability_type'     => 'page',
    );
    register_post_type( 'heads_up_bar', $args );
  }
  /**
   * bar update messages.
   * See /wp-admin/edit-form-advanced.php
   * @param array $messages Existing post update messages.
   * @return array Amended post update messages with new CPT update messages.
   */
  function ehb_updated_messages( $messages ) {
    $post             = get_post();
    $post_type        = 'heads_up_bar';//get_post_type( $post );
    $post_type_object = get_post_type_object( $post_type );

    $messages['heads_up_bar'] = array(
      0  => '', // Unused. Messages start at index 1.
      1  => __( 'Heads Up Bar updated.', 'ehb_lang' ),
      2  => __( 'Custom field updated.', 'ehb_lang' ),
      3  => __( 'Custom field deleted.', 'ehb_lang' ),
      4  => __( 'Heads Up Bar updated.', 'ehb_lang' ),
      /* translators: %s: date and time of the revision */
      5  => isset( $_GET['revision'] ) ? sprintf( __( 'Heads Up Bar restored to revision from %s', 'ehb_lang' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
      6  => __( 'Heads Up Bar published.', 'ehb_lang' ),
      7  => __( 'Heads Up Bar saved.', 'ehb_lang' ),
      8  => __( 'Heads Up Bar submitted.', 'ehb_lang' ),
      9  => sprintf(
        __( 'Heads Up Bar scheduled for: <strong>%1$s</strong>.', 'ehb_lang' ),
        // translators: Publish box date format, see http://php.net/date
        date_i18n( __( 'M j, Y @ G:i', 'ehb_lang' ), strtotime( $post->post_date ) )
      ),
      10 => __( 'Heads Up Bar draft updated.', 'ehb_lang' ),
    );

    if ( $post_type_object->publicly_queryable ) {
      $permalink = get_permalink( $post->ID );

      $view_link = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), __( 'View Bar', 'ehb_lang' ) );
      $messages[ $post_type ][1] .= $view_link;
      $messages[ $post_type ][6] .= $view_link;
      $messages[ $post_type ][9] .= $view_link;

      $preview_permalink = add_query_arg( 'preview', 'true', $permalink );
      $preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), __( 'Preview Bar', 'ehb_lang' ) );
      $messages[ $post_type ][8]  .= $preview_link;
      $messages[ $post_type ][10] .= $preview_link;
    }

    return $messages;
  }

}