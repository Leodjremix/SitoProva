<?php
/**
 * Template Name: Video YouTube
 *
 * @package santagatesi
 */

get_header();

// Setup API Logic
$api_key = ''; // Leave empty as requested. We will use the RSS feed fallback from functions.php if this is empty.
$channel_id = 'nardino1000'; // Target YouTube Channel (Using Username logic from RSS)

// Fetch Videos using existing logic in functions.php that parses the RSS Feed since API key is empty
$videos = santagatesi_get_latest_youtube_videos( 12 );

// Header background
$inline_style = "background-image: url('" . get_stylesheet_directory_uri() . "/images/video-bg.jpg');";
?>

<main id="primary" class="site-main bg-[var(--color-surface)]">

	<!-- Modern Asymmetric Hero Section -->
	<section class="relative min-h-[70vh] flex items-center overflow-hidden bg-[#0a0a0a]">
		<!-- Cinematic Blurred Background -->
		<div class="absolute inset-0 z-0">
			<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/images/video-bg.jpg' ); ?>" alt="Background" class="w-full h-full object-cover opacity-40 scale-105 filter blur-sm">
			<div class="absolute inset-0 bg-gradient-to-r from-black via-black/80 to-transparent"></div>
			<div class="absolute inset-0 bg-gradient-to-t from-[var(--color-surface)] to-transparent opacity-90"></div>
		</div>

		<!-- Animated Particles / Grid Overlay -->
		<div class="absolute inset-0 z-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTSAwIDEwIEwgNDAgMTAgTSAxMCAwIEwgMTAgNDAiIGZpbGw9Im5vbmUiIHN0cm9rZT0icmdiYSgyNTUsMjU1LDI1NSwwLjA1KSIgc3Ryb2tlLXdpZHRoPSIxIi8+PC9wYXR0ZXJuPjwvZGVmcz48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSJ1cmwoI2dyaWQpIi8+PC9zdmc+')] opacity-50"></div>

		<div class="container relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center px-4">

			<!-- Left Text Area (Asymmetric) -->
			<div class="lg:col-span-7 space-y-8">
				<div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-red-500 font-bold uppercase tracking-widest text-sm mb-4">
					<span class="relative flex h-3 w-3">
					  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
					  <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
					</span>
					Live & Archive
				</div>

				<h1 class="text-5xl md:text-7xl font-extrabold text-white leading-tight font-display drop-shadow-lg">
					Artemisium <br>
					<span class="text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-orange-400">Web-TV</span>
				</h1>

				<p class="text-xl md:text-2xl text-gray-300 font-body leading-relaxed max-w-2xl border-l-4 border-red-500 pl-6">
					Archivio video e dirette dal canale ufficiale YouTube. Vivi le emozioni, le processioni e gli eventi di Sant'Agata ovunque tu sia.
				</p>

				<div class="flex items-center gap-6 pt-6">
					<a href="#video-grid" class="group relative inline-flex items-center justify-center px-8 py-4 font-bold text-white transition-all duration-200 bg-red-600 font-body rounded-xl hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600 shadow-[0_0_20px_rgba(220,38,38,0.4)]">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-3 animate-bounce"><polyline points="7 13 12 18 17 13"></polyline><polyline points="7 6 12 11 17 6"></polyline></svg>
						Esplora l'Archivio
					</a>
					<a href="https://www.youtube.com/user/nardino1000" target="_blank" rel="noopener noreferrer" class="text-gray-300 hover:text-white font-semibold flex items-center gap-2 transition-colors font-body">
						Iscriviti al Canale
						<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
					</a>
				</div>
			</div>

			<!-- Right Graphic Element (Floating Video Player Mockup) -->
			<div class="lg:col-span-5 hidden lg:block relative">
				<div class="relative w-full aspect-[4/3] rounded-3xl overflow-hidden shadow-2xl transform rotate-3 hover:rotate-0 transition-transform duration-500 border-4 border-white/10 group">
					<!-- Fake video thumbnail -->
					<div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1516280440503-6c680db18c2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80')] bg-cover bg-center transition-transform duration-700 group-hover:scale-110"></div>
					<!-- Play Button Overlay -->
					<div class="absolute inset-0 bg-black/40 flex items-center justify-center">
						<div class="w-24 h-24 bg-red-600/90 backdrop-blur-sm rounded-full flex items-center justify-center shadow-[0_0_30px_rgba(220,38,38,0.6)] animate-pulse">
							<svg width="40" height="40" viewBox="0 0 24 24" fill="currentColor" class="text-white ml-2"><path d="M3 22v-20l18 10-18 10z"/></svg>
						</div>
					</div>
					<!-- Fake UI Elements -->
					<div class="absolute bottom-0 inset-x-0 h-2 bg-white/20"><div class="h-full bg-red-600 w-1/3"></div></div>
				</div>
				<!-- Decorative blur behind -->
				<div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] h-[120%] bg-red-600/20 mix-blend-screen filter blur-[100px] -z-10 rounded-full"></div>
			</div>

		</div>
	</section>

	<!-- Video Grid Section -->
	<section class="section min-h-screen pt-12">
		<div class="container">
			<?php if ( ! empty( $videos ) ) : ?>
				<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-10">
					<?php foreach ( $videos as $video ) : ?>
						<article class="tonal-panel bg-white video-card flex flex-col h-full p-4 transition-transform duration-300 hover:-translate-y-2">
							<a href="<?php echo esc_url( $video['link'] ); ?>" target="_blank" rel="noopener noreferrer" class="block relative w-full aspect-video rounded-t-[var(--radius-md)] overflow-hidden">
								<div class="video-thumbnail-container absolute inset-0 bg-[var(--color-surface-container-low)]">
									<?php if ( ! empty( $video['thumbnail'] ) ) : ?>
										<img src="<?php echo esc_url( $video['thumbnail'] ); ?>" alt="<?php echo esc_attr( $video['title'] ); ?>" class="w-full h-full object-cover">
									<?php else : ?>
										<div class="w-full h-full bg-[var(--color-surface-container-low)] flex items-center justify-center">
											<span class="text-[var(--color-on-surface-muted)]">Nessuna miniatura</span>
										</div>
									<?php endif; ?>

									<div class="video-play-icon absolute inset-0 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-300 bg-black/20">
                                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-lg transform transition-transform duration-300 hover:scale-110">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="currentColor" class="text-[var(--color-primary)] ml-2">
                                                <path d="M3 22v-20l18 10-18 10z"/>
                                            </svg>
                                        </div>
									</div>
								</div>
							</a>

							<div class="pt-4 flex flex-col flex-grow">
								<div class="text-sm text-[var(--color-on-surface-muted)] mb-3 font-body font-semibold flex items-center gap-2">
									<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
									<?php echo esc_html( $video['date'] ); ?>
								</div>
								<h3 class="text-xl font-bold leading-snug mb-2 flex-grow text-[var(--color-primary)]">
									<a href="<?php echo esc_url( $video['link'] ); ?>" target="_blank" rel="noopener noreferrer" class="hover:text-[var(--color-accent)] transition-colors line-clamp-2">
										<?php echo esc_html( $video['title'] ); ?>
									</a>
								</h3>
							</div>
						</article>
					<?php endforeach; ?>
				</div>

				<!-- CTA -->
				<div class="text-center mt-20 pb-12">
					<a href="https://www.youtube.com/user/nardino1000" target="_blank" rel="noopener noreferrer" class="btn btn-primary px-8 py-4 text-lg bg-[#FF0000] hover:bg-[#CC0000] text-white shadow-lg flex inline-flex items-center gap-3 mx-auto max-w-max rounded-full transition-transform duration-300 hover:-translate-y-1">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
						Vai al Canale Ufficiale
					</a>
				</div>
			<?php else : ?>
				<div class="tonal-panel bg-white text-center py-20 border-2 border-dashed border-[var(--color-surface-container-low)]">
					<svg class="mx-auto h-20 w-20 text-[var(--color-surface-container-low)] mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
					<h3 class="text-3xl font-bold mb-4 text-[var(--color-primary)] font-display">Nessun video trovato</h3>
					<p class="text-[var(--color-on-surface-muted)] mb-8 font-body max-w-lg mx-auto">Si è verificato un errore nel recupero dei video dal feed RSS, o il canale non ha caricato nulla di recente.</p>
					<p class="text-[var(--color-on-surface-muted)] text-sm border-t border-[var(--color-surface-container-low)] pt-6 max-w-md mx-auto font-body italic">Nota Tecnica: Per mostrare risultati in questa pagina, assicurati che la funzione in <code>functions.php</code> riesca a parsare l'RSS o inserisci un'API Key valida nel file template.</p>
				</div>
			<?php endif; ?>
		</div>
	</section>

</main>

<?php
get_footer();
