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
	<footer id="colophon" class="site-footer bg-[var(--color-surface-container-low)] pt-20 pb-8 border-none mt-12 relative z-10">
		<div class="container mx-auto px-4">

            <!-- Fat Footer - Grid Layout 4 Colonne -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16 border-b border-[var(--color-surface)] pb-12">

                <!-- Colonna 1: Logo & Info Principali -->
                <div class="footer-widget">
                    <h2 class="text-2xl font-extrabold tracking-tight text-[var(--color-primary)] font-display mb-4">
                        <?php bloginfo( 'name' ); ?>
                    </h2>
                    <p class="text-[var(--color-on-surface-muted)] font-body text-sm mb-6 leading-relaxed">
                        Un ponte tra le nostre radici e il futuro, ovunque tu sia. Associazione di Promozione Sociale fondata per unire i Santagatesi sparsi per il mondo.
                    </p>
                    <div class="footer-socials flex gap-4">
                        <a href="https://www.facebook.com/groups/artemisiumwebtv/" target="_blank" rel="noopener noreferrer" aria-label="Facebook Group" class="text-[var(--color-primary)] bg-white p-2 rounded-full shadow-sm hover:text-[#1877F2] transition-colors duration-300 transform hover:scale-110">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/></svg>
                        </a>
                        <a href="https://www.youtube.com/user/nardino1000" target="_blank" rel="noopener noreferrer" aria-label="YouTube Channel" class="text-[var(--color-primary)] bg-white p-2 rounded-full shadow-sm hover:text-[#FF0000] transition-colors duration-300 transform hover:scale-110">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Colonna 2: Contatti Rapidi -->
                <div class="footer-widget">
                    <h3 class="text-xl font-bold text-[var(--color-primary)] font-display mb-6">Contatti</h3>
                    <ul class="space-y-4 text-[var(--color-on-surface-muted)] text-sm font-body">
                        <li class="flex items-start gap-3">
                            <svg class="mt-1 flex-shrink-0 text-[var(--color-accent)]" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            <span>Via G. Garibaldi, 44<br>71028 Sant'Agata di Puglia (FG)</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="mt-1 flex-shrink-0 text-[var(--color-accent)]" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                            <div>
                                <a href="tel:+393284595122" class="block hover:text-[var(--color-primary)] transition">+39 328 4595122</a>
                                <a href="tel:+393459555117" class="block hover:text-[var(--color-primary)] transition">+39 345 9555117</a>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="mt-1 flex-shrink-0 text-[var(--color-accent)]" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                            <a href="mailto:redazione@santagatesinelmondo.it" class="hover:text-[var(--color-primary)] transition break-all">redazione@santagatesinelmondo.it</a>
                        </li>
                    </ul>
                </div>

                <!-- Colonna 3: Link Rapidi (Legal & Pages) -->
                <div class="footer-widget">
                    <h3 class="text-xl font-bold text-[var(--color-primary)] font-display mb-6">Link Rapidi</h3>
                    <ul class="space-y-3 text-[var(--color-on-surface-muted)] text-sm font-body font-medium">
                        <li><a href="<?php echo esc_url( home_url( '/chi-siamo' ) ); ?>" class="hover:text-[var(--color-accent)] transition flex items-center gap-2"><span class="text-[var(--color-accent)] opacity-50">&rsaquo;</span> Chi Siamo</a></li>
                        <li><a href="<?php echo esc_url( home_url( '/archivio-storico' ) ); ?>" class="hover:text-[var(--color-accent)] transition flex items-center gap-2"><span class="text-[var(--color-accent)] opacity-50">&rsaquo;</span> Archivio Storico</a></li>
                        <li><a href="<?php echo esc_url( home_url( '/foto-video-gallery' ) ); ?>" class="hover:text-[var(--color-accent)] transition flex items-center gap-2"><span class="text-[var(--color-accent)] opacity-50">&rsaquo;</span> Multimedia</a></li>
                        <li class="pt-4 mt-4 border-t border-[var(--color-surface)]">
                            <a href="<?php echo esc_url( home_url( '/privacy-policy' ) ); ?>" class="hover:text-[var(--color-accent)] transition">Privacy Policy</a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url( home_url( '/cookie-policy' ) ); ?>" class="hover:text-[var(--color-accent)] transition">Cookie Policy</a>
                        </li>
                    </ul>
                </div>

                <!-- Colonna 4: Box Donazioni Prominente -->
                <div class="footer-widget bg-white p-6 rounded-2xl shadow-sm border border-[var(--color-surface)] relative overflow-hidden">
                    <!-- Deco bg -->
                    <div class="absolute -right-4 -bottom-4 text-[var(--color-surface-container-low)] opacity-50">
                        <svg width="100" height="100" viewBox="0 0 24 24" fill="currentColor"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    </div>

                    <h3 class="text-xl font-bold text-[var(--color-primary)] font-display mb-3 relative z-10 flex items-center gap-2">
                        <svg class="text-red-500" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                        Sostienici
                    </h3>
                    <p class="text-xs text-[var(--color-on-surface-muted)] mb-4 font-body leading-relaxed relative z-10">
                        Il tuo contributo ci permette di mantenere attive le webcam e il portale.
                    </p>

                    <div class="space-y-3 relative z-10">
                        <div class="bg-[var(--color-surface-container-low)] p-3 rounded-lg">
                            <span class="block text-[10px] uppercase font-bold text-[var(--color-accent)] mb-1">C/C Postale</span>
                            <span class="font-mono text-sm text-[var(--color-primary)] font-bold">001023412800</span>
                        </div>
                        <div class="bg-[var(--color-surface-container-low)] p-3 rounded-lg">
                            <span class="block text-[10px] uppercase font-bold text-[var(--color-accent)] mb-1">IBAN</span>
                            <span class="font-mono text-xs text-[var(--color-primary)] font-bold break-all">IT08A0760115700001023412800</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Footer Bottom: Legal Text & Copyright -->
            <div class="footer-bottom text-center">
                <div class="footer-legal max-w-4xl mx-auto font-body text-xs text-[var(--color-on-surface-muted)] leading-relaxed mb-6 opacity-80">
                    <p class="mb-1"><strong>Periodico di Video Informazione on-line.</strong> Direttore Responsabile: Samantha Berardino. Reg.Trib. di Foggia n.20 del 20 settembre 2006.</p>
                    <p>La collaborazione a qualsiasi titolo deve intendersi gratuita.</p>
                </div>

                <p class="font-body text-sm text-[var(--color-primary)] font-medium">
                    &copy; <?php echo date( 'Y' ); ?> Santagatesi nel Mondo. Tutti i diritti riservati.
                </p>
            </div>

		</div><!-- .container -->
	</footer><!-- #colophon -->

    <!-- Custom GDPR Cookie Banner -->
    <div id="gdpr-cookie-banner" class="fixed bottom-0 left-0 right-0 bg-[var(--color-surface-container-lowest)] border-t border-[var(--color-surface-container-low)] shadow-[0_-10px_40px_rgba(0,0,0,0.2)] p-6 z-[100] transform translate-y-full transition-transform duration-500 font-body">
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

    <!-- YouTube Lightbox Modal -->
    <div id="youtube-lightbox" class="fixed inset-0 z-[9999] bg-black/90 hidden items-center justify-center opacity-0 transition-opacity duration-300">
        <div class="relative w-full max-w-5xl px-4 mx-auto">
            <!-- Close Button -->
            <button id="close-lightbox" class="absolute -top-12 right-4 text-white hover:text-[var(--color-accent)] transition-colors focus:outline-none p-2" aria-label="Chiudi video">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>

            <!-- Video Container (16:9) -->
            <div class="relative w-full pt-[56.25%] bg-black rounded-lg overflow-hidden shadow-2xl">
                <!-- If GDPR consent is missing, show placeholder, else empty iframe ready for src injection -->
                <div id="lightbox-gdpr-placeholder" class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center z-10 bg-[var(--color-surface-container-low)] hidden">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-[var(--color-on-surface-muted)] mb-4"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33 2.78 2.78 0 0 0 1.94 2c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.33 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg>
                    <p class="text-[var(--color-on-surface-muted)] font-body text-lg mb-6 max-w-lg mx-auto">Per riprodurre il video YouTube è necessario accettare i cookie di terze parti.</p>
                    <button class="accept-cookies-btn btn btn-primary bg-[#FF0000] hover:bg-[#CC0000] text-white">Accetta i Cookie di YouTube</button>
                </div>

                <iframe id="lightbox-iframe" class="absolute inset-0 w-full h-full border-0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>
    </div>

</div><!-- #page -->

<script>
document.addEventListener("DOMContentLoaded", function() {
    // -----------------------------------------------------
    // Vanilla JS GDPR Cookie Management & Lightbox
    // -----------------------------------------------------
    const cookieBanner = document.getElementById('gdpr-cookie-banner');
    const acceptBtn = document.getElementById('btn-accept-cookies');
    const rejectBtn = document.getElementById('btn-reject-cookies');
    const cookieName = 'santagatesi_cookie_consent';

    // Lightbox Elements
    const lightbox = document.getElementById('youtube-lightbox');
    const lightboxIframe = document.getElementById('lightbox-iframe');
    const lightboxClose = document.getElementById('close-lightbox');
    const lightboxGdprPlaceholder = document.getElementById('lightbox-gdpr-placeholder');
    let currentVideoId = '';

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
        // If lightbox is open and was blocked, load video now
        if (!lightbox.classList.contains('hidden') && currentVideoId) {
            playLightboxVideo(currentVideoId);
        }
    });

    // Reject Cookies
    rejectBtn.addEventListener('click', function() {
        localStorage.setItem(cookieName, 'rejected');
        cookieBanner.classList.add('translate-y-full');
        // Iframes remain blocked
    });

    // Function to load blocked iframes (Webcams)
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

    // Logic for the specific placeholder accept buttons (webcams & lightbox)
    const placeholderAcceptBtns = document.querySelectorAll('.accept-cookies-btn');
    placeholderAcceptBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            localStorage.setItem(cookieName, 'accepted');
            cookieBanner.classList.add('translate-y-full');
            enableThirdPartyIframes();

            // If inside lightbox, play it immediately
            if (!lightbox.classList.contains('hidden') && currentVideoId) {
                playLightboxVideo(currentVideoId);
            }
        });
    });

    // --- Lightbox Logic ---
    const lightboxTriggers = document.querySelectorAll('.yt-lightbox-trigger');

    function playLightboxVideo(videoId) {
        lightboxGdprPlaceholder.classList.add('hidden');
        lightboxIframe.classList.remove('hidden');
        lightboxIframe.setAttribute('src', 'https://www.youtube-nocookie.com/embed/' + videoId + '?autoplay=1&rel=0');
    }

    function openLightbox(videoId) {
        currentVideoId = videoId;
        lightbox.classList.remove('hidden');
        // Small delay for fade transition
        setTimeout(() => lightbox.classList.remove('opacity-0'), 10);

        // Disable body scroll
        document.body.style.overflow = 'hidden';

        if (localStorage.getItem(cookieName) === 'accepted') {
            playLightboxVideo(videoId);
        } else {
            // Show GDPR placeholder inside lightbox
            lightboxIframe.classList.add('hidden');
            lightboxIframe.setAttribute('src', '');
            lightboxGdprPlaceholder.classList.remove('hidden');
        }
    }

    function closeLightbox() {
        lightbox.classList.add('opacity-0');
        setTimeout(() => {
            lightbox.classList.add('hidden');
            lightboxIframe.setAttribute('src', ''); // Stop video
            document.body.style.overflow = ''; // Restore scroll
            currentVideoId = '';
        }, 300);
    }

    // Bind triggers
    lightboxTriggers.forEach(trigger => {
        trigger.addEventListener('click', function() {
            const videoId = this.getAttribute('data-video-id');
            if (videoId) {
                openLightbox(videoId);
            }
        });
    });

    // Bind close actions
    lightboxClose.addEventListener('click', closeLightbox);

    // Close on background click
    lightbox.addEventListener('click', function(e) {
        if (e.target === lightbox) {
            closeLightbox();
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !lightbox.classList.contains('hidden')) {
            closeLightbox();
        }
    });
});
</script>

<?php wp_footer(); ?>

</body>
</html>
