<?php

if ( isset( $_GET['activated'] ) && is_admin() ) :

	/*
	 * Create pages on theme activation.
	 */
	function createPage($page_title, $page_template) {

		$page_settings  = array(
			'post_type' => 'page',
			'post_title' => $page_title,
			'post_status' => 'publish'
		);

		$args = array(
		    'post_type'      => 'page',
		    'post_status'    => 'publish',
		    'title'          => $page_title,
		    'posts_per_page' => 1
		);

		$query = new WP_Query($args);

		if ($query->have_posts()) {
		    $query->the_post();
		    $page = get_the_ID();
		    wp_reset_postdata(); 
		} else {
		    $page = wp_insert_post($page_settings);

		    if (!empty($page_template)) {
		        update_post_meta($page, '_wp_page_template', $page_template);
		    }
		}

		/*
		if( !isset( get_page_by_title( $page_title )->ID ) ) {

			$page = wp_insert_post( $page_settings );

			if( !empty( $page_template ) ) {
				update_post_meta( $page, '_wp_page_template', $page_template );
			}

		}*/ 

	}

	createPage('People', 'page-people.php');
	createPage('Archive', 'page-archive.php');
	createPage('Tags', 'page-tag.php');
	createPage('Categories', 'page-category.php');

  /*
   * Set permalink structure.
   */  
  global $wp_rewrite; 

  $wp_rewrite->set_permalink_structure('/%year%/%monthnum%/%day%/%postname%/'); 

  update_option( "rewrite_rules", FALSE ); 

  // Flush the rules and tell it to write htaccess.
  $wp_rewrite->flush_rules( true );

endif;

