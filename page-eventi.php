<?php
/**
 * Template Name: Eventi
 *
 * @package santagatesi
 */

get_header();

// Extract upcoming events using WP_Query for category 'eventi'
$args = array(
	'post_type'      => 'post',
	'posts_per_page' => 10,
	'category_name'  => 'eventi', // Make sure this slug exists in WP
	'orderby'        => 'date',
	'order'          => 'ASC'
);
$eventi_query = new WP_Query( $args );

// Generate a static lightweight CSS calendar for UI purposes
$current_month = date_i18n('F');
$current_year = date('Y');
$days_in_month = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
$first_day = date('w', strtotime(date('Y-m-01')));
?>

<main id="primary" class="site-main">

	<section class="section py-20 bg-gradient-to-b from-[#0f172a] to-blue-900/20">
		<div class="container">
			<header class="text-center mb-16">
				<h1 class="text-4xl md:text-5xl font-bold mb-4 flex items-center justify-center gap-3 text-white">
					<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-400"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
					Calendario Eventi
				</h1>
				<p class="text-xl text-blue-200">Non perderti i prossimi appuntamenti a Sant'Agata di Puglia.</p>
			</header>

			<div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-start">

				<!-- CSS UI Calendar Sidebar (Visual only, no JS logic needed for UI demo) -->
				<div class="lg:col-span-1 glass-panel p-6 sticky top-24">
					<div class="flex justify-between items-center mb-6 text-white font-bold text-lg border-b border-white/10 pb-4">
						<button class="hover:text-blue-400 transition">&larr;</button>
						<span class="capitalize"><?php echo $current_month . ' ' . $current_year; ?></span>
						<button class="hover:text-blue-400 transition">&rarr;</button>
					</div>

					<div class="grid grid-cols-7 gap-2 text-center text-sm font-semibold text-blue-300 mb-2">
						<div>Dom</div><div>Lun</div><div>Mar</div><div>Mer</div><div>Gio</div><div>Ven</div><div>Sab</div>
					</div>

					<div class="grid grid-cols-7 gap-2 text-center text-white/70">
						<?php
						// Empty days offset
						for ($i = 0; $i < $first_day; $i++) { echo '<div class="p-2 opacity-20">-</div>'; }

						// Actual days
						for ($day = 1; $day <= $days_in_month; $day++) {
							$is_today = ($day == date('j')) ? 'bg-blue-600 text-white rounded-full font-bold shadow-lg' : 'hover:bg-white/10 rounded-full cursor-pointer transition';
							echo '<div class="p-2 ' . $is_today . '">' . $day . '</div>';
						}
						?>
					</div>

					<div class="mt-8 pt-6 border-t border-white/10 text-center">
						<p class="text-sm text-blue-200 italic">Clicca su una data per filtrare gli eventi. <br>(Feature coming soon)</p>
					</div>
				</div>

				<!-- Events List (WP_Query) -->
				<div class="lg:col-span-2 space-y-8">

					<?php if ( $eventi_query->have_posts() ) : ?>
						<?php while ( $eventi_query->have_posts() ) : $eventi_query->the_post(); ?>
							<article class="glass-panel hover:bg-white/5 transition duration-300 flex flex-col md:flex-row gap-6 p-6">

								<!-- Date Badge -->
								<div class="flex-shrink-0 w-24 h-24 rounded-2xl bg-gradient-to-br from-blue-600 to-blue-900 flex flex-col items-center justify-center text-white shadow-inner border border-blue-500/50">
									<span class="text-sm font-semibold uppercase tracking-widest opacity-80"><?php echo get_the_date('M'); ?></span>
									<span class="text-3xl font-bold leading-none"><?php echo get_the_date('d'); ?></span>
								</div>

								<!-- Content -->
								<div class="flex-grow flex flex-col justify-center">
									<h2 class="text-2xl font-bold mb-2 text-white hover:text-blue-300 transition-colors">
										<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									</h2>
									<div class="text-blue-200/80 mb-4 line-clamp-2 text-sm">
										<?php the_excerpt(); ?>
									</div>
									<div class="mt-auto flex items-center gap-4 text-xs font-medium text-blue-400">
										<span class="flex items-center gap-1">
											<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
											<?php echo get_the_time(); ?>
										</span>
										<a href="<?php the_permalink(); ?>" class="text-white bg-blue-600/50 hover:bg-blue-600 px-3 py-1 rounded-full transition ml-auto border border-blue-500">
											Scopri i dettagli &rarr;
										</a>
									</div>
								</div>

							</article>
						<?php endwhile; wp_reset_postdata(); ?>
					<?php else : ?>

						<!-- Fallback No Events -->
						<div class="glass-panel text-center py-24 flex flex-col items-center justify-center">
							<svg class="w-20 h-20 text-blue-500/30 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
							<h3 class="text-2xl font-bold text-white mb-2">Nessun evento in programma</h3>
							<p class="text-blue-200">Al momento non ci sono eventi pubblicati in calendario per Sant'Agata. Torna a visitare questa pagina più tardi o seguici sui nostri canali social per aggiornamenti in tempo reale.</p>

							<div class="mt-8 flex gap-4">
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn bg-white/10 hover:bg-white/20 text-white border border-white/30 rounded-full px-6 py-2 transition">Torna alla Home</a>
							</div>
						</div>

					<?php endif; ?>

				</div>
			</div>
		</div>
	</section>
</main>

<?php get_footer(); ?>