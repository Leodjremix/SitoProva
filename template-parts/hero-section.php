<?php
/**
 * Template part for displaying the hero section
 *
 * @package santagatesi
 */

// Recupera l'URL dinamicamente dalle opzioni salvate tramite il modulo Admin Frontend
$hero_bg_url = get_option( 'santagatesi_hero_image_url', 'https://www.santagatesinelmondo.it/public/banner/cripta.jpg' );
$inline_style = "background-image: url('" . esc_url($hero_bg_url) . "');";
?>

<section class="hero-section" style="<?php echo esc_attr( $inline_style ); ?>">
	<div class="container relative z-10 flex justify-center">
		<!-- Testo pulito e diretto sull'immagine con animazioni a cascata (no box bianchi) -->
		<div class="hero-content mx-auto text-center w-full max-w-5xl px-4 md:px-8">
			<h1 class="hero-title font-extrabold tracking-tight animate-fade-in-up">Santagatesi nel Mondo</h1>
			<p class="hero-subtitle text-lg md:text-2xl leading-relaxed animate-fade-in-up animation-delay-200">Un ponte tra le nostre radici e il futuro, ovunque tu sia.</p>

			<div class="flex flex-col sm:flex-row justify-center items-center gap-6 mt-10 animate-fade-in-up animation-delay-400">
				<a href="#news" class="btn btn-hero-primary w-full sm:w-auto text-lg px-8 py-4">Ultime Notizie</a>
				<a href="#webcam" class="btn btn-hero-secondary w-full sm:w-auto text-lg px-8 py-4">Guarda le Webcam</a>
			</div>
		</div>
	</div>
</section>
