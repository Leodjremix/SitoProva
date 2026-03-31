<?php
/**
 * Template Name: Libro dei Saluti
 *
 * @package santagatesi
 */

get_header();

// Hero Data from CMS
$hero_data = get_option('santagatesi_hero_data', array());
$guestbook_hero = isset($hero_data['guestbook']) ? $hero_data['guestbook'] : array();

$hero_title = !empty($guestbook_hero['title']) ? $guestbook_hero['title'] : "Libro dei Saluti";
$hero_subtitle = !empty($guestbook_hero['subtitle']) ? $guestbook_hero['subtitle'] : "Unisciti alla nostra community. Lascia un pensiero, un ricordo o un saluto speciale per i tuoi compaesani e la tua amata Sant'Agata.";
$hero_bg_dynamic  = !empty($guestbook_hero['bg_image']) ? $guestbook_hero['bg_image'] : '';
?>

<main id="primary" class="site-main bg-[var(--color-surface)] min-h-screen pb-24">

	<!-- Header Sezione Guestbook -->
	<section class="relative py-32 mb-16 overflow-hidden bg-gradient-to-br from-[var(--color-surface-container-low)] to-[var(--color-surface-container-lowest)] <?php echo $hero_bg_dynamic ? 'bg-cover bg-center' : ''; ?>" <?php echo $hero_bg_dynamic ? 'style="background-image: url(\'' . esc_url($hero_bg_dynamic) . '\');"' : ''; ?>>

		<?php if ($hero_bg_dynamic) : ?>
            <div class="absolute inset-0 bg-white/80 backdrop-blur-md z-0"></div>
        <?php else : ?>
            <!-- Overlay Decorativo (Luminous Horizon Effect) -->
            <div class="absolute inset-0 bg-white/40 backdrop-blur-sm z-0"></div>
            <!-- Decorative Circles -->
            <div class="absolute -top-20 -left-20 w-72 h-72 bg-[var(--color-surface-container-low)] rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob"></div>
            <div class="absolute top-20 -right-20 w-72 h-72 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-2000"></div>
		<?php endif; ?>

		<div class="container relative z-10 text-center px-4 pt-24">
			<span class="text-[var(--color-accent)] font-semibold tracking-widest uppercase text-sm mb-6 block font-body">Bacheca Globale</span>
			<h1 class="text-5xl md:text-7xl font-extrabold text-[var(--color-primary)] mb-8 font-display drop-shadow-sm">
				<?php echo esc_html( $hero_title ); ?>
			</h1>
			<?php if ( $hero_subtitle ) : ?>
                <p class="text-xl md:text-2xl text-[var(--color-on-surface-muted)] max-w-3xl mx-auto font-body leading-relaxed font-medium">
                    <?php echo esc_html( $hero_subtitle ); ?>
                </p>
            <?php endif; ?>
		</div>
	</section>

	<section class="container max-w-5xl mx-auto px-4">
		<div class="tonal-panel p-8 md:p-14 shadow-[var(--shadow-ambient)] rounded-[var(--radius-xl)] bg-white relative overflow-hidden transition-all duration-500">

			<!-- Decorazione Angolo -->
			<div class="absolute top-0 right-0 p-8 opacity-5 text-[var(--color-primary)]">
				<svg width="140" height="140" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
					<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
				</svg>
			</div>

			<?php
			while ( have_posts() ) :
				the_post();

				// Mostra il contenuto della pagina se presente (es. istruzioni introduttive)
				if ( ! empty( get_the_content() ) ) :
					?>
					<div class="entry-content prose prose-lg max-w-none text-[var(--color-on-surface-muted)] mb-14 border-b border-[var(--color-surface-container-low)] pb-14 relative z-10 font-body leading-loose">
						<?php the_content(); ?>
					</div>
					<?php
				endif;

				// Includi il template dei commenti
				// Se i commenti sono aperti o ci sono almeno commenti
				if ( comments_open() || get_comments_number() ) :
					comments_template( '/comments-guestbook.php' ); // Richiama il file personalizzato
				else:
					echo '<div class="text-center py-12 bg-rose-50 rounded-2xl border border-rose-100 text-rose-600 font-body font-medium shadow-sm">I saluti sono temporaneamente chiusi.</div>';
				endif;

			endwhile; // End of the loop.
			?>

		</div>
	</section>

</main>

<?php get_footer(); ?>