<?php
/**
 * Template part for displaying the YouTube Videos section
 *
 * @package santagatesi
 */

// Fetch the 4 latest YouTube videos using the custom function defined in functions.php
$youtube_videos = santagatesi_get_latest_youtube_videos( 4 );
?>

<section id="videos" class="section">
	<div class="container">
		<header class="section-header text-center mb-8">
			<h2 class="text-3xl font-bold mb-4">Artemisium Web-TV</h2>
			<p class="text-color-muted">Gli ultimi video dal nostro canale YouTube ufficiale.</p>
		</header>

		<?php if ( ! empty( $youtube_videos ) ) : ?>
			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
				<?php foreach ( $youtube_videos as $video ) : ?>
					<div class="glass-panel video-card">
						<a href="<?php echo esc_url( $video['link'] ); ?>" target="_blank" rel="noopener noreferrer" class="block relative">
							<div class="video-thumbnail-container">
								<?php if ( ! empty( $video['thumbnail'] ) ) : ?>
									<img src="<?php echo esc_url( $video['thumbnail'] ); ?>" alt="<?php echo esc_attr( $video['title'] ); ?>">
								<?php else : ?>
                                    <!-- Fallback image -->
									<div class="absolute inset-0 bg-gray-800 flex items-center justify-center">
										<span class="text-gray-500">Video</span>
									</div>
								<?php endif; ?>

								<!-- Play Icon Overlay -->
								<div class="video-play-icon">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
										<path d="M3 22v-20l18 10-18 10z"/>
									</svg>
								</div>
							</div>
						</a>
						<div class="video-info mt-4">
							<p class="text-xs text-color-muted mb-1"><?php echo esc_html( $video['date'] ); ?></p>
							<h3 class="text-lg font-semibold leading-tight line-clamp-2">
								<a href="<?php echo esc_url( $video['link'] ); ?>" target="_blank" rel="noopener noreferrer">
									<?php echo esc_html( $video['title'] ); ?>
								</a>
							</h3>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="text-center mt-12">
				<a href="https://www.youtube.com/user/nardino1000" target="_blank" rel="noopener noreferrer" class="btn btn-primary bg-red-600 hover:bg-red-700">
					Visita il Canale YouTube
				</a>
			</div>
		<?php else : ?>
			<p class="text-center text-color-muted">Nessun video trovato al momento. Visita il nostro <a href="https://www.youtube.com/user/nardino1000" target="_blank" rel="noopener noreferrer" class="text-accent underline">Canale YouTube</a>.</p>
		<?php endif; ?>

	</div>
</section>
