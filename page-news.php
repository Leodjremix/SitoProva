<?php
/**
 * Template Name: Archivio News
 *
 * Questo template sostituisce il vecchio archivio `news.asp`
 * mostrando tutte le news (post_type 'post') in ordine cronologico inverso.
 * Mantiene le classi CSS legacy richieste per retrocompatibilità stilistica.
 *
 * @package santagatesi
 */

get_header();

// 1. Recupero dati Hero della pagina (Se configurati dal pannello)
$hero_data = get_option('santagatesi_hero_data', array());
// Possiamo usare una chiave generica o pescare i dati della pagina attuale
$page_title = get_the_title();
$hero_bg_dynamic = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'full' ) : 'https://www.santagatesinelmondo.it/public/banner/cripta.jpg';

$inline_style = "background-image: url('" . esc_url( $hero_bg_dynamic ) . "');";
?>

<main id="primary" class="site-main bg-[var(--color-surface)] min-h-screen pb-24">

	<!-- Hero Section -->
	<section class="hero-section relative h-[40vh] min-h-[400px] flex items-center justify-center bg-cover bg-center" style="<?php echo esc_attr( $inline_style ); ?>">
        <div class="absolute inset-0 bg-black/60 z-0"></div> <!-- Overlay scuro per leggibilità -->
		<div class="container relative z-10 text-center pt-24">
			<div class="tonal-panel mx-auto max-w-4xl bg-[var(--color-surface-container-lowest)]/95 backdrop-blur-md p-8 rounded-2xl shadow-[var(--shadow-ambient)] border border-[var(--color-surface-container-low)]">
				<h1 class="text-4xl md:text-5xl font-bold mb-4 text-[var(--color-primary)] font-display"><?php echo esc_html( $page_title ); ?></h1>
				<?php if ( has_excerpt() ) : ?>
                    <p class="text-lg text-[var(--color-on-surface-muted)] font-body max-w-2xl mx-auto"><?php echo get_the_excerpt(); ?></p>
                <?php else : ?>
                    <p class="text-lg text-[var(--color-on-surface-muted)] font-body max-w-2xl mx-auto">Archivio cronologico di tutte le notizie, gli articoli e gli aggiornamenti da Sant'Agata di Puglia.</p>
                <?php endif; ?>
			</div>
		</div>
	</section>

	<!-- Griglia News (Legacy Structure) -->
	<section class="section mt-16">
		<div class="container max-w-5xl mx-auto">

			<?php
            // 2. Impostazione della Paginazione Nativa
            $paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;

			// 3. Query per recuperare tutti i Post (News)
			$news_args = array(
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'posts_per_page' => 10, // Numero di news per pagina (modificabile)
				'paged'          => $paged,
				'orderby'        => 'date',
				'order'          => 'DESC',
			);

			$news_query = new WP_Query( $news_args );

			if ( $news_query->have_posts() ) :
			?>
				<div class="news-list-container space-y-12">

					<?php while ( $news_query->have_posts() ) : $news_query->the_post(); ?>

						<!-- STRUTTURA HTML LEGACY RICHIESTA DAL CLIENTE -->
						<!-- Usiamo Tailwind per l'impaginazione di base, ma manteniamo scrupolosamente le classi originali -->
						<div id="post-<?php the_ID(); ?>" <?php post_class( 'mainnews1 tonal-panel bg-white rounded-2xl p-6 md:p-8 flex flex-col md:flex-row gap-8 shadow-sm hover:shadow-md transition-shadow border border-[var(--color-surface-container-low)]' ); ?>>

                            <!-- Immagine (se presente) -->
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="normalnewsfoto flex-shrink-0 w-full md:w-1/3 aspect-video md:aspect-square overflow-hidden rounded-xl bg-[var(--color-surface-container-low)] relative group">
                                    <a href="<?php the_permalink(); ?>" class="block w-full h-full">
                                        <?php the_post_thumbnail( 'medium_large', array( 'class' => 'w-full h-full object-cover transition-transform duration-500 group-hover:scale-105' ) ); ?>
                                    </a>
                                </div>
                            <?php else : ?>
                                <!-- Fallback visivo se manca l'immagine -->
                                <div class="normalnewsfoto flex-shrink-0 w-full md:w-1/3 aspect-video md:aspect-square overflow-hidden rounded-xl bg-gray-100 flex items-center justify-center text-gray-400">
                                    <a href="<?php the_permalink(); ?>" class="block w-full h-full flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" opacity="0.3"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <!-- Contenuto Testuale -->
							<div class="news-content-wrapper flex flex-col flex-grow justify-center">

                                <!-- Data di pubblicazione -->
                                <span class="datanews text-sm font-semibold tracking-wider text-[var(--color-accent)] uppercase mb-3 flex items-center gap-2 font-body">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                    <?php echo get_the_date(); ?>
                                </span>

                                <!-- Titolo -->
								<div class="mainnewstitle text-2xl md:text-3xl font-bold font-display text-[var(--color-primary)] mb-4 leading-snug">
									<a href="<?php the_permalink(); ?>" class="hover:text-[var(--color-accent)] transition-colors">
										<?php the_title(); ?>
									</a>
								</div>

								<!-- Testo Completo (Content) -->
								<div class="normalnewstext text-[var(--color-on-surface-muted)] font-body leading-relaxed mb-6">
									<?php
                                    // Sostituito the_excerpt() con the_content() per mostrare l'articolo completo nella lista
                                    the_content();
                                    ?>
								</div>

                                <?php
                                // Mostra Video Player se presente anche in lista archivio
                                $video_url = get_post_meta( get_the_ID(), '_news_video_url', true );
                                if ( ! empty( $video_url ) ) :
                                    $embed = wp_oembed_get( $video_url );
                                ?>
                                    <div class="mt-4 mb-8 rounded-xl overflow-hidden shadow-sm bg-[var(--color-surface-container-low)]">
                                        <?php if ( $embed ) : ?>
                                            <div class="aspect-video w-full">
                                                <?php echo $embed; ?>
                                            </div>
                                        <?php else : ?>
                                            <video controls class="w-full rounded-xl shadow-sm">
                                                <source src="<?php echo esc_url( $video_url ); ?>" type="video/mp4">
                                                Il tuo browser non supporta il video.
                                            </video>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

							</div>
						</div>
						<!-- FINE STRUTTURA HTML LEGACY -->

					<?php endwhile; ?>

				</div>

                <!-- 4. Paginazione Nativa WordPress -->
                <div class="mt-16 pt-8 border-t border-[var(--color-surface-container-low)]">
                    <div class="pagination flex justify-center gap-2 font-body font-bold text-lg">
                        <?php
                        echo paginate_links( array(
                            'total'     => $news_query->max_num_pages,
                            'current'   => $paged,
                            'prev_text' => '<span class="px-4 py-2 rounded-lg bg-white border border-gray-200 text-[var(--color-primary)] hover:bg-[var(--color-surface-container-low)] transition-colors">&larr; Precedente</span>',
                            'next_text' => '<span class="px-4 py-2 rounded-lg bg-white border border-gray-200 text-[var(--color-primary)] hover:bg-[var(--color-surface-container-low)] transition-colors">Successiva &rarr;</span>',
                            'type'      => 'list',
                            'before_page_number' => '<span class="px-3 py-2">',
                            'after_page_number'  => '</span>'
                        ) );
                        ?>
                    </div>
                </div>

                <!-- Stili extra per rendere i link della paginazione generati da WP coerenti con Tailwind -->
                <style>
                    .pagination ul { display: flex; flex-wrap: wrap; list-style: none; padding: 0; margin: 0; gap: 0.5rem; justify-content: center; }
                    .pagination li a, .pagination li span.current { display: block; border-radius: 0.5rem; text-decoration: none; transition: all 0.3s ease; }
                    .pagination li a.page-numbers { background: #fff; border: 1px solid #e5e7eb; color: var(--color-primary); }
                    .pagination li a.page-numbers:hover { background: var(--color-surface-container-low); }
                    .pagination li span.current { background: var(--color-primary); color: #fff; border: 1px solid var(--color-primary); }
                    .pagination li span.dots { padding: 0.5rem 1rem; color: var(--color-on-surface-muted); }
                </style>

			<?php
			else :
				// Nessun post trovato
			?>
				<div class="tonal-panel text-center p-12 bg-[var(--color-surface-container-lowest)] rounded-2xl shadow-sm border border-[var(--color-surface-container-low)]">
					<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto text-[var(--color-on-surface-muted)] mb-4 opacity-50"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
					<h3 class="text-2xl font-bold text-[var(--color-primary)] font-display mb-2">Nessuna News Pubblicata</h3>
					<p class="text-[var(--color-on-surface-muted)] font-body text-lg">Al momento non ci sono articoli disponibili nell'archivio.</p>
				</div>
			<?php
			endif;
			wp_reset_postdata(); // Ripristina la post data globale
			?>

		</div>
	</section>

</main>

<?php
get_footer();
