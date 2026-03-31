<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package santagatesi
 */

get_header();
?>

	<main id="primary" class="site-main bg-[var(--color-surface)] pb-24">

		<?php
		while ( have_posts() ) :
			the_post();
			?>

            <!-- Header della Pagina Statico -->
			<header class="page-header pt-32 pb-20 bg-[var(--color-surface-container-lowest)] shadow-[var(--shadow-ambient)] text-center relative overflow-hidden mb-16 border-b border-[var(--color-surface-container-low)]">
                <!-- Background decorativo opzionale -->
                <div class="absolute inset-0 bg-gradient-to-t from-[var(--color-surface)] to-transparent opacity-50 pointer-events-none"></div>
                <div class="container relative z-10 px-4 mt-8">
                    <?php the_title( '<h1 class="page-title text-4xl md:text-6xl font-extrabold text-[var(--color-primary)] font-display tracking-tight leading-tight">', '</h1>' ); ?>
                </div>
			</header><!-- .page-header -->

            <!-- Contenuto della Pagina Statico (Gutenberg Blocks) -->
			<div class="container px-4">
                <article id="post-<?php the_ID(); ?>" <?php post_class( 'tonal-panel bg-white shadow-[var(--shadow-ambient)] rounded-3xl p-8 md:p-16 border-none' ); ?>>

                    <div class="entry-content prose prose-lg prose-blue max-w-none font-body text-lg leading-loose text-[var(--color-on-surface)]">
                        <?php
                        the_content();

                        wp_link_pages(
                            array(
                                'before' => '<div class="page-links">' . esc_html__( 'Pagine:', 'santagatesi' ),
                                'after'  => '</div>',
                            )
                        );
                        ?>
                    </div><!-- .entry-content -->

                </article><!-- #post-<?php the_ID(); ?> -->
			</div>

		<?php endwhile; // End of the loop. ?>

	</main><!-- #primary -->

<?php
get_footer();
