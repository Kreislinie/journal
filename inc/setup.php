<?php
/**
 * Create needed pages on theme activation
 *
 * @package bitjournal
 */

if ( isset( $_GET['activated'] ) && is_admin() ) {

  $people_title = __('People', 'bitjournal');
  $people_page_template = 'page-people.php';
  $people_check = get_page_by_title( $people_title );

  /**
   * Array for wp_insert_post 
   * 
   * @link https://developer.wordpress.org/reference/functions/wp_insert_post
   */   
  $people_page = array(
    'post_type' => 'page',
    'post_title' => $people_title,
    'post_status' => 'publish'
  );

  // check if page does not exist
  if( !isset( $people_check->ID ) ) {
      $people_page_id = wp_insert_post( $people_page );
      if( !empty( $people_page_template ) ) {
        update_post_meta( $people_page_id, '_wp_page_template', $people_page_template );
      }
  }

  /*
   * Set permalink structure 
   */   
  global $wp_rewrite; 

  $wp_rewrite->set_permalink_structure('/%year%/%monthnum%/%day%/'); 

  //Set the option
  update_option( "rewrite_rules", FALSE ); 

  //Flush the rules and tell it to write htaccess
  $wp_rewrite->flush_rules( true );

}