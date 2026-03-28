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
			// 1. Recupera tutte le categorie ("categorie_link" o vecchia "link_category") che hanno almeno un link associato
			$terms = get_terms( array(
				'taxonomy'   => array('categorie_link', 'link_category'),
				'hide_empty' => true,
			) );

			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) :

				// Ciclo per ogni categoria
				foreach ( $terms as $term ) :

					// 2. Query: Per questa categoria, prendi solo i link con _visibilita_frontend = 1
					$links_query = new WP_Query( array(
						'post_type'      => array('link_utili', 'santagatesi_links'), // Supports both new and old CPT slugs just in case
						'posts_per_page' => -1,
						'post_status'    => 'publish',
						'orderby'        => 'title',
						'order'          => 'ASC',
						'tax_query'      => array(
                            'relation' => 'OR',
							array(
								'taxonomy' => 'categorie_link',
								'field'    => 'term_id',
								'terms'    => $term->term_id,
							),
                            array(
								'taxonomy' => 'link_category', // Supports old taxonomy
								'field'    => 'term_id',
								'terms'    => $term->term_id,
							),
						),
						'meta_query'     => array(
                            'relation' => 'OR',
							array(
								'key'     => '_visibilita_frontend',
								'value'   => '1',
								'compare' => '=',
							),
                            array(
                                'key'     => '_visibilita_frontend',
                                'compare' => 'NOT EXISTS'
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
							<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

								<?php while ( $links_query->have_posts() ) : $links_query->the_post();
									// Recupero i dati dai Custom Meta Fields creati nel Backend
									$url         = get_post_meta( get_the_ID(), '_link_url', true );
									$descrizione = get_post_meta( get_the_ID(), '_link_descrizione', true );
									$telefono    = get_post_meta( get_the_ID(), '_link_telefono', true );

                                    // Scegliamo un'icona SVG basandoci sul nome della categoria, o una generica
                                    $cat_name_lower = strtolower( $term->name );
                                    $icon_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>'; // Info generica

                                    if ( strpos( $cat_name_lower, 'mangiare' ) !== false || strpos( $cat_name_lower, 'ristorant' ) !== false ) {
                                        $icon_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"></path><path d="M7 2v20"></path><path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"></path></svg>'; // Forchetta e coltello
                                    } elseif ( strpos( $cat_name_lower, 'dormire' ) !== false || strpos( $cat_name_lower, 'b&b' ) !== false || strpos( $cat_name_lower, 'hotel' ) !== false ) {
                                        $icon_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2 4v16"></path><path d="M2 8h18a2 2 0 0 1 2 2v10"></path><path d="M2 17h20"></path><path d="M6 8v9"></path></svg>'; // Letto
                                    } elseif ( strpos( $cat_name_lower, 'comune' ) !== false || strpos( $cat_name_lower, 'serviz' ) !== false || strpos( $cat_name_lower, 'istituzion' ) !== false ) {
                                        $icon_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"></path><path d="M9 8h1"></path><path d="M9 12h1"></path><path d="M9 16h1"></path><path d="M14 8h1"></path><path d="M14 12h1"></path><path d="M14 16h1"></path><path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"></path></svg>'; // Palazzo/Edificio
                                    }
								?>

									<!-- Nuova Card Link Stilizzata -->
									<div class="link-card group relative bg-white rounded-2xl p-6 transition-all duration-300 hover:shadow-[var(--shadow-hover)] hover:-translate-y-2 border border-transparent hover:border-[var(--color-surface-container-low)] flex flex-col h-full overflow-hidden z-10">

                                        <!-- Decorative Background Shape -->
                                        <div class="absolute -right-8 -top-8 w-32 h-32 bg-[var(--color-surface-container-low)] rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500 ease-in-out -z-10"></div>

                                        <!-- Header Card: Icon + Title -->
                                        <div class="flex items-start gap-4 mb-4 relative z-10">
                                            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-[var(--color-surface)] text-[var(--color-primary)] flex items-center justify-center shadow-sm group-hover:bg-[var(--color-primary)] group-hover:text-white transition-colors duration-300">
                                                <?php echo $icon_svg; ?>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-bold font-display text-[var(--color-on-surface)] group-hover:text-[var(--color-primary)] transition-colors leading-tight">
                                                    <?php the_title(); ?>
                                                </h3>
                                            </div>
                                        </div>

										<?php if ( ! empty( $descrizione ) ) : ?>
											<p class="text-[var(--color-on-surface-muted)] text-sm font-body mb-6 flex-grow relative z-10">
												<?php echo nl2br( esc_html( $descrizione ) ); ?>
											</p>
										<?php else : ?>
											<div class="flex-grow mb-6 relative z-10"></div> <!-- Spacer se non c'è desc -->
										<?php endif; ?>

                                        <!-- Actions Footer -->
										<div class="contact-info mt-auto pt-4 border-t border-[var(--color-surface-container-low)]/50 flex flex-col gap-3 relative z-10">

											<?php if ( ! empty( $telefono ) ) : ?>
												<a href="tel:<?php echo esc_attr( preg_replace('/[^0-9+]/', '', $telefono) ); ?>" class="flex items-center gap-3 p-2 rounded-lg hover:bg-[var(--color-surface)] transition-colors group/tel">
													<div class="w-8 h-8 rounded-full bg-[var(--color-surface-container-low)] flex items-center justify-center text-[var(--color-accent)] group-hover/tel:bg-[var(--color-accent)] group-hover/tel:text-white transition-colors">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                                    </div>
													<span class="text-[var(--color-on-surface-muted)] text-sm font-semibold group-hover/tel:text-[var(--color-primary)] transition-colors"><?php echo esc_html( $telefono ); ?></span>
												</a>
											<?php endif; ?>

											<?php if ( ! empty( $url ) ) : ?>
												<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer" class="flex items-center gap-3 p-2 rounded-lg hover:bg-[var(--color-surface)] transition-colors group/link">
													<div class="w-8 h-8 rounded-full bg-[var(--color-surface-container-low)] flex items-center justify-center text-[var(--color-accent)] group-hover/link:bg-[var(--color-accent)] group-hover/link:text-white transition-colors">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                                                    </div>
													<span class="text-[var(--color-accent)] font-semibold text-sm group-hover/link:text-[var(--color-primary)] transition-colors truncate">
														<?php esc_html_e( 'Visita il Sito Web', 'santagatesi' ); ?>
													</span>
												</a>
											<?php endif; ?>

										</div>

									</div>
									<!-- /Nuova Card Link Stilizzata -->

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
