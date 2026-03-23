<?php
/**
 * Template part for displaying the YouTube Videos section
 *
 * @package santagatesi
 */

// Fetch the 4 latest YouTube videos using the custom function defined in functions.php
$youtube_videos = santagatesi_get_latest_youtube_videos( 4 );
?>

<section id="videos" class="section bg-[var(--color-surface)]">
	<div class="container">
		<header class="section-header text-center mb-12">
			<h2 class="text-4xl font-bold mb-4">Artemisium Web-TV</h2>
			<p class="text-lg text-[var(--color-on-surface-muted)] font-body">Gli ultimi video dal nostro canale YouTube ufficiale.</p>
		</header>

		<?php if ( ! empty( $youtube_videos ) ) : ?>
			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
				<?php foreach ( $youtube_videos as $video ) : ?>
					<div class="tonal-panel video-card flex flex-col p-4 bg-[var(--color-surface-container-lowest)] rounded-[var(--radius-lg)] shadow-[var(--shadow-ambient)] transition-transform duration-300 hover:-translate-y-1">
						<a href="<?php echo esc_url( $video['link'] ); ?>" target="_blank" rel="noopener noreferrer" class="block relative rounded-t-[var(--radius-lg)] overflow-hidden">
							<div class="video-thumbnail-container relative aspect-video bg-[var(--color-surface-container-low)]">
								<?php if ( ! empty( $video['thumbnail'] ) ) : ?>
									<img src="<?php echo esc_url( $video['thumbnail'] ); ?>" alt="<?php echo esc_attr( $video['title'] ); ?>" class="absolute inset-0 w-full h-full object-cover">
								<?php else : ?>
                                    <!-- Fallback image -->
									<div class="absolute inset-0 bg-[var(--color-surface-container-low)] flex items-center justify-center">
										<span class="text-[var(--color-on-surface-muted)]">Video</span>
									</div>
								<?php endif; ?>

								<!-- Play Icon Overlay -->
								<div class="video-play-icon absolute inset-0 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-300 bg-black/20">
                                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-lg transform transition-transform duration-300 hover:scale-110">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="currentColor" class="text-[var(--color-primary)] ml-2">
                                            <path d="M3 22v-20l18 10-18 10z"/>
                                        </svg>
                                    </div>
								</div>
							</div>
						</a>
						<div class="video-info mt-4 flex-grow flex flex-col">
							<p class="text-sm text-[var(--color-on-surface-muted)] mb-2 font-body font-semibold"><?php echo esc_html( $video['date'] ); ?></p>
							<h3 class="text-xl font-bold leading-snug line-clamp-2 text-[var(--color-primary)]">
								<a href="<?php echo esc_url( $video['link'] ); ?>" target="_blank" rel="noopener noreferrer" class="hover:text-[var(--color-accent)] transition-colors">
									<?php echo esc_html( $video['title'] ); ?>
								</a>
							</h3>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="text-center mt-16">
				<a href="https://www.youtube.com/user/nardino1000" target="_blank" rel="noopener noreferrer" class="btn btn-primary bg-[#FF0000] hover:bg-[#CC0000] text-white">
					Visita il Canale YouTube
				</a>
			</div>
		<?php else : ?>
			<p class="text-center text-[var(--color-on-surface-muted)]">Nessun video trovato al momento. Visita il nostro <a href="https://www.youtube.com/user/nardino1000" target="_blank" rel="noopener noreferrer" class="text-[var(--color-accent)] underline">Canale YouTube</a>.</p>
		<?php endif; ?>

	</div>
</section>
