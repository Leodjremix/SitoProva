<?php
/**
 * The template for displaying a single "Personaggio Illustre"
 *
 * Poiché abbiamo rimosso l'editor standard e usiamo Custom Fields,
 * questo file è necessario per stampare correttamente i dati salvati dal backend.
 *
 * @package santagatesi
 */

get_header();
?>

<main id="primary" class="site-main bg-[var(--color-surface)] min-h-screen py-16">

	<div class="container max-w-4xl mx-auto px-4 md:px-0">

		<?php
		while ( have_posts() ) :
			the_post();

            // Recupera i Custom Meta Fields creati nel backend "Foolproof"
            $foto_url    = get_post_meta( get_the_ID(), '_illustri_foto', true );
            $descrizione = get_post_meta( get_the_ID(), '_illustri_descrizione', true );
            $visibile    = get_post_meta( get_the_ID(), '_visibilita_frontend', true );

            // Controllo Sicurezza: Se l'admin ha nascosto la scheda dal toggle, mostriamo un 404 simulato ai visitatori non loggati
            if ( $visibile !== '1' && ! current_user_can( 'administrator' ) ) {
                echo '<div class="tonal-panel text-center p-12 bg-[var(--color-surface-container-lowest)] rounded-2xl shadow-sm border border-[var(--color-surface-container-low)]">';
                echo '<h1 class="text-3xl font-bold text-[var(--color-primary)] mb-4">Profilo non disponibile</h1>';
                echo '<p class="text-[var(--color-on-surface-muted)]">Questo profilo è stato temporaneamente nascosto dagli amministratori.</p>';
                echo '<a href="' . esc_url( home_url('/') ) . 'personaggi-illustri/" class="btn btn-primary mt-6 inline-block">Torna all\'Archivio</a>';
                echo '</div>';
                break;
            }
		?>

			<article id="post-<?php the_ID(); ?>" <?php post_class( 'tonal-panel bg-[var(--color-surface-container-lowest)] rounded-2xl shadow-[var(--shadow-ambient)] overflow-hidden border border-[var(--color-surface-container-low)]' ); ?>>

				<div class="flex flex-col md:flex-row">

					<!-- Colonna Foto Profilo -->
					<div class="w-full md:w-2/5 md:min-h-[500px] bg-[var(--color-surface-container-low)] relative">
                        <?php if ( ! empty( $foto_url ) ) : ?>
                            <img src="<?php echo esc_url( $foto_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="absolute inset-0 w-full h-full object-cover">
                        <?php else : ?>
                            <div class="absolute inset-0 flex items-center justify-center text-[var(--color-on-surface-muted)]">
                                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" opacity="0.3"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            </div>
                        <?php endif; ?>
					</div>

					<!-- Colonna Testo (Biografia) -->
					<div class="w-full md:w-3/5 p-8 md:p-12 flex flex-col">
						<header class="entry-header mb-8 pb-6 border-b border-[var(--color-surface-container-low)]">
                            <span class="text-[var(--color-accent)] font-semibold tracking-wider uppercase text-sm mb-2 block font-body">Santagatesi nel Mondo</span>
							<?php the_title( '<h1 class="text-4xl md:text-5xl font-bold font-display text-[var(--color-primary)] leading-tight">', '</h1>' ); ?>
						</header>

						<div class="entry-content prose prose-lg prose-slate max-w-none text-[var(--color-on-surface)] font-body leading-relaxed flex-grow">
							<?php
                            if ( ! empty( $descrizione ) ) {
                                // Formattiamo il testo preservando i paragrafi (invio a capo dal form)
                                echo wpautop( wp_kses_post( $descrizione ) );
                            } else {
                                echo '<p class="italic text-[var(--color-on-surface-muted)]">Nessuna biografia inserita per questo personaggio.</p>';
                            }
                            ?>
						</div>

                        <div class="mt-12 pt-6 border-t border-[var(--color-surface-container-low)]">
                            <!-- Link indietro generico (potrebbe essere aggiustato all'URL esatto della pagina illustri se noto) -->
                            <a href="javascript:history.back()" class="text-[var(--color-primary)] hover:text-[var(--color-accent)] font-semibold flex items-center gap-2 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                                Torna Indietro
                            </a>
                        </div>
					</div>
				</div>

			</article>

		<?php
		endwhile; // End of the loop.
		?>

	</div>
</main>

<?php
get_footer();
