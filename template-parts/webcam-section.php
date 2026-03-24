<?php
/**
 * Template part for displaying the Webcams section
 *
 * @package santagatesi
 */

// Le URL delle webcam live fornite dal contesto.
$webcam_1_url = 'https://www.liveincam.com/?w=588'; // Vico V. Emanuele
$webcam_2_url = 'https://www.skylinewebcams.com/webcam/italia/puglia/foggia/santagata-di-puglia.html?w=453'; // Piazza Toni Santagata
?>

<section id="webcam" class="section">
	<div class="container mx-auto px-4 md:px-0">
		<header class="section-header text-center mb-12">
			<h2 class="text-4xl md:text-5xl font-bold mb-4 text-[var(--color-primary)] font-display">Webcam H24</h2>
			<p class="text-lg text-[var(--color-on-surface-muted)] font-body max-w-2xl mx-auto">Guarda Sant'Agata di Puglia in diretta streaming, ovunque ti trovi nel mondo.</p>
		</header>

		<!-- Griglia ottimizzata per mobile: 1 colonna (impilata), su tablet/desktop 2 colonne -->
		<div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

			<!-- Webcam 1 -->
			<div class="tonal-panel flex flex-col items-center bg-[var(--color-surface-container-lowest)] p-4 sm:p-6 rounded-2xl shadow-lg border border-[var(--color-surface-container-low)] relative w-full h-full">
				<h3 class="text-2xl font-bold mb-4 text-[var(--color-primary)] font-display text-center w-full pb-3 border-b border-[var(--color-surface-container-low)]">Vico V. Emanuele</h3>

				<!-- Container 16:9 responsivo per iframe -->
				<div class="webcam-container relative w-full pt-[56.25%] bg-[var(--color-surface-container-low)] rounded-xl overflow-hidden shadow-inner">
                    <!-- Placeholder per consenso GDPR -->
                    <div class="gdpr-placeholder absolute inset-0 flex flex-col items-center justify-center p-6 text-center z-10 bg-[var(--color-surface-container-lowest)]">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-[var(--color-on-surface-muted)] mb-4"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                        <p class="text-[var(--color-on-surface-muted)] font-body text-sm mb-4">Per visualizzare la webcam è necessario accettare i cookie di terze parti.</p>
                        <button class="accept-cookies-btn btn btn-primary text-sm py-2 px-6 shadow-md transition-transform hover:-translate-y-1">Accetta e visualizza</button>
                    </div>
					<!-- L'iframe viene caricato post-consenso via data-src in JS -->
                    <iframe data-src="<?php echo esc_url( $webcam_1_url ); ?>" allowfullscreen title="Webcam Vico V. Emanuele" class="gdpr-iframe absolute top-0 left-0 w-full h-full border-0 rounded-lg"></iframe>
				</div>
			</div>

			<!-- Webcam 2 -->
			<div class="tonal-panel flex flex-col items-center bg-[var(--color-surface-container-lowest)] p-4 sm:p-6 rounded-2xl shadow-lg border border-[var(--color-surface-container-low)] relative w-full h-full">
				<h3 class="text-2xl font-bold mb-4 text-[var(--color-primary)] font-display text-center w-full pb-3 border-b border-[var(--color-surface-container-low)]">Piazza Toni Santagata</h3>

				<!-- Container 16:9 responsivo per iframe -->
				<div class="webcam-container relative w-full pt-[56.25%] bg-[var(--color-surface-container-low)] rounded-xl overflow-hidden shadow-inner">
                    <!-- Placeholder per consenso GDPR -->
                    <div class="gdpr-placeholder absolute inset-0 flex flex-col items-center justify-center p-6 text-center z-10 bg-[var(--color-surface-container-lowest)]">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-[var(--color-on-surface-muted)] mb-4"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                        <p class="text-[var(--color-on-surface-muted)] font-body text-sm mb-4">Per visualizzare la webcam è necessario accettare i cookie di terze parti.</p>
                        <button class="accept-cookies-btn btn btn-primary text-sm py-2 px-6 shadow-md transition-transform hover:-translate-y-1">Accetta e visualizza</button>
                    </div>
					<!-- L'iframe viene caricato post-consenso via data-src in JS -->
                    <iframe data-src="<?php echo esc_url( $webcam_2_url ); ?>" allowfullscreen title="Webcam Piazza Toni Santagata" class="gdpr-iframe absolute top-0 left-0 w-full h-full border-0 rounded-lg"></iframe>
				</div>
			</div>

		</div>
	</div>
</section>
