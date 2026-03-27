<?php
/**
 * Template Name: Personaggi Illustri
 *
 * Questo template di pagina recupera automaticamente tutti i Personaggi Illustri
 * (CPT 'personaggi_illustri') che hanno la visibilità attiva e li impagina a griglia.
 *
 * @package santagatesi
 */

get_header();

// Intestazione Dinamica
$hero_bg_fallback = get_stylesheet_directory_uri() . '/images/illustri-bg.jpg';
$hero_bg_dynamic  = get_option( 'santagatesi_img_storia', $hero_bg_fallback ); // Reuse the "Storia" image for the Illustri archive
if ( empty( $hero_bg_dynamic ) ) {
    $hero_bg_dynamic = $hero_bg_fallback;
}
$inline_style = "background-image: url('" . esc_url( $hero_bg_dynamic ) . "');";
?>

<main id="primary" class="site-main bg-[var(--color-surface)] min-h-screen pb-24">

	<!-- Hero Section dell'Archivio -->
	<section class="hero-section relative h-[40vh] min-h-[400px] flex items-center justify-center bg-cover bg-center" style="<?php echo esc_attr( $inline_style ); ?>">
        <div class="absolute inset-0 bg-black/50 z-0"></div> <!-- Overlay scuro per leggibilità -->
		<div class="container relative z-10 text-center">
			<div class="tonal-panel mx-auto max-w-4xl bg-[var(--color-surface-container-lowest)]/95 backdrop-blur-md p-8 rounded-2xl shadow-[var(--shadow-ambient)]">
				<h1 class="text-4xl md:text-5xl font-bold mb-4 text-[var(--color-primary)] font-display">Santagatesi Illustri</h1>
				<p class="text-lg text-[var(--color-on-surface-muted)] font-body max-w-2xl mx-auto">Donne e uomini di talento che hanno portato alto il nome di Sant'Agata di Puglia nel mondo, distinguendosi per le loro opere e il loro ingegno.</p>
			</div>
		</div>
	</section>

	<!-- Griglia Automatica delle Card -->
	<section class="section mt-16">
		<div class="container">

			<?php
			// Query specifica per i personaggi "Visibili" (Toggle ON = 1) o senza meta (Retrocompatibilità)
			$illustri_args = array(
				'post_type'      => array('personaggi_illustri', 'santagatesi_illustri'), // Accept old CPT slug to ensure data shows up if user hasn't recreated them yet
				'posts_per_page' => -1, // Mostra tutti
				'post_status'    => 'publish',
				'orderby'        => 'title',
				'order'          => 'ASC',
				'meta_query'     => array(
                    'relation' => 'OR',
					array(
						'key'     => '_visibilita_frontend',
						'value'   => '1',
						'compare' => '=', // Mostra se il toggle è attivo
					),
                    array(
                        'key'     => '_visibilita_frontend',
                        'compare' => 'NOT EXISTS' // Retrocompatibilità: Mostra anche se il post è vecchio e non ha ancora il meta _visibilita_frontend salvato
                    ),
				),
			);

			$illustri_query = new WP_Query( $illustri_args );

			if ( $illustri_query->have_posts() ) :
			?>
				<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">

					<?php while ( $illustri_query->have_posts() ) : $illustri_query->the_post(); ?>

						<!-- Inizio singola Card Generata Automaticamente -->
						<article id="post-<?php the_ID(); ?>" <?php post_class( 'illustri-card tonal-panel flex flex-col bg-[var(--color-surface-container-lowest)] rounded-2xl shadow-[var(--shadow-ambient)] border border-[var(--color-surface-container-low)] overflow-hidden transition-all duration-300 hover:-translate-y-2 hover:shadow-[var(--shadow-hover)] group h-full' ); ?>>

							<!-- Fetch Custom Meta Fields -->
                            <?php
                                $foto_url = get_post_meta( get_the_ID(), '_illustri_foto', true );
                                $descrizione = get_post_meta( get_the_ID(), '_illustri_descrizione', true );
                            ?>

                            <!-- Immagine Profilo 4:5 -->
							<div class="illustri-thumbnail relative w-full aspect-[4/5] bg-[var(--color-surface-container-low)] overflow-hidden">
								<?php if ( ! empty( $foto_url ) ) : ?>
									<img src="<?php echo esc_url( $foto_url ); ?>" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="<?php echo esc_attr( get_the_title() ); ?>">
								<?php else : ?>
									<!-- Placeholder se non c'è foto -->
									<div class="absolute inset-0 flex items-center justify-center bg-[var(--color-surface-container-low)] text-[var(--color-on-surface-muted)]">
										<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" opacity="0.3"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
									</div>
								<?php endif; ?>

								<!-- Gradiente decorativo in basso -->
								<div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-[var(--color-primary)]/90 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none flex items-end p-6">
                                    <span class="text-white font-semibold flex items-center gap-2 opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 delay-100">
                                        Vedi Biografia <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                                    </span>
                                </div>
							</div>

							<!-- Contenuto della Scheda -->
							<div class="illustri-content p-6 flex flex-col flex-grow bg-[var(--color-surface-container-lowest)] relative z-10">
								<h3 class="text-xl font-bold font-display text-[var(--color-primary)] mb-3 group-hover:text-[var(--color-accent)] transition-colors line-clamp-2">
									<a href="<?php the_permalink(); ?>" class="focus:outline-none before:absolute before:inset-0">
										<?php the_title(); ?>
									</a>
								</h3>

								<!-- Estratto della descrizione tramite Custom Meta -->
								<div class="text-[var(--color-on-surface-muted)] text-sm font-body line-clamp-4 flex-grow mb-4 leading-relaxed">
									<?php echo wp_trim_words( wp_strip_all_tags( $descrizione ), 25, '...' ); ?>
								</div>

							</div>

						</article>
						<!-- Fine singola Card -->

					<?php endwhile; ?>

				</div>
			<?php
			else :
				// Nessun personaggio trovato o tutti spenti
			?>
				<div class="tonal-panel text-center p-12 bg-[var(--color-surface-container-lowest)] rounded-2xl shadow-sm border border-[var(--color-surface-container-low)]">
					<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto text-[var(--color-on-surface-muted)] mb-4 opacity-50"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
					<h3 class="text-2xl font-bold text-[var(--color-primary)] font-display mb-2">Sezione in Aggiornamento</h3>
					<p class="text-[var(--color-on-surface-muted)] font-body text-lg">Al momento non ci sono schede pubbliche in questa sezione. Torna a trovarci presto!</p>
				</div>
			<?php
			endif;
			wp_reset_postdata();
			?>

		</div>
	</section>

</main>

<?php
get_footer();
