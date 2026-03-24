<?php
/**
 * Template part for displaying the Donation CTA section
 *
 * @package santagatesi
 */
?>

<section id="sostieni" class="section bg-[var(--color-surface)]">
	<div class="container">
		<div class="donation-cta tonal-panel max-w-5xl mx-auto text-center py-16 px-6 lg:px-12 bg-white rounded-[var(--radius-xl)] shadow-[var(--shadow-hover)] border-4 border-[var(--color-surface-container-low)] relative overflow-hidden">

            <!-- Decorazione Sfondo -->
            <div class="absolute top-0 right-0 p-8 opacity-5 text-[var(--color-primary)]">
                <svg width="200" height="200" viewBox="0 0 24 24" fill="currentColor"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
            </div>

			<div class="donation-icon mb-6 flex justify-center text-red-500">
				<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="currentColor">
					<path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
				</svg>
			</div>

			<h2 class="text-4xl md:text-5xl font-bold mb-6 text-[var(--color-primary)] font-display relative z-10">Sostieni il nostro portale</h2>
			<p class="text-xl text-[var(--color-on-surface-muted)] mb-10 max-w-3xl mx-auto font-body leading-relaxed relative z-10">
				Sostieni il nostro portale e le webcam. Il tuo contributo ci permette di mantenere vivo questo ponte tra Sant'Agata e i compaesani sparsi per il mondo.
			</p>

            <div class="bank-details bg-[var(--color-surface-container-low)] p-8 rounded-2xl max-w-2xl mx-auto text-left relative z-10 border border-[var(--color-surface)]">
                <div class="mb-4">
                    <span class="block text-sm font-semibold uppercase tracking-wider text-[var(--color-accent)] mb-1">Intestato a</span>
                    <strong class="text-lg text-[var(--color-primary)] font-display">Associazione di promozione sociale SANTAGATESI NEL MONDO</strong><br>
                    <span class="text-[var(--color-on-surface-muted)] text-sm font-body">Via G. Garibaldi, 44 - 71028 Sant'Agata di Puglia</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 pt-6 border-t border-[var(--color-surface)]">
                    <div>
                        <span class="block text-sm font-semibold uppercase tracking-wider text-[var(--color-accent)] mb-1">Conto Corrente Postale</span>
                        <strong class="text-xl font-mono text-[var(--color-primary)] bg-white px-3 py-1 rounded shadow-sm inline-block">001023412800</strong>
                    </div>
                    <div>
                        <span class="block text-sm font-semibold uppercase tracking-wider text-[var(--color-accent)] mb-1">IBAN</span>
                        <strong class="text-sm sm:text-base font-mono text-[var(--color-primary)] bg-white px-3 py-1 rounded shadow-sm inline-block tracking-tight break-all">IT08A0760115700001023412800</strong>
                    </div>
                </div>
            </div>

            <p class="mt-8 text-sm text-[var(--color-on-surface-muted)] italic relative z-10">Grazie di cuore per il tuo prezioso supporto alla nostra Associazione.</p>

		</div>
	</div>
</section>
