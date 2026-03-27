<?php
/**
 * Template part for displaying the "Santagatesi Illustri" section
 *
 * @package santagatesi
 */

// Recupera i Personaggi Illustri che hanno la spunta "Visibile sul Frontend" (Meta _visibilita_frontend = 1)
$args = array(
	'post_type'      => array('personaggi_illustri', 'santagatesi_illustri'), // Retrocompatibilità con i vecchi CPT creati prima del refactoring
	'posts_per_page' => -1, // Mostra tutti
	'post_status'    => 'publish',
	'orderby'        => 'title',
	'order'          => 'ASC',
	'meta_query'     => array(
        'relation' => 'OR',
		array(
			'key'     => '_visibilita_frontend',
			'value'   => '1',
			'compare' => '=', // Filtra strettamente solo chi ha valore "1" (attivo)
		),
        array(
            'key'     => '_visibilita_frontend',
            'compare' => 'NOT EXISTS' // Retrocompatibilità: mostra anche se creato prima che il toggle esistesse
        ),
	),
);

$illustri_query = new WP_Query( $args );

// Non mostrare l'intera sezione se non ci sono personaggi visibili (o pubblicati)
if ( $illustri_query->have_posts() ) :
?>

<section id="illustri" class="section bg-[var(--color-surface)] py-20 border-t border-[var(--color-surface-container-low)]">
	<div class="container mx-auto px-4 md:px-0">

		<header class="section-header text-center mb-16">
			<h2 class="text-4xl md:text-5xl font-bold mb-4 text-[var(--color-primary)] font-display">Santagatesi Illustri</h2>
			<p class="text-lg text-[var(--color-on-surface-muted)] font-body max-w-2xl mx-auto">Donne e uomini di talento che hanno portato alto il nome di Sant'Agata di Puglia in tutto il mondo con le loro opere e il loro ingegno.</p>
		</header>

		<!-- Griglia Card -->
		<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">

			<?php while ( $illustri_query->have_posts() ) : $illustri_query->the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class( 'illustri-card tonal-panel flex flex-col bg-[var(--color-surface-container-lowest)] rounded-2xl shadow-lg border border-[var(--color-surface-container-low)] overflow-hidden transition-transform duration-300 hover:-translate-y-2 group' ); ?>>

                    <?php
                        $foto_url = get_post_meta( get_the_ID(), '_illustri_foto', true );
                        $descrizione = get_post_meta( get_the_ID(), '_illustri_descrizione', true );
                    ?>

					<!-- Immagine Profilo -->
					<div class="illustri-thumbnail relative w-full aspect-[4/5] bg-[var(--color-surface-container-low)] overflow-hidden">
						<?php if ( ! empty( $foto_url ) ) : ?>
                            <img src="<?php echo esc_url( $foto_url ); ?>" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" alt="<?php echo esc_attr( get_the_title() ); ?>">
						<?php else : ?>
							<!-- Fallback Icon if no picture -->
							<div class="absolute inset-0 flex items-center justify-center bg-[var(--color-surface-container-low)] text-[var(--color-on-surface-muted)]">
								<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" opacity="0.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
							</div>
						<?php endif; ?>

						<!-- Gradiente sfumato in basso per migliorare la leggibilità del nome se dovesse sovrapporsi in futuro -->
						<div class="absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-[var(--color-primary)]/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
					</div>

					<!-- Contenuto -->
					<div class="illustri-content p-6 flex flex-col flex-grow">
						<h3 class="text-xl font-bold font-display text-[var(--color-primary)] mb-2 group-hover:text-[var(--color-accent)] transition-colors">
							<a href="<?php the_permalink(); ?>" class="focus:outline-none focus:ring-2 focus:ring-[var(--color-accent)] focus:ring-offset-2 rounded-sm">
								<?php the_title(); ?>
							</a>
						</h3>

						<!-- Estratto della biografia (tramite custom meta) -->
						<div class="text-[var(--color-on-surface-muted)] text-sm font-body line-clamp-3 mb-4 flex-grow">
							<?php echo wp_trim_words( wp_strip_all_tags( $descrizione ), 20, '...' ); ?>
						</div>

						<!-- Pulsante "Leggi tutto" -->
						<div class="mt-auto pt-4 border-t border-[var(--color-surface-container-low)]">
							<a href="<?php the_permalink(); ?>" class="text-[var(--color-accent)] font-semibold font-body text-sm hover:underline flex items-center gap-1 transition-colors">
								<?php esc_html_e( 'Leggi la biografia', 'santagatesi' ); ?>
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="transform transition-transform group-hover:translate-x-1"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
							</a>
						</div>
					</div>

				</article>

			<?php endwhile; ?>

		</div>
	</div>
</section>

<?php
endif;
wp_reset_postdata();
?>
