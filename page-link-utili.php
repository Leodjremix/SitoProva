<?php
/**
 * Template Name: Link Utili
 *
 * Questo template raccoglie tutti i "Link Utili" salvati dall'amministratore,
 * raggruppandoli dinamicamente sotto i titoli delle rispettive categorie.
 *
 * @package santagatesi
 */

get_header();

// Setup Intestazione Dinamica (Se non ne hai una specifica per questa pagina, uso fallback)
$hero_bg_fallback = get_stylesheet_directory_uri() . '/images/links-bg.jpg';
$hero_bg_dynamic  = get_option( 'santagatesi_img_chisiamo', $hero_bg_fallback ); // Fallback su un'immagine esistente
if ( empty( $hero_bg_dynamic ) ) {
    $hero_bg_dynamic = $hero_bg_fallback;
}
$inline_style = "background-image: url('" . esc_url( $hero_bg_dynamic ) . "');";
?>

<main id="primary" class="site-main bg-[var(--color-surface)] min-h-screen pb-24">

	<!-- Hero Section -->
	<section class="hero-section relative h-[35vh] min-h-[300px] flex items-center justify-center bg-cover bg-center" style="<?php echo esc_attr( $inline_style ); ?>">
        <div class="absolute inset-0 bg-black/60 z-0"></div> <!-- Overlay scuro -->
		<div class="container relative z-10 text-center">
			<div class="tonal-panel mx-auto max-w-3xl bg-[var(--color-surface-container-lowest)]/95 backdrop-blur-md p-6 rounded-2xl shadow-lg">
				<h1 class="text-3xl md:text-5xl font-bold mb-3 text-[var(--color-primary)] font-display">Attività e Link Utili</h1>
				<p class="text-lg text-[var(--color-on-surface-muted)] font-body">Scopri i servizi, le strutture ricettive e le attività consigliate a Sant'Agata di Puglia.</p>
			</div>
		</div>
	</section>

	<section class="section mt-16">
		<div class="container">

			<?php
			// 1. Recupera tutte le categorie ("link_category") che hanno almeno un link associato
			$terms = get_terms( array(
				'taxonomy'   => 'link_category',
				'hide_empty' => true,
			) );

			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) :

				// Ciclo per ogni categoria
				foreach ( $terms as $term ) :

					// 2. Query: Per questa categoria, prendi solo i link con _visibilita_frontend = 1
					$links_query = new WP_Query( array(
						'post_type'      => 'santagatesi_links',
						'posts_per_page' => -1,
						'post_status'    => 'publish',
						'orderby'        => 'title',
						'order'          => 'ASC',
						'tax_query'      => array(
							array(
								'taxonomy' => 'link_category',
								'field'    => 'term_id',
								'terms'    => $term->term_id,
							),
						),
						'meta_query'     => array(
							array(
								'key'     => '_visibilita_frontend',
								'value'   => '1',
								'compare' => '=',
							),
						),
					) );

					// 3. Stampa la Categoria e le sue Card SOLO se la query ha trovato risultati visibili
					if ( $links_query->have_posts() ) :
					?>

						<!-- Blocco Categoria -->
						<div class="link-category-block mb-16">
							<h2 class="text-3xl font-bold font-display text-[var(--color-primary)] border-b-2 border-[var(--color-accent)] inline-block pb-2 mb-8">
								<?php echo esc_html( $term->name ); ?>
							</h2>

							<!-- Griglia dei Link per questa Categoria -->
							<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

								<?php while ( $links_query->have_posts() ) : $links_query->the_post();
									// Recupero i dati dai Custom Meta Fields creati nel Backend
									$url         = get_post_meta( get_the_ID(), '_link_url', true );
									$descrizione = get_post_meta( get_the_ID(), '_link_descrizione', true );
									$telefono    = get_post_meta( get_the_ID(), '_link_telefono', true );
								?>

									<!-- Singola Card Link -->
									<div class="link-card tonal-panel bg-[var(--color-surface-container-lowest)] p-6 rounded-xl shadow-[var(--shadow-ambient)] border border-[var(--color-surface-container-low)] flex flex-col transition-all duration-300 hover:shadow-[var(--shadow-hover)] hover:-translate-y-1">

										<h3 class="text-xl font-bold font-display text-[var(--color-primary)] mb-3">
											<?php the_title(); ?>
										</h3>

										<?php if ( ! empty( $descrizione ) ) : ?>
											<p class="text-[var(--color-on-surface-muted)] text-sm font-body mb-4 flex-grow">
												<?php echo nl2br( esc_html( $descrizione ) ); ?>
											</p>
										<?php else : ?>
											<div class="flex-grow"></div> <!-- Spacer se non c'è desc -->
										<?php endif; ?>

										<div class="contact-info mt-auto pt-4 border-t border-[var(--color-surface-container-low)] flex flex-col gap-2">

											<?php if ( ! empty( $telefono ) ) : ?>
												<div class="flex items-center gap-2 text-[var(--color-on-surface-muted)] text-sm font-body">
													<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[var(--color-accent)]"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
													<a href="tel:<?php echo esc_attr( preg_replace('/[^0-9+]/', '', $telefono) ); ?>" class="hover:text-[var(--color-primary)] hover:underline"><?php echo esc_html( $telefono ); ?></a>
												</div>
											<?php endif; ?>

											<?php if ( ! empty( $url ) ) : ?>
												<div class="flex items-center gap-2 text-sm font-body">
													<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[var(--color-accent)]"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
													<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer" class="text-[var(--color-accent)] font-semibold hover:underline truncate">
														<?php esc_html_e( 'Visita il Sito Web', 'santagatesi' ); ?>
													</a>
												</div>
											<?php endif; ?>

										</div>

									</div>
									<!-- /Singola Card Link -->

								<?php endwhile; ?>
							</div>
						</div>
						<!-- /Blocco Categoria -->

					<?php
					endif;
					wp_reset_postdata(); // Ripristina dati dopo ogni categoria

				endforeach; // Fine ciclo categorie

			else :
				// Nessuna categoria o nessun link presente
			?>
				<div class="tonal-panel text-center p-12 bg-[var(--color-surface-container-lowest)] rounded-2xl shadow-sm border border-[var(--color-surface-container-low)]">
					<h3 class="text-2xl font-bold text-[var(--color-primary)] font-display mb-2">Sezione in Aggiornamento</h3>
					<p class="text-[var(--color-on-surface-muted)] font-body text-lg">Al momento non ci sono attività pubbliche in questa sezione. Torna a trovarci presto!</p>
				</div>
			<?php endif; ?>

		</div>
	</section>

</main>

<?php
get_footer();
