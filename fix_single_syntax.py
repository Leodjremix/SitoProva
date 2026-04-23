import re

with open('single.php', 'r') as f:
    content = f.read()


fixed_video_block = """                    <?php
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
                    endif; ?>"""

broken_video_block = """                    <div class="news-video-container mt-12 mb-8 rounded-2xl overflow-hidden shadow-lg bg-[var(--color-surface-container-low)]">
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
                    <?php endif; ?>"""

content = content.replace(broken_video_block, fixed_video_block)


# Remove the trailing unused Array display logic
content = re.sub(
    r"""                                <\?php if \( ! empty\( \$dida_2 \) \) : \?>.*<\?php endif; \?>""",
    r"",
    content,
    flags=re.DOTALL
)

with open('single.php', 'w') as f:
    f.write(content)
