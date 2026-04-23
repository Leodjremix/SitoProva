import re

with open('single.php', 'r') as f:
    content = f.read()

# Make sure all single.php logic we wrote earlier is applied here correctly without breaking variables.
var_fetch_block = """				</header>

                <?php
                // Recupero i dati media extra e didascalie in anticipo
                $extra_img = get_post_meta( get_the_ID(), '_news_extra_image_path', true );
                $video_url = get_post_meta( get_the_ID(), '_news_video_data', true );
                $dida_1    = get_post_meta( get_the_ID(), '_news_dida_1', true );
                $dida_2    = get_post_meta( get_the_ID(), '_news_dida_2', true );
                $dida_3    = get_post_meta( get_the_ID(), '_news_dida_3', true );
                ?>

                <!-- Media Primario -->
                <?php if ( has_post_thumbnail() ) : ?>
                <div class="container-reading mx-auto mt-12 px-4">
                    <figure class="primary-media-container w-full">
                        <?php the_post_thumbnail( 'full', array( 'class' => 'w-full rounded-2xl shadow-[var(--shadow-ambient)] object-cover' ) ); ?>
                        <?php if ( ! empty( $dida_1 ) ) : ?>
                            <figcaption class="mt-4 text-center text-sm md:text-base text-[var(--color-on-surface-muted)] italic font-body">
                                <?php echo wp_kses_post( $dida_1 ); ?>
                            </figcaption>
                        <?php endif; ?>
                    </figure>
                </div>
                <?php endif; ?>"""

content = content.replace("				</header>", var_fetch_block)

# Remove the default hero image that was above header
content = re.sub(
    r"\s*<!-- Hero Image \(Full-width o Larga\) -->.*?<\?php endif; \?>\s*",
    "\n",
    content,
    flags=re.DOTALL
)

# Remove the extra fetch block
content = re.sub(
    r"<\?php\s*// Recupero i dati media extra e didascalie.*?\s*\?>\s*",
    "\n                    ",
    content,
    flags=re.DOTALL
)

# Update Author mapping in header
content = re.sub(
    r"<span>Di <strong><\?php the_author\(\); \?></strong></span>",
    r"""<span>Di <strong><?php
                                $author_name = get_post_meta( get_the_ID(), '_news_author_name', true );
                                if ( ! empty( $author_name ) ) {
                                    echo esc_html( $author_name );
                                } else {
                                    the_author();
                                }
                                ?></strong></span>""",
    content
)

# Update Author info at bottom
content = re.sub(
    r"<h3 class=\"text-xl font-bold font-display text-\[var\(--color-primary\)\] mb-2\">Scritto da <\?php the_author\(\); \?></h3>",
    r"""<h3 class="text-xl font-bold font-display text-[var(--color-primary)] mb-2">Scritto da <?php
                                $author_name = get_post_meta( get_the_ID(), '_news_author_name', true );
                                if ( ! empty( $author_name ) ) {
                                    echo esc_html( $author_name );
                                } else {
                                    the_author();
                                }
                                ?></h3>""",
    content
)

# Update extra image and video
extra_media_block = """                    <?php
                    // Mostra Video Player se presente
                    if ( ! empty( $video_url ) ) :
                        if ( strpos( $video_url, '<iframe' ) !== false ) :
                        ?>
                            <div class="news-video-container mt-12 mb-8 rounded-2xl overflow-hidden shadow-lg bg-[var(--color-surface-container-low)]">
                                <div class="aspect-video w-full">
                                    <?php echo $video_url; // It's an iframe, we trust it from backend ?>
                                </div>
                            </div>
                        <?php else :
                            $embed = wp_oembed_get( $video_url );
                        ?>
                            <div class="news-video-container mt-12 mb-8 rounded-2xl overflow-hidden shadow-lg bg-[var(--color-surface-container-low)]">
                                <?php if ( $embed ) : ?>
                                    <div class="aspect-video w-full">
                                        <?php echo $embed; ?>
                                    </div>
                                <?php else : ?>
                                    <video controls class="w-full rounded-2xl shadow-sm">
                                        <source src="<?php echo esc_url( $video_url ); ?>" type="video/mp4">
                                        Il tuo browser non supporta il tag video. <a href="<?php echo esc_url( $video_url ); ?>">Scarica il video qui</a>.
                                    </video>
                                <?php endif; ?>
                            </div>
                        <?php endif;
                    endif; ?>

                    <?php
                    // Mostra Immagine Extra se presente
                    if ( ! empty( $extra_img ) ) :
                    ?>
                        <figure class="news-extra-image mt-12 mb-8">
                            <img src="<?php echo esc_url( $extra_img ); ?>" alt="Immagine aggiuntiva" class="w-full rounded-2xl shadow-[var(--shadow-ambient)]">
                            <?php if ( ! empty( $dida_2 ) ) : ?>
                                <figcaption class="mt-4 text-center text-sm md:text-base text-[var(--color-on-surface-muted)] italic font-body">
                                    <?php echo wp_kses_post( $dida_2 ); ?>
                                </figcaption>
                            <?php endif; ?>
                        </figure>
                    <?php endif; ?>"""

content = re.sub(
    r"<\?php\s*// Mostra Video Player se presente.*?<\?php endif; \?>",
    extra_media_block,
    content,
    flags=re.DOTALL
)

# Footer dida_3 note
dida3_block = """                    <?php
                    // Footer Articolo: Nota o Commento Autore
                    if ( ! empty( $dida_3 ) ) :
                    ?>
                        <div class="news-didascalie-box mt-16 p-8 bg-blue-50 border-l-4 border-[var(--color-primary)] rounded-r-2xl text-[var(--color-on-surface)] font-body">
                            <h4 class="text-lg font-bold text-[var(--color-primary)] mb-4 font-display flex items-center gap-2">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                                Nota dell'Autore
                            </h4>
                            <p class="leading-relaxed">
                                <?php echo wp_kses_post( $dida_3 ); ?>
                            </p>
                        </div>
                    <?php endif; ?>"""

content = re.sub(
    r"<\?php\s*// Mostra Sezione Didascalie se almeno una è presente.*?<\?php endif; \?>",
    dida3_block,
    content,
    flags=re.DOTALL
)

content = re.sub(
    r"tonal-panel bg-white p-8 md:p-12 shadow-\[var\(--shadow-hover\)\] rounded-\[var\(--radius-xl\)\] -mt-20 border border-\[var\(--color-surface-container-low\)\] text-center",
    r"tonal-panel bg-white p-8 md:p-12 shadow-[var(--shadow-hover)] rounded-[var(--radius-xl)] border border-[var(--color-surface-container-low)] text-center mt-12",
    content
)


with open('single.php', 'w') as f:
    f.write(content)
