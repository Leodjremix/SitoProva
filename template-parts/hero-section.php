<?php
/**
 * Template part for displaying the hero section
 *
 * @package santagatesi
 */

// Placeholder background image, can be changed via Customizer or ACF later.
// Using a dynamic path assuming an 'images' folder might exist, fallback to a placeholder color if not.
// Based on the context, there's a background image of Sant'Agata.
// We will use inline style for parallax background.
$bg_image = get_stylesheet_directory_uri() . '/images/hero-bg.jpg';
// Let's use a safe fallback since we don't have the image file yet.
$inline_style = "background-image: url('https://www.santagatesinelmondo.it/public/banner/cripta.jpg');"; // Placeholder from context
?>

<section class="hero-section" style="<?php echo esc_attr( $inline_style ); ?>">
	<div class="container relative z-10">
		<div class="hero-content glass-panel mx-auto">
			<h1 class="hero-title">Santagatesi nel Mondo</h1>
			<p class="hero-subtitle">Un ponte tra le nostre radici e il futuro, ovunque tu sia.</p>

			<div class="flex flex-wrap justify-center gap-4">
				<a href="#news" class="btn btn-primary">Ultime Notizie</a>
				<a href="#webcam" class="btn btn-glass">Guarda le Webcam</a>
			</div>
		</div>
	</div>
</section>
