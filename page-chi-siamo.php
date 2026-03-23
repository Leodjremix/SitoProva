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

<main id="primary" class="site-main">

	<!-- Hero Section -->
	<section class="hero-section" style="<?php echo esc_attr( $inline_style ); ?>">
		<div class="container relative z-10 py-24">
			<div class="glass-panel mx-auto max-w-4xl text-center">
				<h1 class="text-4xl md:text-5xl font-bold mb-6">Chi Siamo</h1>
				<p class="text-xl text-color-muted">Associazione di Promozione Sociale - Un ponte vitale tra la nostra amata Sant'Agata di Puglia e i Santagatesi sparsi per il mondo.</p>
			</div>
		</div>
	</section>

	<!-- Striped Layout Content Section (Dark bg) -->
	<section class="section bg-opacity-50">
		<div class="container">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-16">
						<div class="entry-content prose prose-invert max-w-none text-lg leading-relaxed text-color-muted">
							<?php
							if ( empty( get_the_content() ) ) {
								echo '<p>L\'Associazione "Santagatesi nel Mondo" nasce dalla volontà di mantenere vive le radici, le tradizioni e il legame indissolubile con il nostro paese d\'origine. Attraverso questo portale, la web TV "Artemisium" e le innumerevoli iniziative culturali, ci impegniamo ogni giorno a superare le distanze geografiche.</p>';
								echo '<p>Il nostro obiettivo è valorizzare il patrimonio storico, artistico e umano di Sant\'Agata di Puglia, offrendo un punto di ritrovo virtuale e reale per tutti i compaesani emigrati.</p>';
							} else {
								the_content();
							}
							?>
						</div>

						<div class="glass-panel text-center p-8">
							<h3 class="text-2xl font-bold mb-4 text-accent">Il nostro impegno</h3>
							<ul class="text-left space-y-4 text-color-muted">
								<li class="flex items-center gap-3">
									<svg class="text-accent flex-shrink-0" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
									Promozione Culturale
								</li>
								<li class="flex items-center gap-3">
									<svg class="text-accent flex-shrink-0" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
									Artemisium Web TV On Air
								</li>
								<li class="flex items-center gap-3">
									<svg class="text-accent flex-shrink-0" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
									Iniziative di Solidarietà
								</li>
								<li class="flex items-center gap-3">
									<svg class="text-accent flex-shrink-0" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
									Mantenimento Tradizioni Locali
								</li>
							</ul>
						</div>
					</div>

					<!-- Team/Staff Grid -->
					<div class="mt-20">
						<header class="text-center mb-12">
							<h2 class="text-3xl font-bold">La Redazione</h2>
							<p class="text-color-muted mt-2">Le persone dietro il progetto Artemisium.</p>
						</header>

						<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
							<!-- Placeholder Member Card -->
							<div class="glass-panel text-center">
								<div class="w-24 h-24 mx-auto rounded-full bg-gray-700 mb-4 overflow-hidden border-2 border-accent">
									<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/avatar-1.jpg" alt="Team Member" class="w-full h-full object-cover" onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'100\' height=\'100\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%23cbd5e1\' stroke-width=\'1\' stroke-linecap=\'round\' stroke-linejoin=\'round\'><path d=\'M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2\'></path><circle cx=\'12\' cy=\'7\' r=\'4\'></circle></svg>';">
								</div>
								<h3 class="text-xl font-bold">Samantha Berardino</h3>
								<p class="text-color-muted text-sm mt-1">Direttore Responsabile</p>
							</div>

							<div class="glass-panel text-center">
								<div class="w-24 h-24 mx-auto rounded-full bg-gray-700 mb-4 overflow-hidden border-2 border-accent">
									<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/avatar-2.jpg" alt="Team Member" class="w-full h-full object-cover" onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'100\' height=\'100\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%23cbd5e1\' stroke-width=\'1\' stroke-linecap=\'round\' stroke-linejoin=\'round\'><path d=\'M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2\'></path><circle cx=\'12\' cy=\'7\' r=\'4\'></circle></svg>';">
								</div>
								<h3 class="text-xl font-bold">Redazione</h3>
								<p class="text-color-muted text-sm mt-1">Staff Artemisium</p>
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
