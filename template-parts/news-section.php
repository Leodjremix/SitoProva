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

<section id="news" class="section bg-[var(--color-surface-container-lowest)]">
	<div class="container">
		<header class="section-header text-center mb-12">
			<h2 class="text-4xl font-bold mb-4">Ultime Notizie</h2>
			<p class="text-lg text-color-on-surface-muted font-body">Rimani aggiornato su cosa succede a Sant'Agata e nel mondo.</p>
		</header>

		<?php if ( $news_query->have_posts() ) : ?>
			<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
				<?php
				while ( $news_query->have_posts() ) :
					$news_query->the_post();
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'tonal-panel-low news-card' ); ?>>
						<?php if ( has_post_thumbnail() ) : ?>
							<a href="<?php the_permalink(); ?>">
								<?php the_post_thumbnail( 'medium_large', array( 'class' => 'news-thumbnail w-full h-48 object-cover rounded-t-xl mb-4' ) ); ?>
							</a>
						<?php else: ?>
                            <!-- Fallback thumbnail if none is set -->
                            <a href="<?php the_permalink(); ?>">
                                <div class="news-thumbnail flex items-center justify-center bg-[var(--color-surface)] w-full h-48 rounded-t-xl mb-4">
                                    <span class="text-[var(--color-on-surface-muted)]">Nessuna Immagine</span>
                                </div>
                            </a>
                        <?php endif; ?>

						<div class="news-content mt-4 px-2">
                            <div class="post-meta text-sm text-[var(--color-on-surface-muted)] mb-2 font-body font-semibold">
                                <?php echo get_the_date(); ?> &bull; di <?php the_author(); ?>
                            </div>
							<h3 class="news-title text-2xl font-bold text-[var(--color-primary)] mb-3 leading-snug">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h3>
							<div class="news-excerpt text-[var(--color-on-surface-muted)] mb-4 font-body leading-relaxed">
								<?php the_excerpt(); ?>
							</div>
							<div class="mt-auto pt-4 border-t border-[var(--color-surface)]">
								<a href="<?php the_permalink(); ?>" class="text-[var(--color-accent)] hover:text-[var(--color-primary)] font-semibold font-body transition-colors">Leggi di più &rarr;</a>
							</div>
						</div>
					</article>
				<?php endwhile; ?>
			</div>

			<div class="text-center mt-12">
				<a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="btn btn-primary">Vedi tutte le notizie</a>
			</div>
		<?php else : ?>
			<p class="text-center text-[var(--color-on-surface-muted)]">Nessuna notizia trovata.</p>
		<?php endif; ?>

		<?php wp_reset_postdata(); ?>
	</div>
</section>
