<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package santagatesi
 */

get_header();
?>

	<main id="primary" class="site-main">
		<div class="container section">
			<?php
			if ( have_posts() ) :

				/* Start the Loop */
				while ( have_posts() ) :
					the_post();

					/*
					 * Include the Post-Type-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
					 */
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'glass-panel mb-8' ); ?>>
						<header class="entry-header mb-4">
							<?php
							if ( is_singular() ) :
								the_title( '<h1 class="entry-title text-3xl font-bold">', '</h1>' );
							else :
								the_title( '<h2 class="entry-title text-2xl font-bold"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
							endif;

							if ( 'post' === get_post_type() ) :
								?>
								<div class="entry-meta text-sm text-color-muted mt-2">
									<?php echo get_the_date(); ?> &bull; di <?php the_author(); ?>
								</div><!-- .entry-meta -->
							<?php endif; ?>
						</header><!-- .entry-header -->

						<?php if ( has_post_thumbnail() && ! is_singular() ) : ?>
							<div class="post-thumbnail mb-4">
								<a href="<?php the_permalink(); ?>">
									<?php the_post_thumbnail( 'large', array( 'class' => 'rounded-lg w-full object-cover max-h-96' ) ); ?>
								</a>
							</div>
						<?php elseif ( has_post_thumbnail() && is_singular() ) : ?>
							<div class="post-thumbnail mb-6">
								<?php the_post_thumbnail( 'large', array( 'class' => 'rounded-lg w-full object-cover max-h-96' ) ); ?>
							</div>
						<?php endif; ?>

						<div class="entry-content">
							<?php
							if ( is_singular() ) :
								the_content();
							else :
								the_excerpt();
							endif;
							?>
						</div><!-- .entry-content -->
					</article>
					<?php

				endwhile;

				the_posts_navigation();

			else :

				?>
				<section class="no-results not-found glass-panel text-center py-12">
					<header class="page-header mb-4">
						<h1 class="page-title text-3xl font-bold"><?php esc_html_e( 'Nothing Found', 'santagatesi' ); ?></h1>
					</header><!-- .page-header -->

					<div class="page-content">
						<?php
						if ( is_search() ) :
							?>
							<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'santagatesi' ); ?></p>
							<?php
							get_search_form();

						else :
							?>
							<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'santagatesi' ); ?></p>
							<?php
							get_search_form();

						endif;
						?>
					</div><!-- .page-content -->
				</section><!-- .no-results -->
				<?php

			endif;
			?>
		</div>
	</main><!-- #primary -->

<?php
get_footer();
