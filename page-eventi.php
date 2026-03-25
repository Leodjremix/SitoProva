<?php
/**
 * Template Name: Eventi
 *
 * @package santagatesi
 */

get_header();

// Extract upcoming events using WP_Query for custom post type 'santagatesi_evento'
$args = array(
	'post_type'      => 'santagatesi_evento',
	'posts_per_page' => 15,
    'meta_key'       => '_event_date',
	'orderby'        => 'meta_value',
	'order'          => 'ASC'
);
$eventi_query = new WP_Query( $args );

// Generate a static lightweight CSS calendar for UI purposes
$current_month = date_i18n('F');
$current_year = date('Y');
$days_in_month = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
$first_day = date('w', strtotime(date('Y-m-01')));

// Array per marcare i giorni con eventi sul calendario
$days_with_events = array();
if ( $eventi_query->have_posts() ) {
    while ( $eventi_query->have_posts() ) {
        $eventi_query->the_post();
        $date_meta = get_post_meta( get_the_ID(), '_event_date', true );
        if ( !empty($date_meta) && date('Y-m', strtotime($date_meta)) === date('Y-m') ) {
            $days_with_events[] = date('j', strtotime($date_meta));
        }
    }
    // Reset loop
    $eventi_query->rewind_posts();
}
?>

<main id="primary" class="site-main bg-[var(--color-surface)]">

	<section class="section py-24">
		<div class="container">
			<header class="text-center mb-20">
				<h1 class="text-5xl md:text-6xl font-bold mb-6 flex items-center justify-center gap-4 text-[var(--color-primary)] font-display">
					<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[var(--color-accent)]"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
					Calendario Eventi
				</h1>
				<p class="text-xl text-[var(--color-on-surface-muted)] font-body">Non perderti i prossimi appuntamenti a Sant'Agata di Puglia.</p>
			</header>

			<div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-start">

				<!-- CSS UI Calendar Sidebar -->
				<div class="lg:col-span-1 tonal-panel p-8 sticky top-32 bg-white">
					<div class="flex justify-between items-center mb-8 text-[var(--color-primary)] font-bold text-xl border-b border-[var(--color-surface-container-low)] pb-4 font-display">
						<button class="hover:text-[var(--color-accent)] transition-colors w-8 h-8 rounded-full hover:bg-[var(--color-surface)] flex items-center justify-center">&larr;</button>
						<span class="capitalize"><?php echo $current_month . ' ' . $current_year; ?></span>
						<button class="hover:text-[var(--color-accent)] transition-colors w-8 h-8 rounded-full hover:bg-[var(--color-surface)] flex items-center justify-center">&rarr;</button>
					</div>

					<div class="grid grid-cols-7 gap-2 text-center text-sm font-semibold text-[var(--color-on-surface-muted)] mb-4 font-body uppercase tracking-wider">
						<div>Dom</div><div>Lun</div><div>Mar</div><div>Mer</div><div>Gio</div><div>Ven</div><div>Sab</div>
					</div>

					<div class="grid grid-cols-7 gap-2 text-center text-[var(--color-on-surface)] font-body">
						<?php
						// Empty days offset
						for ($i = 0; $i < $first_day; $i++) { echo '<div class="p-2 opacity-30 text-gray-400">-</div>'; }

						// Actual days
						for ($day = 1; $day <= $days_in_month; $day++) {
							$is_today = ($day == date('j')) ? 'ring-2 ring-[var(--color-primary)]' : '';

                            // Highlight event days
                            $has_event_class = in_array($day, $days_with_events) ? 'bg-[var(--color-primary)] text-white shadow-md font-bold' : 'hover:bg-[var(--color-surface-container-low)] font-medium text-[var(--color-on-surface-muted)]';

							echo '<div class="p-2 w-10 h-10 rounded-full mx-auto flex items-center justify-center cursor-pointer transition-colors ' . $is_today . ' ' . $has_event_class . '">' . $day . '</div>';
						}
						?>
					</div>

					<div class="mt-10 pt-6 border-t border-[var(--color-surface-container-low)] text-center">
						<p class="text-sm text-[var(--color-on-surface-muted)] font-body italic">I giorni evidenziati contengono eventi programmati.</p>
					</div>
				</div>

				<!-- Events List (WP_Query Custom Post Type) -->
				<div class="lg:col-span-2 space-y-8">

					<?php if ( $eventi_query->have_posts() ) : ?>
						<?php while ( $eventi_query->have_posts() ) : $eventi_query->the_post();
                              $date_meta = get_post_meta( get_the_ID(), '_event_date', true );
                              $loc_meta  = get_post_meta( get_the_ID(), '_event_location', true );
                              $event_ts  = strtotime($date_meta);
                        ?>
							<article class="tonal-panel bg-white transition-all duration-300 hover:-translate-y-1 hover:shadow-lg flex flex-col sm:flex-row gap-8 p-8">

								<!-- Date Badge -->
								<div class="flex-shrink-0 w-28 h-28 rounded-2xl bg-[var(--color-surface-container-low)] flex flex-col items-center justify-center text-[var(--color-primary)] border border-transparent shadow-inner">
									<span class="text-sm font-bold uppercase tracking-widest opacity-80 font-body"><?php echo $date_meta ? date_i18n('M', $event_ts) : 'N/A'; ?></span>
									<span class="text-4xl font-extrabold leading-none mt-1 font-display"><?php echo $date_meta ? date_i18n('d', $event_ts) : '-'; ?></span>
								</div>

								<!-- Content -->
								<div class="flex-grow flex flex-col justify-center">
									<h2 class="text-3xl font-bold mb-3 text-[var(--color-primary)] hover:text-[var(--color-accent)] transition-colors font-display leading-tight">
										<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									</h2>
									<div class="text-[var(--color-on-surface-muted)] mb-6 line-clamp-2 text-base font-body leading-relaxed">
										<?php the_excerpt(); ?>
									</div>
									<div class="mt-auto flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6 text-sm font-semibold text-[var(--color-accent)] font-body border-t border-[var(--color-surface-container-low)] pt-4">
										<?php if ( ! empty($loc_meta) ) : ?>
                                            <span class="flex items-center gap-2">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                                <?php echo esc_html($loc_meta); ?>
                                            </span>
                                        <?php endif; ?>
										<a href="<?php the_permalink(); ?>" class="text-[var(--color-primary)] hover:text-[var(--color-accent)] transition-colors sm:ml-auto flex items-center gap-1 group">
											Scopri i dettagli
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="transition-transform group-hover:translate-x-1"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
										</a>
									</div>
								</div>

							</article>
						<?php endwhile; wp_reset_postdata(); ?>
					<?php else : ?>

						<!-- Fallback No Events -->
						<div class="tonal-panel bg-white text-center py-24 flex flex-col items-center justify-center border-2 border-dashed border-[var(--color-surface-container-low)]">
							<svg class="w-24 h-24 text-[var(--color-surface-container-low)] mb-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
							<h3 class="text-3xl font-bold text-[var(--color-primary)] mb-4 font-display">Nessun evento in programma</h3>
							<p class="text-[var(--color-on-surface-muted)] font-body max-w-lg mx-auto leading-relaxed">Al momento non ci sono eventi pubblicati in calendario per Sant'Agata. Torna a visitare questa pagina più tardi o seguici sui nostri canali social per aggiornamenti in tempo reale.</p>

							<div class="mt-10 flex gap-4">
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-secondary">Torna alla Home</a>
							</div>
						</div>

					<?php endif; ?>

				</div>
			</div>
		</div>
	</section>
</main>

<?php get_footer(); ?>