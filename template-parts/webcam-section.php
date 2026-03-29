<?php
/**
 * Template part for displaying the Webcams section
 *
 * @package santagatesi
 */

// Array dinamico salvato nel database (Admin Dashboard)
$default_webcams = array(
    array('nome' => 'Vico V. Emanuele', 'url' => 'https://www.liveincam.com/?w=588', 'visibile' => '1'),
    array('nome' => 'Piazza Toni Santagata', 'url' => 'https://www.skylinewebcams.com/webcam/italia/puglia/foggia/santagata-di-puglia.html?w=453', 'visibile' => '1')
);
$webcams = get_option( 'santagatesi_webcam_data', $default_webcams );

// Filtra solo le webcam attive
$active_webcams = array_filter( $webcams, function($cam) {
    return ( isset($cam['visibile']) && $cam['visibile'] === '1' && !empty($cam['url']) );
});

// Esci se non ci sono webcam da mostrare
if ( empty( $active_webcams ) ) {
    return;
}

$webcam_count = count( $active_webcams );
// Layout classes: se è una, la facciamo larga, altrimenti griglia
$grid_classes = ($webcam_count === 1) ? 'grid-cols-1 max-w-4xl mx-auto' : 'grid-cols-1 lg:grid-cols-2';
?>

<section id="webcam" class="section">
	<div class="container mx-auto px-4 md:px-0">
		<header class="section-header text-center mb-12">
			<h2 class="text-4xl md:text-5xl font-bold mb-4 text-[var(--color-primary)] font-display">Webcam H24</h2>
			<p class="text-lg text-[var(--color-on-surface-muted)] font-body max-w-2xl mx-auto">Guarda Sant'Agata di Puglia in diretta streaming, ovunque ti trovi nel mondo.</p>
		</header>

		<!-- Griglia Dinamica -->
		<div class="grid <?php echo esc_attr( $grid_classes ); ?> gap-10">

            <?php foreach ( $active_webcams as $cam ) : ?>
                <div class="tonal-panel flex flex-col items-center bg-[var(--color-surface-container-lowest)] p-4 sm:p-6 rounded-2xl shadow-lg border border-[var(--color-surface-container-low)] relative w-full h-full">
                    <h3 class="text-2xl font-bold mb-4 text-[var(--color-primary)] font-display text-center w-full pb-3 border-b border-[var(--color-surface-container-low)]">
                        <?php echo esc_html( $cam['nome'] ); ?>
                    </h3>

                    <!-- Container 16:9 responsivo per iframe -->
                    <div class="webcam-container relative w-full pt-[56.25%] bg-[var(--color-surface-container-low)] rounded-xl overflow-hidden shadow-inner">
                        <!-- Placeholder per consenso GDPR -->
                        <div class="gdpr-placeholder absolute inset-0 flex flex-col items-center justify-center p-6 text-center z-10 <?php echo empty($cam['anteprima']) ? 'bg-[var(--color-surface-container-lowest)]' : 'bg-cover bg-center'; ?>" <?php echo !empty($cam['anteprima']) ? 'style="background-image: url(\'' . esc_url($cam['anteprima']) . '\');"' : ''; ?>>
                            <?php if (!empty($cam['anteprima'])) : ?>
                                <!-- Overlay scuro se c'è immagine di anteprima -->
                                <div class="absolute inset-0 bg-black/60 backdrop-blur-[2px] z-0"></div>
                            <?php endif; ?>

                            <div class="relative z-10 flex flex-col items-center">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="<?php echo !empty($cam['anteprima']) ? 'text-white' : 'text-[var(--color-on-surface-muted)]'; ?> mb-4"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                                <p class="<?php echo !empty($cam['anteprima']) ? 'text-white/90 drop-shadow-md' : 'text-[var(--color-on-surface-muted)]'; ?> font-body text-sm mb-4 max-w-xs leading-relaxed">Per visualizzare la webcam è necessario accettare i cookie di terze parti.</p>
                                <button class="accept-cookies-btn btn btn-primary text-sm py-2 px-6 shadow-md transition-transform hover:-translate-y-1 bg-[var(--color-primary)] text-white border-none">Accetta e visualizza</button>
                            </div>
                        </div>
                        <!-- L'iframe viene caricato post-consenso via data-src in JS -->
                        <iframe data-src="<?php echo esc_url( $cam['url'] ); ?>" allowfullscreen title="Webcam <?php echo esc_attr( $cam['nome'] ); ?>" class="gdpr-iframe absolute top-0 left-0 w-full h-full border-0 rounded-lg"></iframe>
                    </div>
                </div>
            <?php endforeach; ?>

		</div>
	</div>
</section>
