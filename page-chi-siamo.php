<?php
/**
 * Template Name: Chi Siamo
 *
 * @package santagatesi
 */

get_header();

// Setup a fallback image using the requested dynamic path method
$hero_bg = get_stylesheet_directory_uri() . '/images/chi-siamo-bg.jpg';
$inline_style = "background-image: url('https://www.santagatesinelmondo.it/public/header/fratelli%20emigranti.jpg');"; // Placeholder from context if dynamic image is missing
?>

<main id="primary" class="site-main bg-[var(--color-surface)]">

	<!-- Hero Section -->
	<section class="hero-section" style="<?php echo esc_attr( $inline_style ); ?>">
		<div class="container relative z-10 py-24 flex justify-center">
			<div class="tonal-panel mx-auto max-w-4xl text-center bg-white/90 backdrop-blur-md">
				<h1 class="text-4xl md:text-6xl font-bold mb-6 text-[var(--color-primary)] font-display">Chi Siamo</h1>
				<p class="text-xl text-[var(--color-on-surface-muted)] font-body leading-relaxed max-w-2xl mx-auto">Associazione di Promozione Sociale - Un ponte vitale tra la nostra amata Sant'Agata di Puglia e i Santagatesi sparsi per il mondo.</p>
			</div>
		</div>
	</section>

	<!-- Striped Layout Content Section -->
	<section class="section">
		<div class="container">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center mb-24">
						<div class="entry-content prose prose-lg max-w-none text-[var(--color-on-surface-muted)] font-body leading-loose">
							<?php
							if ( empty( get_the_content() ) ) {
								echo '<p class="mb-6">L\'Associazione "Santagatesi nel Mondo" nasce dalla volontà di mantenere vive le radici, le tradizioni e il legame indissolubile con il nostro paese d\'origine. Attraverso questo portale, la web TV "Artemisium" e le innumerevoli iniziative culturali, ci impegniamo ogni giorno a superare le distanze geografiche.</p>';
								echo '<p>Il nostro obiettivo è valorizzare il patrimonio storico, artistico e umano di Sant\'Agata di Puglia, offrendo un punto di ritrovo virtuale e reale per tutti i compaesani emigrati.</p>';
							} else {
								the_content();
							}
							?>
						</div>

						<div class="tonal-panel text-center p-10 bg-[var(--color-surface-container-low)]">
							<h3 class="text-3xl font-bold mb-8 text-[var(--color-primary)] font-display">Il nostro impegno</h3>
							<ul class="text-left space-y-6 text-[var(--color-on-surface-muted)] font-body text-lg">
								<li class="flex items-center gap-4 bg-white p-4 rounded-2xl shadow-sm">
									<svg class="text-[var(--color-accent)] flex-shrink-0" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
									<span class="font-semibold">Promozione Culturale</span>
								</li>
								<li class="flex items-center gap-4 bg-white p-4 rounded-2xl shadow-sm">
									<svg class="text-[var(--color-accent)] flex-shrink-0" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
									<span class="font-semibold">Artemisium Web TV On Air</span>
								</li>
								<li class="flex items-center gap-4 bg-white p-4 rounded-2xl shadow-sm">
									<svg class="text-[var(--color-accent)] flex-shrink-0" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
									<span class="font-semibold">Iniziative di Solidarietà</span>
								</li>
								<li class="flex items-center gap-4 bg-white p-4 rounded-2xl shadow-sm">
									<svg class="text-[var(--color-accent)] flex-shrink-0" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
									<span class="font-semibold">Mantenimento Tradizioni Locali</span>
								</li>
							</ul>
						</div>
					</div>

					<!-- Team/Staff Grid -->
					<div class="mt-20">
						<header class="text-center mb-16">
							<h2 class="text-4xl font-bold text-[var(--color-primary)] font-display">La Redazione</h2>
							<p class="text-[var(--color-on-surface-muted)] mt-4 font-body text-lg">Le persone dietro il progetto Artemisium.</p>
						</header>

						<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
							<!-- Placeholder Member Card -->
							<div class="tonal-panel text-center bg-white transition-transform hover:-translate-y-2">
								<div class="w-32 h-32 mx-auto rounded-full bg-[var(--color-surface-container-low)] mb-6 overflow-hidden shadow-lg border-4 border-white">
									<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/avatar-1.jpg" alt="Team Member" class="w-full h-full object-cover" onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'128\' height=\'128\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%23003d6c\' stroke-width=\'1\' stroke-linecap=\'round\' stroke-linejoin=\'round\'><path d=\'M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2\'></path><circle cx=\'12\' cy=\'7\' r=\'4\'></circle></svg>';">
								</div>
								<h3 class="text-2xl font-bold text-[var(--color-primary)] font-display">Samantha Berardino</h3>
								<p class="text-[var(--color-accent)] font-semibold text-sm mt-2 uppercase tracking-wider font-body">Direttore Responsabile</p>
							</div>

							<div class="tonal-panel text-center bg-white transition-transform hover:-translate-y-2">
								<div class="w-32 h-32 mx-auto rounded-full bg-[var(--color-surface-container-low)] mb-6 overflow-hidden shadow-lg border-4 border-white">
									<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/avatar-2.jpg" alt="Team Member" class="w-full h-full object-cover" onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'128\' height=\'128\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%23003d6c\' stroke-width=\'1\' stroke-linecap=\'round\' stroke-linejoin=\'round\'><path d=\'M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2\'></path><circle cx=\'12\' cy=\'7\' r=\'4\'></circle></svg>';">
								</div>
								<h3 class="text-2xl font-bold text-[var(--color-primary)] font-display">Redazione</h3>
								<p class="text-[var(--color-accent)] font-semibold text-sm mt-2 uppercase tracking-wider font-body">Staff Artemisium</p>
							</div>
						</div>
					</div>

				</article>
			<?php endwhile; ?>
		</div>
	</section>

</main>

<?php
get_footer();
