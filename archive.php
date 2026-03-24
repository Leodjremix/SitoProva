<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package santagatesi
 */

get_header();
?>

	<main id="primary" class="site-main bg-[var(--color-surface)] pb-24 min-h-screen">

		<?php if ( have_posts() ) : ?>

			<!-- Header Archivio -->
			<header class="page-header py-24 bg-[var(--color-surface-container-lowest)] border-b border-[var(--color-surface-container-low)] mb-16 shadow-[var(--shadow-ambient)] text-center relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-b from-[var(--color-surface)] to-transparent opacity-50"></div>
                <div class="container relative z-10 px-4">
                    <?php
                    // Titolo
                    the_archive_title( '<h1 class="page-title text-4xl md:text-6xl font-extrabold text-[var(--color-primary)] font-display mb-6 tracking-tight">', '</h1>' );

                    // Descrizione
                    the_archive_description( '<div class="archive-description text-xl text-[var(--color-on-surface-muted)] max-w-3xl mx-auto font-body leading-relaxed">', '</div>' );
                    ?>
                </div>
			</header><!-- .page-header -->

            <!-- Griglia Articoli -->
            <div class="container px-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 md:gap-10">
                    <?php
                    /* Start the Loop */
                    while ( have_posts() ) :
                        the_post();
                        ?>

                        <article id="post-<?php the_ID(); ?>" <?php post_class('article-card group'); ?>>

                            <!-- Thumbnail 16:9 -->
                            <a href="<?php echo esc_url( get_permalink() ); ?>" class="block relative article-card-image-wrapper">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail( 'medium_large', array( 'class' => 'w-full h-full object-cover transition-transform duration-700 group-hover:scale-105' ) ); ?>
                                <?php else : ?>
                                    <div class="absolute inset-0 flex items-center justify-center bg-[var(--color-surface)]">
                                        <svg class="w-16 h-16 text-[var(--color-on-surface-muted)] opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                <?php endif; ?>
                            </a>

                            <!-- Contenuto Card -->
                            <div class="article-card-content">

                                <!-- Meta -->
                                <div class="entry-meta flex items-center justify-between text-xs font-semibold text-[var(--color-on-surface-muted)] uppercase tracking-wider mb-4 font-body border-b border-[var(--color-surface)] pb-3">
                                    <span class="flex items-center gap-1 text-[var(--color-accent)]">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                        <?php echo get_the_date('d M Y'); ?>
                                    </span>
                                    <span>di <?php the_author(); ?></span>
                                </div>

                                <!-- Titolo -->
                                <h2 class="text-2xl font-bold text-[var(--color-primary)] font-display leading-snug mb-3 group-hover:text-[var(--color-accent)] transition-colors">
                                    <a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>

                                <!-- Riassunto -->
                                <div class="entry-summary text-[var(--color-on-surface-muted)] text-base font-body leading-relaxed mb-6 line-clamp-3">
                                    <?php the_excerpt(); ?>
                                </div>

                                <!-- Footer Card (Link a fondo fisso) -->
                                <div class="mt-auto pt-4">
                                    <a href="<?php echo esc_url( get_permalink() ); ?>" class="inline-flex items-center gap-2 text-sm font-bold text-[var(--color-accent)] hover:text-[var(--color-primary)] transition-colors font-body uppercase tracking-wide">
                                        Leggi l'articolo
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="transition-transform group-hover:translate-x-1"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                                    </a>
                                </div>

                            </div>
                        </article><!-- #post-<?php the_ID(); ?> -->

                        <?php
                    endwhile;
                    ?>
                </div>

                <!-- Paginazione -->
                <div class="mt-16 pt-8 border-t border-[var(--color-surface-container-low)]">
                    <?php
                    the_posts_pagination(
                        array(
                            'mid_size'  => 2,
                            'prev_text' => sprintf(
                                '%s <span class="nav-prev-text">%s</span>',
                                '<span class="text-[var(--color-accent)] text-2xl mr-2" aria-hidden="true">&larr;</span>',
                                __( 'Pagina precedente', 'santagatesi' )
                            ),
                            'next_text' => sprintf(
                                '<span class="nav-next-text">%s</span> %s',
                                __( 'Pagina successiva', 'santagatesi' ),
                                '<span class="text-[var(--color-accent)] text-2xl ml-2" aria-hidden="true">&rarr;</span>'
                            ),
                            'class'     => 'pagination font-body font-bold text-lg flex justify-center',
                        )
                    );
                    ?>
                </div>

            </div>

		<?php else : ?>

            <!-- Fallback se non ci sono articoli -->
			<div class="container px-4 text-center py-24">
                <div class="tonal-panel bg-white max-w-2xl mx-auto border border-dashed border-[var(--color-surface-container-low)]">
                    <svg class="mx-auto h-24 w-24 text-[var(--color-surface-container-low)] mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                    <h2 class="text-3xl font-display font-bold text-[var(--color-primary)] mb-4">Nessun articolo trovato</h2>
                    <p class="text-[var(--color-on-surface-muted)] font-body text-lg">Sembra che non ci siano ancora articoli pubblicati in questa categoria. Torna a trovarci presto!</p>
                </div>
            </div>

		<?php endif; ?>

	</main><!-- #primary -->

<?php
get_footer();
