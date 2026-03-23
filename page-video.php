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
// Let's get more videos for a dedicated page
$videos = santagatesi_get_latest_youtube_videos( 12 );

// Header background
$inline_style = "background-image: url('" . get_stylesheet_directory_uri() . "/images/video-bg.jpg');";
?>

<main id="primary" class="site-main">

	<!-- Hero Section -->
	<section class="hero-section" style="<?php echo esc_attr( $inline_style ); ?>">
		<div class="container relative z-10 py-24 text-center">
			<div class="glass-panel mx-auto max-w-4xl">
				<h1 class="text-4xl md:text-5xl font-bold mb-6 text-red-500 flex items-center justify-center gap-4">
					<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
						<path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
					</svg>
					Artemisium Web-TV
				</h1>
				<p class="text-xl text-color-muted">Archivio video e dirette dal canale ufficiale YouTube. Resta sintonizzato con gli eventi e le processioni di Sant'Agata.</p>
			</div>
		</div>
	</section>

	<!-- Video Grid Section -->
	<section class="section bg-opacity-50 min-h-screen">
		<div class="container">
			<?php if ( ! empty( $videos ) ) : ?>
				<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
					<?php foreach ( $videos as $video ) : ?>
						<article class="glass-panel video-card flex flex-col h-full transform transition hover:-translate-y-2 hover:shadow-xl">
							<a href="<?php echo esc_url( $video['link'] ); ?>" target="_blank" rel="noopener noreferrer" class="block relative w-full aspect-video">
								<div class="video-thumbnail-container absolute inset-0">
									<?php if ( ! empty( $video['thumbnail'] ) ) : ?>
										<img src="<?php echo esc_url( $video['thumbnail'] ); ?>" alt="<?php echo esc_attr( $video['title'] ); ?>" class="w-full h-full object-cover">
									<?php else : ?>
										<div class="w-full h-full bg-gray-800 flex items-center justify-center">
											<span class="text-gray-500">Nessuna miniatura</span>
										</div>
									<?php endif; ?>

									<div class="video-play-icon">
										<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
											<path d="M3 22v-20l18 10-18 10z"/>
										</svg>
									</div>
								</div>
							</a>

							<div class="p-4 flex flex-col flex-grow">
								<div class="text-xs text-color-muted mb-2 font-mono flex items-center gap-2">
									<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
									<?php echo esc_html( $video['date'] ); ?>
								</div>
								<h3 class="text-lg font-bold leading-tight mb-4 flex-grow">
									<a href="<?php echo esc_url( $video['link'] ); ?>" target="_blank" rel="noopener noreferrer" class="hover:text-red-400 transition-colors">
										<?php echo esc_html( $video['title'] ); ?>
									</a>
								</h3>
							</div>
						</article>
					<?php endforeach; ?>
				</div>

				<!-- CTA -->
				<div class="text-center mt-16 pb-8">
					<a href="https://www.youtube.com/user/nardino1000" target="_blank" rel="noopener noreferrer" class="btn btn-primary px-8 py-4 text-lg bg-red-600 hover:bg-red-700 shadow-lg flex inline-flex items-center gap-3 mx-auto max-w-max rounded-full">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
						Vai al Canale Ufficiale
					</a>
				</div>
			<?php else : ?>
				<div class="glass-panel text-center py-16">
					<svg class="mx-auto h-16 w-16 text-color-muted mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
					<h3 class="text-xl font-bold mb-2">Nessun video trovato</h3>
					<p class="text-color-muted mb-6">Si è verificato un errore nel recupero dei video dal feed RSS, o il canale non ha caricato nulla di recente.</p>
					<p class="text-color-muted text-sm border-t border-gray-700 pt-4 max-w-md mx-auto">Nota Tecnica: Per mostrare risultati in questa pagina, assicurati che la funzione in <code>functions.php</code> riesca a parsare l'RSS o inserisci un'API Key valida nel file template.</p>
				</div>
			<?php endif; ?>
		</div>
	</section>

</main>

<?php
get_footer();
