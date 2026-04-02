<?php
/**
 * Template part for displaying the hero section
 *
 * @package santagatesi
 */

// 1. Dati Legacy (Impostazioni "Pannello Vecchio" Statiche)
$hero_data = get_option('santagatesi_hero_data', array());
$home_hero = isset($hero_data['home']) ? $hero_data['home'] : array();

$legacy_title = !empty($home_hero['title']) ? $home_hero['title'] : "Santagatesi nel Mondo";
$legacy_subtitle = !empty($home_hero['subtitle']) ? $home_hero['subtitle'] : "Un ponte tra le nostre radici e il futuro, ovunque tu sia.";
$legacy_bg_url = !empty($home_hero['bg_image']) ? $home_hero['bg_image'] : 'https://www.santagatesinelmondo.it/public/banner/cripta.jpg';

// 2. Nuova Logica Dinamica: Sovrascrive i Dati Legacy se c'è un banner programmato attivo (Dati/Ore/Default CPT)
if ( function_exists( 'get_dynamic_hero' ) ) {
    $dynamic_banner = get_dynamic_hero();
    // Se la funzione ci restituisce qualcosa con un'immagine valida, la usiamo
    if ( ! empty( $dynamic_banner['image'] ) ) {
        $legacy_title    = ! empty( $dynamic_banner['title'] ) ? $dynamic_banner['title'] : $legacy_title;
        $legacy_subtitle = ! empty( $dynamic_banner['subtitle'] ) ? $dynamic_banner['subtitle'] : $legacy_subtitle;
        $legacy_bg_url   = $dynamic_banner['image'];
    }
}

$inline_style = "background-image: url('" . esc_url($legacy_bg_url) . "');";
?>
<section class="hero-section" style="<?php echo esc_attr( $inline_style ); ?>">
	<div class="container relative z-10 flex justify-center">
		<!-- Testo pulito e diretto sull'immagine con animazioni a cascata (no box bianchi) -->
		<div class="hero-content mx-auto text-center w-full max-w-5xl px-4 md:px-8">
			<h1 class="hero-title font-extrabold tracking-tight animate-fade-in-up"><?php echo esc_html( $legacy_title ); ?></h1>
			<p class="hero-subtitle text-lg md:text-2xl leading-relaxed animate-fade-in-up animation-delay-200"><?php echo esc_html( $legacy_subtitle ); ?></p>

			<div class="flex flex-col sm:flex-row justify-center items-center gap-6 mt-10 animate-fade-in-up animation-delay-400">
				<a href="#news" class="btn btn-hero-primary w-full sm:w-auto text-lg px-8 py-4">Ultime Notizie</a>
				<a href="#webcam" class="btn btn-hero-secondary w-full sm:w-auto text-lg px-8 py-4">Guarda le Webcam</a>
			</div>
		</div>
	</div>
</section>
