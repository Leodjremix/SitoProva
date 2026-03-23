<?php
/**
 * Template part for displaying the hero section
 *
 * @package santagatesi
 */

// Placeholder background image, can be changed via Customizer or ACF later.
$inline_style = "background-image: url('https://www.santagatesinelmondo.it/public/banner/cripta.jpg');"; // Placeholder from context
?>

<section class="hero-section" style="<?php echo esc_attr( $inline_style ); ?>">
	<div class="container relative z-10 flex justify-center">
		<div class="hero-content tonal-panel mx-auto text-center w-full max-w-4xl">
			<h1 class="hero-title">Santagatesi nel Mondo</h1>
			<p class="hero-subtitle">Un ponte tra le nostre radici e il futuro, ovunque tu sia.</p>

			<div class="flex flex-wrap justify-center gap-4 mt-8">
				<a href="#news" class="btn btn-primary">Ultime Notizie</a>
				<a href="#webcam" class="btn btn-secondary">Guarda le Webcam</a>
			</div>
		</div>
	</div>
</section>
