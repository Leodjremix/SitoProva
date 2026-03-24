<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package santagatesi
 */

?>
	<footer id="colophon" class="site-footer bg-[var(--color-surface-container-low)] pt-16 pb-8 border-none mt-12 relative z-10">
		<div class="container">
			<div class="footer-content flex flex-col items-center gap-8">

                <!-- Logo / Brand -->
                <div class="footer-brand text-center">
                    <h2 class="text-2xl font-extrabold tracking-tight text-[var(--color-primary)] font-display mb-2">
                        <?php bloginfo( 'name' ); ?>
                    </h2>
                    <p class="text-[var(--color-on-surface-muted)] font-body max-w-md text-center">
                        Un ponte tra le nostre radici e il futuro, ovunque tu sia.
                    </p>
                </div>

                <!-- Contact & Legal Info -->
                <div class="footer-legal text-center max-w-3xl mx-auto font-body text-sm text-[var(--color-on-surface-muted)] leading-relaxed bg-white p-6 rounded-2xl shadow-sm border border-[var(--color-surface)]">
                    <p class="mb-3 font-medium text-[var(--color-primary)]">Periodico di Video Informazione on-line. Direttore Responsabile: Samantha Berardino. Reg.Trib. di Foggia n.20 del 20 settembre 2006.</p>
                    <p class="mb-4 italic">La collaborazione a qualsiasi titolo deve intendersi gratuita.</p>
                    <div class="flex flex-col sm:flex-row justify-center items-center gap-2 sm:gap-6 pt-4 border-t border-[var(--color-surface)]">
                        <span class="flex items-center gap-2"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg> <a href="mailto:redazione@santagatesinelmondo.it" class="hover:text-[var(--color-primary)] transition-colors">redazione@santagatesinelmondo.it</a></span>
                        <span class="flex items-center gap-2"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg> +39 328 4595122 &bull; +39 345 9555117</span>
                    </div>
                </div>

                <!-- Social Links -->
				<div class="footer-socials flex gap-6">
					<a href="https://www.facebook.com/groups/artemisiumwebtv/" target="_blank" rel="noopener noreferrer" aria-label="Facebook Group" class="text-[var(--color-on-surface-muted)] hover:text-[#1877F2] transition-colors duration-300 transform hover:scale-110">
						<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
							<path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/>
						</svg>
					</a>
					<a href="https://www.youtube.com/user/nardino1000" target="_blank" rel="noopener noreferrer" aria-label="YouTube Channel" class="text-[var(--color-on-surface-muted)] hover:text-[#FF0000] transition-colors duration-300 transform hover:scale-110">
						<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
							<path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
						</svg>
					</a>
				</div>

                <!-- Copyright & Bottom Links -->
				<div class="footer-bottom flex flex-col items-center justify-center font-body text-xs text-[var(--color-on-surface-muted)] border-t border-[var(--color-surface)] w-full pt-8 pb-4">
					<p class="mb-2">&copy; <?php echo date( 'Y' ); ?> Santagatesi nel Mondo. Tutti i diritti riservati.</p>
					<p class="mb-4"><small class="opacity-80">Associazione di Promozione Sociale - Via G. Garibaldi 44, 71028 Sant'Agata di Puglia (FG)</small></p>
                    <div class="footer-policy-links flex gap-4 text-[var(--color-primary)] font-medium">
                        <a href="<?php echo esc_url( home_url( '/privacy-policy' ) ); ?>" class="hover:underline">Privacy Policy</a>
                        <span class="opacity-30">|</span>
                        <a href="<?php echo esc_url( home_url( '/cookie-policy' ) ); ?>" class="hover:underline">Cookie Policy</a>
                    </div>
				</div>
			</div>
		</div><!-- .container -->
	</footer><!-- #colophon -->

    <!-- Custom GDPR Cookie Banner -->
    <div id="gdpr-cookie-banner" class="fixed bottom-0 left-0 right-0 bg-white border-t border-[var(--color-surface-container-low)] shadow-[0_-10px_40px_rgba(0,0,0,0.1)] p-6 z-[100] transform translate-y-full transition-transform duration-500 font-body">
        <div class="container mx-auto max-w-5xl flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="cookie-text flex-1">
                <h4 class="text-xl font-bold text-[var(--color-primary)] font-display mb-2">Informativa sui Cookie</h4>
                <p class="text-[var(--color-on-surface-muted)] text-sm">
                    Utilizziamo i cookie per migliorare la tua esperienza di navigazione e per integrare contenuti di terze parti come webcam e video di YouTube. Cliccando su "Accetta Tutti", acconsenti all'uso dei cookie. Puoi leggere la nostra <a href="<?php echo esc_url( home_url( '/cookie-policy' ) ); ?>" class="text-[var(--color-accent)] hover:underline">Cookie Policy</a> per maggiori dettagli.
                </p>
            </div>
            <div class="cookie-actions flex flex-shrink-0 gap-4">
                <button id="btn-reject-cookies" class="btn btn-secondary text-sm px-6 py-2 border border-[var(--color-surface-container-low)] bg-[var(--color-surface)]">Rifiuta</button>
                <button id="btn-accept-cookies" class="btn btn-primary text-sm px-6 py-2">Accetta Tutti</button>
            </div>
        </div>
    </div>

</div><!-- #page -->

<script>
document.addEventListener("DOMContentLoaded", function() {
    // -----------------------------------------------------
    // Vanilla JS GDPR Cookie Management
    // -----------------------------------------------------
    const cookieBanner = document.getElementById('gdpr-cookie-banner');
    const acceptBtn = document.getElementById('btn-accept-cookies');
    const rejectBtn = document.getElementById('btn-reject-cookies');
    const cookieName = 'santagatesi_cookie_consent';

    // Check if user already consented
    const hasConsented = localStorage.getItem(cookieName);

    if (hasConsented === 'accepted') {
        enableThirdPartyIframes();
    } else if (hasConsented !== 'rejected') {
        // Show banner if no choice has been made
        setTimeout(() => {
            cookieBanner.classList.remove('translate-y-full');
        }, 500);
    }

    // Accept Cookies
    acceptBtn.addEventListener('click', function() {
        localStorage.setItem(cookieName, 'accepted');
        cookieBanner.classList.add('translate-y-full');
        enableThirdPartyIframes();
    });

    // Reject Cookies
    rejectBtn.addEventListener('click', function() {
        localStorage.setItem(cookieName, 'rejected');
        cookieBanner.classList.add('translate-y-full');
        // Iframes remain blocked
    });

    // Function to load blocked iframes (Webcams/YouTube)
    function enableThirdPartyIframes() {
        const gdprIframes = document.querySelectorAll('.gdpr-iframe');
        const gdprPlaceholders = document.querySelectorAll('.gdpr-placeholder');

        // Hide placeholders
        gdprPlaceholders.forEach(el => el.style.display = 'none');

        // Load iframes by swapping data-src to src
        gdprIframes.forEach(iframe => {
            const src = iframe.getAttribute('data-src');
            if (src) {
                iframe.setAttribute('src', src);
            }
        });
    }

    // Logic for the specific placeholder accept buttons
    const placeholderAcceptBtns = document.querySelectorAll('.accept-cookies-btn');
    placeholderAcceptBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            localStorage.setItem(cookieName, 'accepted');
            cookieBanner.classList.add('translate-y-full');
            enableThirdPartyIframes();
        });
    });
});
</script>

<?php wp_footer(); ?>

</body>
</html>
