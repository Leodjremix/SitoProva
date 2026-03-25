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
		<!-- Aggiunto un leggero background opaco (backdrop) per garantire il contrasto del testo sull'immagine -->
		<div class="hero-content bg-[var(--color-surface-container-lowest)]/80 backdrop-blur-md rounded-[var(--radius-xl)] mx-auto text-center w-full max-w-4xl shadow-2xl p-8 md:p-12">
			<h1 class="hero-title font-extrabold tracking-tight">Santagatesi nel Mondo</h1>
			<p class="hero-subtitle text-lg md:text-xl leading-relaxed">Un ponte tra le nostre radici e il futuro, ovunque tu sia.</p>

			<div class="flex flex-col sm:flex-row justify-center items-center gap-4 mt-8">
				<a href="#news" class="btn btn-primary w-full sm:w-auto">Ultime Notizie</a>
				<a href="#webcam" class="btn btn-secondary w-full sm:w-auto">Guarda le Webcam</a>
			</div>
		</div>
	</div>
</section>
