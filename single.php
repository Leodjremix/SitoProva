<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package santagatesi
 */

get_header();
?>

	<main id="primary" class="site-main bg-[var(--color-surface)] pb-24">

		<?php
		while ( have_posts() ) :
			the_post();
			?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <!-- Hero Image (Full-width o Larga) -->
				<?php if ( has_post_thumbnail() ) : ?>
                    <div class="relative w-full">
                        <?php the_post_thumbnail( 'full', array( 'class' => 'single-hero-image w-full h-[50vh] min-h-[400px] object-cover' ) ); ?>
                        <div class="absolute inset-0 bg-gradient-to-t from-[var(--color-surface)] via-transparent to-black/20"></div>
                    </div>
                <?php else: ?>
                    <!-- Spazio compensativo se non c'è immagine -->
                    <div class="pt-32"></div>
				<?php endif; ?>

                <!-- Header dell'articolo sovrapposto o subito sotto -->
				<header class="single-header-meta container-reading mx-auto relative z-10 px-4">
                    <div class="tonal-panel bg-white p-8 md:p-12 shadow-[var(--shadow-hover)] rounded-[var(--radius-xl)] -mt-20 border border-[var(--color-surface-container-low)] text-center">

                        <div class="entry-meta flex justify-center items-center gap-4 text-sm font-semibold text-[var(--color-accent)] uppercase tracking-wider mb-6 font-body">
                            <span class="bg-[var(--color-surface-container-low)] px-4 py-2 rounded-full">
                                <?php
                                $categories = get_the_category();
                                if ( ! empty( $categories ) ) {
                                    echo esc_html( $categories[0]->name );
                                }
                                ?>
                            </span>
                            <span class="text-[var(--color-on-surface-muted)]">
                                <?php echo get_the_date(); ?>
                            </span>
                        </div>

                        <?php the_title( '<h1 class="single-title text-[var(--color-primary)] font-display font-extrabold">', '</h1>' ); ?>

                        <?php
                        $subtitle = get_post_meta( get_the_ID(), '_news_subtitle', true );
                        if ( ! empty( $subtitle ) ) :
                        ?>
                            <div class="news-subtitle mt-4 text-xl md:text-2xl text-[var(--color-on-surface-muted)] font-body font-medium italic border-l-4 border-[var(--color-accent)] pl-6 text-left max-w-3xl mx-auto leading-relaxed">
                                <?php echo wp_kses_post( $subtitle ); ?>
                            </div>
                        <?php endif; ?>

                        <div class="mt-8 text-[var(--color-on-surface-muted)] text-base font-body flex items-center justify-center gap-2">
                            <span>Di <strong><?php the_author(); ?></strong></span>
                            <?php if ( get_comments_number() ) : ?>
                                <span class="mx-2">&bull;</span>
                                <a href="#comments" class="hover:text-[var(--color-primary)] transition">
                                    <?php echo get_comments_number(); ?> Commenti
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
				</header>

                <!-- Contenuto Articolo (max-width per leggibilità) -->
				<div class="container-reading mx-auto mt-16 px-4">
					<div class="entry-content prose prose-lg prose-blue max-w-none font-body text-lg leading-loose text-[var(--color-on-surface)]">
						<?php
						the_content();
                        ?>
                    </div>

                    <?php
                    // Recupero i dati media extra e didascalie
                    $extra_img = get_post_meta( get_the_ID(), '_news_extra_image', true );
                    $video_url = get_post_meta( get_the_ID(), '_news_video_url', true );
                    $dida_1    = get_post_meta( get_the_ID(), '_news_dida_1', true );
                    $dida_2    = get_post_meta( get_the_ID(), '_news_dida_2', true );
                    $dida_3    = get_post_meta( get_the_ID(), '_news_dida_3', true );

                    // Mostra Video Player se presente
                    if ( ! empty( $video_url ) ) :
                        // Se è un video YouTube/Vimeo, wp_oembed_get lo incapsula in un iframe nativo
                        $embed = wp_oembed_get( $video_url );
                    ?>
                        <div class="news-video-container mt-12 mb-8 rounded-2xl overflow-hidden shadow-lg bg-[var(--color-surface-container-low)]">
                            <?php if ( $embed ) : ?>
                                <div class="aspect-video w-full">
                                    <?php echo $embed; ?>
                                </div>
                            <?php else : ?>
                                <!-- Fallback selettivo per video MP4 nativi o URL non supportati da oEmbed -->
                                <video controls class="w-full rounded-2xl shadow-sm">
                                    <source src="<?php echo esc_url( $video_url ); ?>" type="video/mp4">
                                    Il tuo browser non supporta il tag video. <a href="<?php echo esc_url( $video_url ); ?>">Scarica il video qui</a>.
                                </video>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php
                    // Mostra Immagine Extra se presente
                    if ( ! empty( $extra_img ) ) :
                    ?>
                        <figure class="news-extra-image mt-12 mb-8">
                            <img src="<?php echo esc_url( $extra_img ); ?>" alt="Immagine aggiuntiva" class="w-full rounded-2xl shadow-[var(--shadow-ambient)]">
                        </figure>
                    <?php endif; ?>

                    <?php
                    // Mostra Sezione Didascalie se almeno una è presente
                    if ( ! empty( $dida_1 ) || ! empty( $dida_2 ) || ! empty( $dida_3 ) ) :
                    ?>
                        <div class="news-didascalie-box mt-16 p-8 bg-blue-50 border-l-4 border-[var(--color-primary)] rounded-r-2xl text-[var(--color-on-surface)] font-body">
                            <h4 class="text-lg font-bold text-[var(--color-primary)] mb-4 font-display flex items-center gap-2">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                                Note e Approfondimenti
                            </h4>
                            <ul class="space-y-3">
                                <?php if ( ! empty( $dida_1 ) ) : ?>
                                    <li class="flex items-start gap-2">
                                        <span class="text-[var(--color-accent)] mt-1">&bull;</span>
                                        <span><?php echo wp_kses_post( $dida_1 ); ?></span>
                                    </li>
                                <?php endif; ?>
                                <?php if ( ! empty( $dida_2 ) ) : ?>
                                    <li class="flex items-start gap-2">
                                        <span class="text-[var(--color-accent)] mt-1">&bull;</span>
                                        <span><?php echo wp_kses_post( $dida_2 ); ?></span>
                                    </li>
                                <?php endif; ?>
                                <?php if ( ! empty( $dida_3 ) ) : ?>
                                    <li class="flex items-start gap-2">
                                        <span class="text-[var(--color-accent)] mt-1">&bull;</span>
                                        <span><?php echo wp_kses_post( $dida_3 ); ?></span>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="mt-8">
						<?php
						wp_link_pages(
							array(
								'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'santagatesi' ),
								'after'  => '</div>',
							)
						);
						?>
					</div>
				</div><!-- .container-reading -->

                <!-- Dettagli Autore e Condivisione -->
                <div class="container-reading mx-auto px-4">
                    <footer class="author-box border-t border-b border-[var(--color-surface-container-low)] py-10 my-16 flex flex-col md:flex-row items-center md:items-start gap-8 text-center md:text-left">

                        <div class="author-avatar flex-shrink-0">
                            <?php echo get_avatar( get_the_author_meta( 'ID' ), 100, '', '', array( 'class' => 'rounded-full shadow-md border-4 border-white' ) ); ?>
                        </div>

                        <div class="author-info flex-grow">
                            <h3 class="text-xl font-bold font-display text-[var(--color-primary)] mb-2">Scritto da <?php the_author(); ?></h3>
                            <p class="text-[var(--color-on-surface-muted)] font-body text-base mb-4 leading-relaxed">
                                <?php echo get_the_author_meta( 'description' ) ? esc_html( get_the_author_meta( 'description' ) ) : 'Redattore di Artemisium Web TV e membro dell\'Associazione Santagatesi nel Mondo.'; ?>
                            </p>

                            <!-- Pulsanti di Condivisione Social (Basic implementation) -->
                            <div class="social-share flex items-center justify-center md:justify-start gap-3 mt-4">
                                <span class="text-sm font-semibold uppercase text-[var(--color-on-surface-muted)] mr-2">Condividi:</span>
                                <div class="social-share-buttons flex gap-2">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener noreferrer" aria-label="Condividi su Facebook" class="bg-[var(--color-surface-container-lowest)] text-[#1877F2] hover:bg-[#1877F2] hover:text-white transition shadow-sm w-10 h-10 rounded-full flex items-center justify-center border border-[var(--color-surface-container-low)]">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/></svg>
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener noreferrer" aria-label="Condividi su Twitter" class="bg-[var(--color-surface-container-lowest)] text-[#1DA1F2] hover:bg-[#1DA1F2] hover:text-white transition shadow-sm w-10 h-10 rounded-full flex items-center justify-center border border-[var(--color-surface-container-low)]">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                                    </a>
                                    <a href="whatsapp://send?text=<?php echo urlencode(get_the_title() . ' - ' . get_permalink()); ?>" data-action="share/whatsapp/share" aria-label="Condividi su WhatsApp" class="bg-[var(--color-surface-container-lowest)] text-[#25D366] hover:bg-[#25D366] hover:text-white transition shadow-sm w-10 h-10 rounded-full flex items-center justify-center border border-[var(--color-surface-container-low)] md:hidden">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.016-.967-.259-.099-.447-.149-.636.149-.188.297-.76 1.018-.934 1.226-.172.208-.344.234-.641.085-1.921-.963-3.328-2.072-4.605-4.22-.124-.208-.014-.321.134-.47.133-.134.298-.348.447-.521.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.521-.075-.149-.635-1.536-.87-2.103-.229-.553-.464-.478-.636-.487-.165-.008-.354-.01-.543-.01-.188 0-.495.071-.754.368-.259.297-.991.968-.991 2.361s1.015 2.738 1.155 2.937c.14.198 2.003 3.061 4.852 4.291 2.14.924 2.946.994 4.148.835 1.011-.133 3.125-1.275 3.565-2.505.44-1.23.44-2.284.309-2.505-.131-.221-.497-.345-.794-.494l.001-.001zm-5.464 7.608h-.003c-1.611 0-3.189-.434-4.57-1.253l-.328-.194-3.398.891.908-3.313-.213-.339A9.957 9.957 0 0 1 2.01 12.01c0-5.525 4.498-10.024 10.022-10.024 2.678 0 5.197 1.043 7.09 2.936 1.894 1.892 2.937 4.412 2.937 7.09.001 5.525-4.496 10.024-10.023 10.024zM12.01 0C5.388 0 0 5.388 0 12.01c0 2.12.553 4.192 1.603 6.012L0 24l6.142-1.61A11.964 11.964 0 0 0 12.01 24c6.621 0 12-5.388 12-12.01S18.632 0 12.01 0z"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </footer>
                </div>

			</article><!-- #post-<?php the_ID(); ?> -->

            <!-- Navigazione Post Precedente/Successivo -->
            <div class="container-reading mx-auto px-4 mb-16">
                <?php
                the_post_navigation(
                    array(
                        'prev_text' => '<span class="nav-subtitle text-[var(--color-on-surface-muted)] text-sm uppercase tracking-widest block mb-2">' . esc_html__( 'Articolo Precedente', 'santagatesi' ) . '</span> <span class="nav-title font-bold text-xl text-[var(--color-primary)] hover:text-[var(--color-accent)] transition-colors">&larr; %title</span>',
                        'next_text' => '<span class="nav-subtitle text-[var(--color-on-surface-muted)] text-sm uppercase tracking-widest block mb-2 text-right">' . esc_html__( 'Articolo Successivo', 'santagatesi' ) . '</span> <span class="nav-title font-bold text-xl text-[var(--color-primary)] hover:text-[var(--color-accent)] transition-colors block text-right">%title &rarr;</span>',
                        'class'     => 'bg-white p-8 rounded-2xl shadow-sm border border-[var(--color-surface-container-low)] font-body',
                    )
                );
                ?>
            </div>

			<?php
            // Se i commenti sono abilitati, include il template del guestbook (oppure quello di default se preferito)
			if ( comments_open() || get_comments_number() ) :
                echo '<div class="container-reading mx-auto px-4">';
				comments_template();
                echo '</div>';
			endif;

		endwhile; // End of the loop.
		?>

	</main><!-- #primary -->

<?php
get_footer();
