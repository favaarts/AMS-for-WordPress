<?php


get_header();


?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<div id="content" class="site-content ams-content">
        
		<div id="content-inside" class="container no-sidebar">
			<div id="primary" class="content-area">
				<main id="main" class="site-main" role="main">

					<?php while ( have_posts() ) : the_post(); ?>

						<?php the_content(); ?>

						<?php
							// If comments are open or we have at least one comment, load up the comment template.
							if ( comments_open() || get_comments_number() ) :
								comments_template();
							endif;
						?>

					<?php endwhile; // End of the loop. ?>

				</main><!-- #main -->
			</div><!-- #primary -->
		</div><!--#content-inside -->
	</div><!-- #content -->
</article><!-- #post-## -->
<?php get_footer(); ?>
