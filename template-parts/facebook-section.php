<?php
/**
 * Template part for displaying the Facebook CTA section
 *
 * @package santagatesi
 */

$facebook_url = 'https://www.facebook.com/groups/artemisiumwebtv/';
?>

<section id="community" class="section bg-[var(--color-surface)]">
	<div class="container">
		<div class="facebook-cta tonal-panel max-w-4xl mx-auto text-center py-16 bg-[var(--color-surface-container-low)] rounded-[var(--radius-xl)] shadow-[var(--shadow-ambient)] border-none">
			<div class="facebook-icon mb-6 flex justify-center text-[#1877F2]">
				<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="currentColor">
					<path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/>
				</svg>
			</div>

			<h2 class="text-5xl font-bold mb-6 text-[var(--color-primary)] font-display">Unisciti alla nostra Community</h2>
			<p class="text-xl text-[var(--color-on-surface-muted)] mb-10 max-w-2xl mx-auto font-body leading-relaxed">
				Fai parte di una rete globale. Discuti, condividi ricordi e resta in contatto con la community di Santagatesi nel Mondo sul nostro gruppo Facebook.
			</p>

			<a href="<?php echo esc_url( $facebook_url ); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary bg-[#1877F2] hover:bg-[#166fe5] text-white shadow-lg shadow-[#1877F2]/30 transition-transform duration-300 hover:-translate-y-1">
				Partecipa al Gruppo Facebook
			</a>
		</div>
	</div>
</section>
