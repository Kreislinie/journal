<?php

/**
 * Hide menu-pages on admin sidebar.
 */
function bj_remove_menus() {  

  // remove_menu_page( 'edit.php?post_type=page' );    //Pages  
  remove_menu_page( 'edit-comments.php' );          //Comments  
  // remove_menu_page( 'themes.php' );                 //Appearance  
  // remove_menu_page( 'plugins.php' );                //Plugins  
  remove_menu_page( 'users.php' );                  //Users  
  remove_menu_page( 'tools.php' );                  //Tools  
  // remove_menu_page( 'options-general.php' );        //Settings  
  remove_menu_page( 'index.php' );                  //Dashboard
  // remove_menu_page( 'edit.php' );                  //Post edit

}  
add_action( 'admin_menu', 'bj_remove_menus' ); 

function bj_admin_menu_image() {
    
  add_menu_page(
    'Page Title', 
    '<img src="https://d1u5p3l4wpay3k.cloudfront.net/minecraft_gamepedia/8/85/Knowledge_book.png"><h3>bitjournal</h3>', 
    'edit_posts', 
    'click-action', 
    'your_new_menu', 
    'your-favicon-path', 1);
}
add_action('admin_menu', 'bj_admin_menu_image');

/**
 * Create menu for people taxonomy
 * 
 * @link https://wp-kama.com/49/register-taxonomy-without-post-type
 * 
 */
function bitjournal_add_people_menu() {

	$taxname = 'people';

	$is_people = isset( $_GET['taxonomy'] ) && $_GET['taxonomy'] === $taxname;

	// cancel 'current' for posts (taxonomy attaches there by default, even if not set post_type when taxonomy is registered
	$is_people && add_filter( 'parent_file', function( $parent_file ) {
		return false;
	} );

	// add a taxonomy menu item
  $menu_title = 'People';
  
	add_menu_page( 'People', $menu_title, 'manage_options', "edit-tags.php?taxonomy=$taxname", null, 'dashicons-groups', 9 );
  
  // fix some parameters of the added menu item
  $menu_item = & $GLOBALS['menu'][ key( wp_list_filter( $GLOBALS['menu'], [$menu_title] ) ) ];
  
	foreach( $menu_item as & $val ) {
		// add 'current' class if need
		if( false !== strpos( $val, 'menu-top' ) )
			$val = 'menu-top'. ( $is_people ? ' current' : '' );

		$val = preg_replace( '~toplevel_page[^ ]+~', "toplevel_page_$taxname", $val );
  }
  
}

add_action( 'admin_menu', 'bitjournal_add_people_menu' );


/**
 * Change dashboard Posts name to Entries.
 */
function cp_change_post_object() {
  $get_post_type = get_post_type_object('post');
  $labels = $get_post_type->labels;
    $labels->name = __('Entries');
    $labels->singular_name = __('Entry');
    $labels->add_new = __('Add Entry');
    $labels->add_new_item = __('Add Entry');
    $labels->edit_item = __('Edit Entry');
    $labels->new_item = __('News');
    $labels->view_item = __('View Entry');
    $labels->search_items = __('Search Entries');
    $labels->not_found = __('No Entries found');
    $labels->not_found_in_trash = __('No Entries found in Trash');
    $labels->all_items = __('All Entries');
    $labels->menu_name = __('Entries');
    $labels->name_admin_bar = __('Entry');
}
add_action( 'init', 'cp_change_post_object' );

// change icon for entries
function ccd_menu_news_icon() {
global $menu;
foreach ( $menu as $key => $val ) {
  if ( __( 'Entries') == $val[0] ) {
    $menu[$key][6] = 'dashicons-edit';
  }
}
}
add_action( 'admin_menu', 'ccd_menu_news_icon' );


/**
 * Create admin redirect menu items
 * 
 * Creates menu item and loads wp_redirect befor headers are sent.
 */
function bj_admin_menu() { 

  // define slugs
  define ( 'BJ_SLUG_ENTRIES', __('entries', 'bitjournal') );
  define ( 'BJ_SLUG_PEOPLE', __('people', 'bitjournal') );

  // create menu items
  add_menu_page( 
    __( 'Entries', 'bitjournal' ), 
    __( 'Entries', 'bitjournal' ),
    'edit_posts', 
    BJ_SLUG_ENTRIES, 
    'page_callback_function', 
    'dashicons-welcome-write-blog'
  );

  add_menu_page( 
    __( 'people', 'bitjournal' ), 
    __( 'people', 'bitjournal' ),
    'edit_posts', 
    BJ_SLUG_ENTRIES, 
    'page_callback_function', 
    'dashicons-groups'
  );

  // handle redirections
  function bj_preprocess_pages() {

    global $pagenow;

    $page = ( isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : false );

      if( $pagenow == 'admin.php' && $page == BJ_SLUG_ENTRIES ){
        wp_redirect( home_url() );
        exit;
      } elseif ( $pagenow == 'admin.php' && $page == BJ_SLUG_PEOPLE ) {
        wp_redirect( home_url() );
        exit;
      }
      
    }
    add_action( 'admin_init', 'bj_preprocess_pages' );

}
add_action( 'admin_menu', 'bj_admin_menu' );