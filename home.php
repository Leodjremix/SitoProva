<?php
/**
 * The main template file for the blog (Artemisium News)
 * Overrides standard WordPress index for blog posts.
 *
 * @package santagatesi
 */

get_header();

// Se l'utente non ha impostato una pagina "Blog" fissa nelle impostazioni,
// prendiamo comunque i post principali.
?>

<main id="primary" class="site-main bg-gradient-to-b from-[#0f172a] via-[#1e293b] to-[#0f172a] min-h-screen">
	<div class="container section">

		<header class="page-header text-center mb-16 py-12 border-b border-white/10">
			<h1 class="text-4xl md:text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-emerald-400 mb-4 tracking-tight">
				Artemisium News
			</h1>
			<p class="text-xl text-blue-200/80 max-w-2xl mx-auto font-light">
				Notizie locali, approfondimenti culturali e aggiornamenti dalla comunità di Sant'Agata di Puglia.
			</p>
		</header>

		<?php if ( have_posts() ) : ?>

			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
				<?php
				/* Start the Loop */
				while ( have_posts() ) :
					the_post();
					?>

					<!-- News Card (Newspaper Style) -->
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'glass-panel flex flex-col hover:shadow-2xl hover:border-blue-500/30 transition-all duration-300 group' ); ?>>

						<!-- Thumbnail Header -->
						<?php if ( has_post_thumbnail() ) : ?>
							<a href="<?php the_permalink(); ?>" class="block relative overflow-hidden rounded-t-xl aspect-[4/3]">
								<?php the_post_thumbnail( 'medium_large', array( 'class' => 'w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500' ) ); ?>
								<div class="absolute inset-0 bg-gradient-to-t from-[#0f172a] to-transparent opacity-80"></div>
							</a>
						<?php else : ?>
							<!-- Fallback Modern Thumbnail -->
							<a href="<?php the_permalink(); ?>" class="block relative overflow-hidden rounded-t-xl aspect-[4/3] bg-gradient-to-br from-blue-900 to-slate-800 flex items-center justify-center">
								<svg class="w-16 h-16 text-white/20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
								<div class="absolute inset-0 bg-gradient-to-t from-[#0f172a] to-transparent opacity-80"></div>
							</a>
						<?php endif; ?>

						<!-- Content Area -->
						<div class="p-6 flex-grow flex flex-col relative -mt-12 z-10 bg-[#1e293b]/90 backdrop-blur-md rounded-xl mx-4 shadow-lg border border-white/5">

							<div class="flex items-center justify-between text-xs font-semibold text-blue-300 uppercase tracking-wider mb-3">
								<span>
									<?php
										$categories = get_the_category();
										if ( ! empty( $categories ) ) {
											echo esc_html( $categories[0]->name );
										} else {
											echo 'News';
										}
									?>
								</span>
								<time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date('d M Y'); ?></time>
							</div>

							<h2 class="text-xl font-bold text-white mb-4 line-clamp-3 hover:text-blue-400 transition-colors">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h2>

							<div class="text-slate-300 text-sm mb-6 flex-grow line-clamp-3 leading-relaxed">
								<?php the_excerpt(); ?>
							</div>

							<div class="mt-auto pt-4 border-t border-slate-700/50 flex items-center justify-between">
								<div class="flex items-center gap-2">
									<div class="w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center text-white font-bold text-xs uppercase border border-slate-600">
										<?php echo substr(get_the_author(), 0, 1); ?>
									</div>
									<span class="text-xs text-slate-400 font-medium">di <?php the_author(); ?></span>
								</div>

								<a href="<?php the_permalink(); ?>" class="text-blue-400 hover:text-blue-300 font-semibold text-sm flex items-center gap-1 group-hover:translate-x-1 transition-transform">
									Leggi <span aria-hidden="true">&rarr;</span>
								</a>
							</div>

						</div>
					</article>

					<?php
				endwhile;
				?>
			</div>

			<!-- Modern Pagination -->
			<div class="mt-16 flex justify-center">
				<?php
				the_posts_pagination( array(
					'mid_size'  => 2,
					'prev_text' => '<span class="px-4 py-2 rounded-l-lg bg-white/5 border border-white/10 hover:bg-blue-600 transition">&larr; Precedente</span>',
					'next_text' => '<span class="px-4 py-2 rounded-r-lg bg-white/5 border border-white/10 hover:bg-blue-600 transition">Successivo &rarr;</span>',
					'class'     => 'flex gap-2 text-white',
				) );
				?>
			</div>

		<?php else : ?>

			<!-- No Posts Found -->
			<section class="glass-panel text-center py-24 max-w-2xl mx-auto border-dashed border-2 border-slate-600">
				<div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-800 mb-6">
					<svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
				</div>
				<h2 class="text-3xl font-bold text-white mb-4">Nessun Articolo Trovato</h2>
				<p class="text-slate-400 text-lg mb-8">Non ci sono notizie pubblicate in questo momento. Torna a trovarci presto.</p>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn bg-blue-600 hover:bg-blue-500 text-white rounded-full px-8 py-3 transition shadow-lg shadow-blue-500/20">Torna alla Home</a>
			</section>

		<?php endif; ?>

	</div>
</main>

<?php
get_footer();
