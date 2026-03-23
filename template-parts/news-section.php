<?php
/**
 * Template part for displaying the News section
 *
 * @package santagatesi
 */

$news_args = array(
	'post_type'           => 'post',
	'posts_per_page'      => 3,
	'ignore_sticky_posts' => 1,
);

$news_query = new WP_Query( $news_args );
?>

<section id="news" class="section bg-opacity-50">
	<div class="container">
		<header class="section-header text-center mb-8">
			<h2 class="text-3xl font-bold mb-4">Ultime Notizie</h2>
			<p class="text-color-muted">Rimani aggiornato su cosa succede a Sant'Agata e nel mondo.</p>
		</header>

		<?php if ( $news_query->have_posts() ) : ?>
			<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
				<?php
				while ( $news_query->have_posts() ) :
					$news_query->the_post();
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'glass-panel news-card' ); ?>>
						<?php if ( has_post_thumbnail() ) : ?>
							<a href="<?php the_permalink(); ?>">
								<?php the_post_thumbnail( 'medium_large', array( 'class' => 'news-thumbnail' ) ); ?>
							</a>
						<?php else: ?>
                            <!-- Fallback thumbnail if none is set -->
                            <a href="<?php the_permalink(); ?>">
                                <div class="news-thumbnail flex items-center justify-center bg-gray-800" style="background-color: #1e293b;">
                                    <span class="text-gray-500">Nessuna Immagine</span>
                                </div>
                            </a>
                        <?php endif; ?>

						<div class="news-content mt-4">
                            <div class="post-meta text-sm text-color-muted mb-2">
                                <?php echo get_the_date(); ?> &bull; di <?php the_author(); ?>
                            </div>
							<h3 class="news-title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h3>
							<div class="news-excerpt">
								<?php the_excerpt(); ?>
							</div>
							<div class="mt-auto pt-4">
								<a href="<?php the_permalink(); ?>" class="text-accent hover:text-white font-semibold">Leggi di più &rarr;</a>
							</div>
						</div>
					</article>
				<?php endwhile; ?>
			</div>

			<div class="text-center mt-12">
				<a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="btn btn-primary">Vedi tutte le notizie</a>
			</div>
		<?php else : ?>
			<p class="text-center">Nessuna notizia trovata.</p>
		<?php endif; ?>

		<?php wp_reset_postdata(); ?>
	</div>
</section>
