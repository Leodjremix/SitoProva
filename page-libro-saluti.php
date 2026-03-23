<?php
/**
 * Template Name: Libro dei Saluti
 *
 * @package santagatesi
 */

get_header();

// Imposta un'immagine di sfondo statica/dinamica per la testata
$header_bg = get_stylesheet_directory_uri() . '/images/guestbook-bg.jpg';
$inline_style = "background-image: url('https://www.santagatesinelmondo.it/public/header/logointesta.jpg');";
?>

<main id="primary" class="site-main bg-[#0f172a] min-h-screen pb-20">

	<!-- Header Sezione Guestbook -->
	<section class="relative py-32 mb-16 overflow-hidden bg-gradient-to-br from-blue-900 via-slate-900 to-[#0f172a]">

		<!-- Overlay Decorativo (Glassmorphism Effect) -->
		<div class="absolute inset-0 bg-white/5 backdrop-blur-sm z-0"></div>

		<!-- Decorative Circles -->
		<div class="absolute -top-20 -left-20 w-72 h-72 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
		<div class="absolute top-20 -right-20 w-72 h-72 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>

		<div class="container relative z-10 text-center px-4">
			<span class="text-blue-400 font-semibold tracking-widest uppercase text-sm mb-4 block">Bacheca Globale</span>
			<h1 class="text-5xl md:text-7xl font-extrabold text-white mb-6 drop-shadow-lg">
				Libro dei Saluti
			</h1>
			<p class="text-xl md:text-2xl text-blue-100 max-w-3xl mx-auto font-light leading-relaxed">
				Unisciti alla nostra community. Lascia un pensiero, un ricordo o un saluto speciale per i tuoi compaesani e la tua amata Sant'Agata.
			</p>
		</div>
	</section>

	<section class="container max-w-5xl mx-auto px-4">
		<div class="glass-panel p-8 md:p-12 shadow-2xl rounded-3xl border border-white/10 bg-[#1e293b]/80 backdrop-blur-xl relative overflow-hidden">

			<!-- Decorazione Angolo -->
			<div class="absolute top-0 right-0 p-6 opacity-10">
				<svg width="120" height="120" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
					<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
				</svg>
			</div>

			<?php
			while ( have_posts() ) :
				the_post();

				// Mostra il contenuto della pagina se presente (es. istruzioni introduttive)
				if ( ! empty( get_the_content() ) ) :
					?>
					<div class="entry-content prose prose-invert max-w-none text-blue-200 mb-12 border-b border-white/10 pb-12 relative z-10">
						<?php the_content(); ?>
					</div>
					<?php
				endif;

				// Includi il template dei commenti
				// Se i commenti sono aperti o ci sono almeno commenti
				if ( comments_open() || get_comments_number() ) :
					comments_template( '/comments-guestbook.php' ); // Richiama il file personalizzato
				else:
					echo '<div class="text-center py-10 bg-rose-500/10 rounded-xl border border-rose-500/20 text-rose-300">I saluti sono temporaneamente chiusi.</div>';
				endif;

			endwhile; // End of the loop.
			?>

		</div>
	</section>

</main>

<?php get_footer(); ?>