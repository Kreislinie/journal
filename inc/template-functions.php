<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package bitjournal
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function bitjournal_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'bitjournal_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function bitjournal_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'bitjournal_pingback_header' );


/** 
* Display archive post count between <span> tags
*/
function bj_archive_post_count( $link_html ) {
  $link_html = str_replace( '</a>&nbsp;(', '</a> <span class="archiveCount">', $link_html );
  $link_html = str_replace( ')', '</span>', $link_html );
  return $link_html;
}

add_filter( 'get_archives_link', 'bj_archive_post_count' );

/** 
* Hide default WP post type
*/
function bj_remove_default_post_type() {
  remove_menu_page( 'edit.php' );
}

add_action( 'admin_menu', 'bj_remove_default_post_type' );

function bj_remove_default_post_type_menu_bar( $wp_admin_bar ) {
  $wp_admin_bar->remove_node( 'new-post' );
}

add_action( 'admin_bar_menu', 'bj_remove_default_post_type_menu_bar', 999 );

function bj_remove_draft_widget(){
    remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
}

add_action( 'wp_dashboard_setup', 'bj_remove_draft_widget', 999 );
