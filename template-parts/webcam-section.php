<?php
/**
 * Template part for displaying the Webcams section
 *
 * @package santagatesi
 */

// The URLs are taken from the current website's iframes.
$webcam_1_url = 'https://www.liveincam.com/?w=588'; // Vico V. Emanuele
$webcam_2_url = 'https://www.skylinewebcams.com/webcam/italia/puglia/foggia/santagata-di-puglia.html?w=453'; // Piazza Toni Santagata
?>

<section id="webcam" class="section">
	<div class="container">
		<header class="section-header text-center mb-12">
			<h2 class="text-4xl font-bold mb-4 text-[var(--color-primary)] font-display">Webcam H24</h2>
			<p class="text-lg text-[var(--color-on-surface-muted)] font-body">Guarda Sant'Agata di Puglia in diretta streaming.</p>
		</header>

		<div class="grid grid-cols-1 md:grid-cols-2 gap-8">

			<!-- Webcam 1 -->
			<div class="tonal-panel text-center bg-white border border-[var(--color-surface-container-low)] relative">
				<h3 class="text-2xl mb-6 text-[var(--color-primary)] font-display">Vico V. Emanuele</h3>
				<div class="webcam-container bg-[var(--color-surface-container-low)] rounded-xl overflow-hidden relative">
                    <!-- GDPR Placeholder -->
                    <div class="gdpr-placeholder absolute inset-0 flex flex-col items-center justify-center p-6 text-center z-10 bg-[var(--color-surface-container-low)]">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-[var(--color-on-surface-muted)] mb-4"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                        <p class="text-[var(--color-on-surface-muted)] font-body text-sm mb-4">Per visualizzare la webcam è necessario accettare i cookie di terze parti.</p>
                        <button class="accept-cookies-btn btn btn-primary text-sm py-2 px-4 bg-[var(--color-accent)] hover:bg-[var(--color-primary)]">Accetta i Cookie</button>
                    </div>
					<!-- GDPR Blocked iframe -->
                    <iframe data-src="<?php echo esc_url( $webcam_1_url ); ?>" allowfullscreen title="Webcam Vico V. Emanuele" class="gdpr-iframe w-full h-full absolute inset-0 border-0"></iframe>
				</div>
			</div>

			<!-- Webcam 2 -->
			<div class="tonal-panel text-center bg-white border border-[var(--color-surface-container-low)] relative">
				<h3 class="text-2xl mb-6 text-[var(--color-primary)] font-display">Piazza Toni Santagata</h3>
				<div class="webcam-container bg-[var(--color-surface-container-low)] rounded-xl overflow-hidden relative">
                    <!-- GDPR Placeholder -->
                    <div class="gdpr-placeholder absolute inset-0 flex flex-col items-center justify-center p-6 text-center z-10 bg-[var(--color-surface-container-low)]">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-[var(--color-on-surface-muted)] mb-4"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                        <p class="text-[var(--color-on-surface-muted)] font-body text-sm mb-4">Per visualizzare la webcam è necessario accettare i cookie di terze parti.</p>
                        <button class="accept-cookies-btn btn btn-primary text-sm py-2 px-4 bg-[var(--color-accent)] hover:bg-[var(--color-primary)]">Accetta i Cookie</button>
                    </div>
					<!-- GDPR Blocked iframe -->
                    <iframe data-src="<?php echo esc_url( $webcam_2_url ); ?>" allowfullscreen title="Webcam Piazza Toni Santagata" class="gdpr-iframe w-full h-full absolute inset-0 border-0"></iframe>
				</div>
			</div>

		</div>
	</div>
</section>
