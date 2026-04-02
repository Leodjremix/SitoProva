<?php
/**
 * Santagatesi nel Mondo - Child Theme Functions
 *
 * Main entry point. All logic is strictly modularized and placed within the /inc/ directory.
 * Ensure to use `get_stylesheet_directory()` to target this child theme.
 *
 * @package santagatesi
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Modular Includes Loader
 *
 * Safely require all functional components of the theme.
 */
$includes = [
    '/inc/setup.php',
    '/inc/enqueue.php',
    '/inc/helpers.php',
    '/inc/shortcodes.php',
    '/inc/security.php',
    '/inc/frontend-posting.php',
    '/inc/cpt-eventi.php',
    '/inc/cpt-illustri.php',
    '/inc/cpt-team.php',
    '/inc/cpt-links.php',
    '/inc/cpt-media.php',
    '/inc/cpt-hero.php',
    '/inc/frontend-settings.php',
    '/inc/frontend-eventi.php',
    '/inc/admin-dashboard.php',
    '/inc/watermark.php',
    '/inc/integrations.php',
    '/inc/migration-illustri.php'
];

foreach ( $includes as $file ) {
    $filepath = get_stylesheet_directory() . $file;
    if ( file_exists( $filepath ) ) {
        require_once $filepath;
    } else {
        error_log( 'File mancante nel tema child Santagatesi: ' . $filepath );
    }
}

/**
 * Temporary Rewrite Flush logic for CPT structural changes
 * We only want this to run once when the new code hits the live server.
 */
function santagatesi_flush_rewrites_on_update() {
    if ( ! get_option( 'santagatesi_cpt_refactor_flushed' ) ) {
        flush_rewrite_rules();
        update_option( 'santagatesi_cpt_refactor_flushed', true );
    }
}
add_action( 'admin_init', 'santagatesi_flush_rewrites_on_update' );

/**
 * Custom Comment Format for Guestbook (Masonry/Polaroid Style)
 */
function santagatesi_guestbook_comment_format( $comment, $args, $depth ) {
    $tag = ( 'div' === $args['style'] ) ? 'div' : 'li';

    // Array of slight rotations to give a random, organic bulletin board feel
    $rotations = array('-rotate-1', 'rotate-1', '-rotate-2', 'rotate-2', 'rotate-0');
    $random_rotation = $rotations[array_rand($rotations)];

    // Array of subtle background tints for variety
    $bg_colors = array('bg-yellow-50', 'bg-blue-50', 'bg-pink-50', 'bg-green-50', 'bg-purple-50');
    $random_bg = $bg_colors[array_rand($bg_colors)];
?>
    <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? 'mb-6 break-inside-avoid relative group transition-transform duration-300 hover:scale-105 hover:z-10' : 'parent mb-6 break-inside-avoid relative group transition-transform duration-300 hover:scale-105 hover:z-10', $comment ); ?>>

        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body p-6 rounded-xl border border-[var(--color-surface-container-low)] shadow-sm hover:shadow-lg transition-shadow <?php echo esc_attr( $random_bg ); ?> <?php echo esc_attr( $random_rotation ); ?>">

            <!-- Pin decoration (Pushpin) -->
            <div class="absolute -top-3 left-1/2 -translate-x-1/2 w-6 h-6 rounded-full bg-red-400 border border-red-600 shadow-md flex items-center justify-center z-10">
                <div class="w-2 h-2 rounded-full bg-white opacity-60"></div>
                <div class="w-[2px] h-4 bg-gray-400 absolute -bottom-3 left-1/2 -translate-x-1/2 -z-10 shadow-sm"></div>
            </div>

            <footer class="comment-meta flex items-center gap-4 mb-4 border-b border-black/5 pb-4 mt-2">
                <div class="comment-author vcard flex-shrink-0">
                    <?php
                    if ( 0 != $args['avatar_size'] ) {
                        echo get_avatar( $comment, $args['avatar_size'], '', '', array( 'class' => 'rounded-full border-2 border-white shadow-sm' ) );
                    }
                    ?>
                </div><!-- .comment-author -->

                <div class="comment-metadata flex flex-col justify-center">
                    <b class="fn text-lg font-semibold text-[var(--color-primary)] font-display">
                        <?php echo get_comment_author_link( $comment ); ?>
                    </b>
                    <div class="text-xs text-[var(--color-on-surface-muted)] font-body flex items-center gap-2">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        <time datetime="<?php comment_time( 'c' ); ?>">
                            <?php
                            printf(
                                /* translators: 1: Comment date, 2: Comment time. */
                                esc_html__( '%1$s alle %2$s', 'santagatesi' ),
                                get_comment_date( '', $comment ),
                                get_comment_time()
                            );
                            ?>
                        </time>
                    </div>
                </div><!-- .comment-metadata -->
            </footer><!-- .comment-meta -->

            <div class="comment-content text-[var(--color-on-surface)] font-body leading-relaxed text-base italic text-gray-800">
                <?php
                // Standard output: get_comment_text() returns formatted HTML (e.g., <p> tags)
                // We use wp_kses_post to ensure it's safe while preserving basic markup.
                echo wp_kses_post( get_comment_text() );
                ?>
            </div><!-- .comment-content -->

            <?php if ( '0' == $comment->comment_approved ) : ?>
                <p class="comment-awaiting-moderation mt-4 text-sm text-amber-600 bg-amber-50 p-2 rounded-lg border border-amber-200">
                    <?php esc_html_e( 'Il tuo saluto è in attesa di moderazione.', 'santagatesi' ); ?>
                </p>
            <?php endif; ?>

            <div class="reply mt-4 flex justify-end">
                <?php
                comment_reply_link(
                    array_merge(
                        $args,
                        array(
                            'add_below' => 'div-comment',
                            'depth'     => $depth,
                            'max_depth' => $args['max_depth'],
                            'before'    => '<div class="reply text-sm font-medium text-[var(--color-accent)] hover:underline flex items-center gap-1"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 17 4 12 9 7"></polyline><path d="M20 18v-2a4 4 0 0 0-4-4H4"></path></svg>',
                            'after'     => '</div>',
                        )
                    )
                );
                ?>
            </div>
        </article><!-- .comment-body -->
<?php
}

/**
 * Force comments open for the Guestbook template regardless of individual page settings.
 */
function santagatesi_force_guestbook_comments_open( $open, $post_id ) {
    $template = get_page_template_slug( $post_id );
    if ( 'page-libro-saluti.php' === $template ) {
        return true;
    }
    return $open;
}
add_filter( 'comments_open', 'santagatesi_force_guestbook_comments_open', 10, 2 );
