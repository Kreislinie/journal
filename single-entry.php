<?php
/**
 * The template for displaying entry single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package bitjournal
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="grid__main site-main">
      <div class="area__content">

        <?php
        while ( have_posts() ) :
          the_post();

          get_template_part( 'template-parts/content', get_post_type() );

        endwhile; // End of the loop.
        ?>

      </div><!-- .area__content -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();