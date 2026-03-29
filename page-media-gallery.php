<?php
/**
 * Template Name: Photo & Video Gallery
 *
 * @package santagatesi
 */

get_header();

// Setup the query for approved media only
$args = array(
	'post_type'      => 'santagatesi_media',
	'posts_per_page' => -1, // Retrieve all for masonry layout
	'meta_query'     => array(
		array(
			'key'   => '_media_approvato',
			'value' => '1',
			'compare' => '=',
		),
	),
	'orderby'        => 'date',
	'order'          => 'DESC',
);
$media_query = new WP_Query( $args );

// Hero Data from CMS
$hero_data = get_option('santagatesi_hero_data', array());
$gallery_hero = isset($hero_data['gallery']) ? $hero_data['gallery'] : array();

$hero_title = !empty($gallery_hero['title']) ? $gallery_hero['title'] : "Galleria Media";
$hero_subtitle = !empty($gallery_hero['subtitle']) ? $gallery_hero['subtitle'] : "Una raccolta di momenti indimenticabili. Sfoglia le foto e i video della nostra amata Sant'Agata.";
$hero_bg_dynamic  = !empty($gallery_hero['bg_image']) ? $gallery_hero['bg_image'] : '';
?>

<main id="primary" class="site-main bg-[var(--color-surface)] min-h-screen pb-24">

	<!-- Modern Hero Section -->
	<section class="relative py-32 mb-16 overflow-hidden bg-gradient-to-br from-[var(--color-surface-container-low)] to-[var(--color-surface-container-lowest)] <?php echo $hero_bg_dynamic ? 'bg-cover bg-center' : ''; ?>" <?php echo $hero_bg_dynamic ? 'style="background-image: url(\'' . esc_url($hero_bg_dynamic) . '\');"' : ''; ?>>

		<?php if ($hero_bg_dynamic) : ?>
            <div class="absolute inset-0 bg-white/80 backdrop-blur-md z-0"></div>
        <?php else : ?>
            <!-- Overlay Decorativo (Luminous Horizon Effect) -->
            <div class="absolute inset-0 bg-white/40 backdrop-blur-sm z-0"></div>
            <!-- Decorative Elements -->
            <div class="absolute -top-20 -left-20 w-72 h-72 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob"></div>
            <div class="absolute top-20 -right-20 w-72 h-72 bg-purple-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-2000"></div>
		<?php endif; ?>

		<div class="container relative z-10 text-center px-4">
			<span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 border border-blue-200 text-[var(--color-accent)] font-semibold tracking-widest uppercase text-xs mb-6 font-body shadow-sm">
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
				Esplora i Ricordi
			</span>
			<h1 class="text-5xl md:text-7xl font-extrabold text-[var(--color-primary)] mb-8 font-display drop-shadow-sm">
				<?php echo esc_html( $hero_title ); ?>
			</h1>
			<?php if ( $hero_subtitle ) : ?>
                <p class="text-xl md:text-2xl text-[var(--color-on-surface-muted)] max-w-3xl mx-auto font-body leading-relaxed">
                    <?php echo esc_html( $hero_subtitle ); ?>
                </p>
            <?php endif; ?>

            <!-- Filters (JS Driven) -->
            <div class="mt-12 flex flex-wrap justify-center gap-4 filter-container">
                <button class="filter-btn active px-6 py-2 rounded-full text-sm font-bold bg-[var(--color-primary)] text-white shadow-md transition-all duration-300 hover:-translate-y-1" data-filter="all">Tutti i Media</button>
                <button class="filter-btn px-6 py-2 rounded-full text-sm font-bold bg-white text-[var(--color-primary)] border border-gray-200 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-[var(--color-primary)]" data-filter="foto">Solo Foto</button>
                <button class="filter-btn px-6 py-2 rounded-full text-sm font-bold bg-white text-[var(--color-primary)] border border-gray-200 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-[var(--color-primary)]" data-filter="video">Solo Video</button>
            </div>
		</div>
	</section>

	<!-- Masonry Gallery Section -->
	<section class="container max-w-7xl mx-auto px-4 relative">

		<?php if ( $media_query->have_posts() ) : ?>
            <!-- CSS Grid Masonry Wrapper -->
			<div class="columns-1 sm:columns-2 lg:columns-3 xl:columns-4 gap-6 space-y-6" id="media-gallery-grid">
				<?php
				while ( $media_query->have_posts() ) :
					$media_query->the_post();

					$tipo      = get_post_meta( get_the_ID(), '_media_tipo', true );
					$url       = get_post_meta( get_the_ID(), '_media_url', true );
					$thumbnail = get_post_meta( get_the_ID(), '_media_thumbnail', true );
                    $title     = get_the_title();

					// If thumbnail is empty and it's a photo, use the photo URL
					if ( empty( $thumbnail ) && $tipo === 'foto' ) {
						$thumbnail = $url;
					}
					?>

                    <!-- Media Item (Break Inside Avoid for Masonry) -->
					<div class="media-item break-inside-avoid relative group overflow-hidden rounded-2xl shadow-sm hover:shadow-xl transition-all duration-500 cursor-pointer border border-[var(--color-surface-container-low)]" data-type="<?php echo esc_attr( $tipo ); ?>" onclick="openLightbox('<?php echo esc_url( $url ); ?>', '<?php echo esc_attr( $tipo ); ?>', '<?php echo esc_js( $title ); ?>')">

                        <div class="relative overflow-hidden w-full aspect-auto bg-gray-100">
                            <?php if ( ! empty( $thumbnail ) ) : ?>
                                <!-- Immagine: Caricata lazy per fluidità, zoom in hover -->
                                <img src="<?php echo esc_url( $thumbnail ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy" class="w-full h-auto object-cover transform transition-transform duration-700 group-hover:scale-105">
                            <?php else : ?>
                                <div class="w-full aspect-[4/3] flex items-center justify-center bg-gray-200 text-gray-400">Nessuna miniatura</div>
                            <?php endif; ?>

                            <!-- Overlay Gradient -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                            <!-- Icona Tipo Media al centro -->
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 transform scale-50 group-hover:scale-100">
                                <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white shadow-lg border border-white/30">
                                    <?php if ( $tipo === 'video' ) : ?>
                                        <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor" class="ml-1"><path d="M5 3l14 9-14 9V3z"/></svg>
                                    <?php else: ?>
                                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/></svg>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Titolo in basso -->
                            <div class="absolute bottom-0 left-0 right-0 p-5 translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                                <h3 class="text-white font-bold text-lg drop-shadow-md font-display line-clamp-2"><?php echo esc_html( $title ); ?></h3>
                            </div>

                            <!-- Badge Tipo in alto a destra (Sempre Visibile) -->
                            <div class="absolute top-4 right-4 bg-black/60 backdrop-blur-md text-white px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider flex items-center gap-1.5 shadow-sm border border-white/10">
                                <?php if ( $tipo === 'video' ) : ?>
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M5 3l14 9-14 9V3z"/></svg> Video
                                <?php else: ?>
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg> Foto
                                <?php endif; ?>
                            </div>
                        </div>

					</div>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>

		<?php else : ?>
			<div class="tonal-panel bg-white text-center py-20 border-2 border-dashed border-[var(--color-surface-container-low)] rounded-3xl">
				<svg class="mx-auto h-20 w-20 text-[var(--color-surface-container-low)] mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
				<h3 class="text-3xl font-bold mb-4 text-[var(--color-primary)] font-display">Nessun media presente</h3>
				<p class="text-[var(--color-on-surface-muted)] mb-8 font-body max-w-lg mx-auto">Non sono state ancora caricate foto o video approvati in galleria. Torna a trovarci presto!</p>
			</div>
		<?php endif; ?>

	</section>

</main>

<!-- Vanilla JS Lightbox Overlay (Nascosto di default) -->
<div id="mediaLightbox" class="fixed inset-0 z-[9999] bg-black/95 backdrop-blur-xl hidden flex-col items-center justify-center opacity-0 transition-opacity duration-300">
    <!-- Header Lightbox -->
    <div class="absolute top-0 inset-x-0 p-6 flex justify-between items-start z-10 pointer-events-none">
        <h4 id="lightboxTitle" class="text-white font-display text-2xl font-bold drop-shadow-md max-w-2xl"></h4>
        <button id="closeLightboxBtn" class="pointer-events-auto bg-white/10 hover:bg-white/20 text-white rounded-full p-3 transition-colors backdrop-blur-md border border-white/20 focus:outline-none focus:ring-2 focus:ring-white/50 group">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:rotate-90 transition-transform duration-300"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
    </div>

    <!-- Contenitore Dinamico Media -->
    <div id="lightboxContent" class="w-full h-full max-w-6xl max-h-[85vh] flex items-center justify-center p-4 lg:p-12 relative scale-95 transition-transform duration-300">
        <!-- Loader inserito dinamicamente qui -->
    </div>
</div>

<script>
    // Vanilla JS per Filtri e Lightbox
    document.addEventListener('DOMContentLoaded', () => {

        // --- FILTRI ---
        const filterBtns = document.querySelectorAll('.filter-btn');
        const mediaItems = document.querySelectorAll('.media-item');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Rimuovi classe active (Stili attivi)
                filterBtns.forEach(b => {
                    b.classList.remove('bg-[var(--color-primary)]', 'text-white');
                    b.classList.add('bg-white', 'text-[var(--color-primary)]');
                });
                // Aggiungi classe active al bottone cliccato
                btn.classList.remove('bg-white', 'text-[var(--color-primary)]');
                btn.classList.add('bg-[var(--color-primary)]', 'text-white');

                const filterValue = btn.getAttribute('data-filter');

                mediaItems.forEach(item => {
                    // Animazione uscita
                    item.style.opacity = '0';
                    item.style.transform = 'scale(0.95)';

                    setTimeout(() => {
                        if (filterValue === 'all' || item.getAttribute('data-type') === filterValue) {
                            item.style.display = 'block';
                            // Animazione entrata (necessita breve delay per il reflow)
                            setTimeout(() => {
                                item.style.opacity = '1';
                                item.style.transform = 'scale(1)';
                            }, 50);
                        } else {
                            item.style.display = 'none';
                        }
                    }, 300); // Durata dell'animazione di uscita
                });
            });
        });

        // --- LIGHTBOX ---
        const lightbox = document.getElementById('mediaLightbox');
        const contentContainer = document.getElementById('lightboxContent');
        const titleContainer = document.getElementById('lightboxTitle');
        const closeBtn = document.getElementById('closeLightboxBtn');

        // Funzione Globale per Aprire (richiamata dall'onclick html)
        window.openLightbox = (url, type, title) => {
            // Mostra loader e resetta
            contentContainer.innerHTML = '<div class="w-16 h-16 border-4 border-white/20 border-t-white rounded-full animate-spin"></div>';
            titleContainer.textContent = title;
            lightbox.classList.remove('hidden');

            // Breve delay per permettere il display:block e triggerare l'animazione di opacità
            setTimeout(() => {
                lightbox.classList.remove('opacity-0');
                contentContainer.classList.remove('scale-95');
                contentContainer.classList.add('scale-100');
            }, 10);

            // Popola il contenuto in base al tipo
            setTimeout(() => { // Simula piccolo caricamento per fluidità
                if (type === 'foto') {
                    const img = new Image();
                    img.src = url;
                    img.alt = title;
                    img.className = "max-w-full max-h-full object-contain rounded-lg shadow-2xl animate-fade-in";
                    img.onload = () => {
                        contentContainer.innerHTML = '';
                        contentContainer.appendChild(img);
                    };
                } else if (type === 'video') {
                    // Cerca di estrarre ID Youtube per un embed pulito, altrimenti fallback iframe generico
                    let videoId = '';
                    const ytRegex = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i;
                    const match = url.match(ytRegex);

                    if (match && match[1]) {
                        videoId = match[1];
                        contentContainer.innerHTML = `<iframe class="w-full h-full max-w-5xl aspect-video rounded-xl shadow-2xl animate-fade-in" src="https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
                    } else {
                        // Fallback generico per altri video url
                        contentContainer.innerHTML = `<iframe class="w-full h-full max-w-5xl aspect-video rounded-xl shadow-2xl animate-fade-in" src="${url}" frameborder="0" allowfullscreen></iframe>`;
                    }
                }
            }, 300);

            document.body.style.overflow = 'hidden'; // Previeni scroll pagina
        };

        const closeLightbox = () => {
            lightbox.classList.add('opacity-0');
            contentContainer.classList.remove('scale-100');
            contentContainer.classList.add('scale-95');

            setTimeout(() => {
                lightbox.classList.add('hidden');
                contentContainer.innerHTML = ''; // Ferma i video
                document.body.style.overflow = '';
            }, 300);
        };

        closeBtn.addEventListener('click', closeLightbox);

        // Chiudi cliccando fuori dal contenuto
        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox || e.target === contentContainer) {
                closeLightbox();
            }
        });

        // Chiudi con ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !lightbox.classList.contains('hidden')) {
                closeLightbox();
            }
        });
    });
</script>

<?php get_footer(); ?>
