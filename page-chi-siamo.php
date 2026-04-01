<?php
/**
 * Template Name: Chi Siamo
 *
 * @package santagatesi
 */

get_header();

// Hero Data from CMS
$hero_data = get_option('santagatesi_hero_data', array());
$chisiamo_hero = isset($hero_data['chi_siamo']) ? $hero_data['chi_siamo'] : array();

// Manteniamo l'esperienza foolproof del CMS Custom, estraendo dal pannello di controllo dedicato
$hero_title = !empty($chisiamo_hero['title']) ? $chisiamo_hero['title'] : "Chi Siamo";
$hero_subtitle = !empty($chisiamo_hero['subtitle']) ? $chisiamo_hero['subtitle'] : "Associazione di Promozione Sociale - Un ponte vitale tra la nostra amata Sant'Agata di Puglia e i Santagatesi sparsi per il mondo.";
$hero_bg_dynamic  = !empty($chisiamo_hero['bg_image']) ? $chisiamo_hero['bg_image'] : 'https://www.santagatesinelmondo.it/public/header/fratelli%20emigranti.jpg';
$chisiamo_content = !empty($chisiamo_hero['content']) ? $chisiamo_hero['content'] : "<p>L'Associazione Santagatesi nel Mondo nasce dalla volontà di mantenere vive le radici, le tradizioni e il legame indissolubile con il nostro paese d'origine. Attraverso questo portale, la web TV Artemisium e le innumerevoli iniziative culturali, ci impegniamo ogni giorno a superare le distanze geografiche.</p><p>Il nostro obiettivo è valorizzare il patrimonio storico, artistico e umano di Sant'Agata di Puglia, offrendo un punto di ritrovo virtuale e reale per tutti i compaesani emigrati.</p>";

$inline_style = "background-image: url('" . esc_url( $hero_bg_dynamic ) . "');";
?>

<main id="primary" class="site-main bg-[var(--color-surface)]">

	<!-- Striped Layout Content Section -->
	<section class="section pt-0">
		<div class="w-full">
			<?php
			while ( have_posts() ) :
				the_post();
				?>

                <!-- Hero Section Dinamica (Pescata dal Dashboard Admin) -->
                <section class="hero-section" style="<?php echo esc_attr( $inline_style ); ?>">
                    <div class="container relative z-10 py-24 flex justify-center pt-32">
                        <div class="tonal-panel mx-auto max-w-4xl text-center bg-white/90 backdrop-blur-md">

                            <h1 class="text-4xl md:text-6xl font-bold mb-6 text-[var(--color-primary)] font-display">
                                <?php echo esc_html( $hero_title ); ?>
                            </h1>

                            <?php if ( $hero_subtitle ) : ?>
                                <p class="text-xl text-[var(--color-on-surface-muted)] font-body leading-relaxed max-w-2xl mx-auto">
                                    <?php echo esc_html( $hero_subtitle ); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>

                <!-- Contenuto Articolo -->
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'container pt-16' ); ?>>

					<div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center mb-24">
						<div class="entry-content prose prose-lg max-w-none text-[var(--color-on-surface-muted)] font-body leading-loose">
							<?php
                            // Il contenuto stampato è ora quello personalizzato dall'Admin Dashboard
                            echo wp_kses_post( wpautop( $chisiamo_content ) );
							?>
						</div>

						<div class="tonal-panel text-center p-10 bg-[var(--color-surface-container-low)] relative overflow-hidden">
                            <div class="absolute -right-10 -bottom-10 opacity-5">
                                <svg width="150" height="150" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L2 22h20L12 2zm0 4.5l6.5 13h-13L12 6.5z"/></svg>
                            </div>
							<h3 class="text-3xl font-bold mb-8 text-[var(--color-primary)] font-display">Il nostro impegno</h3>
							<ul class="text-left space-y-6 text-[var(--color-on-surface-muted)] font-body text-lg">
								<li class="flex items-center gap-4 bg-white p-4 rounded-2xl shadow-sm">
									<svg class="text-[var(--color-accent)] flex-shrink-0" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
									<span class="font-semibold">Promozione Culturale</span>
								</li>
								<li class="flex items-center gap-4 bg-white p-4 rounded-2xl shadow-sm">
									<svg class="text-[var(--color-accent)] flex-shrink-0" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
									<span class="font-semibold">Artemisium Web TV On Air</span>
								</li>
								<li class="flex items-center gap-4 bg-white p-4 rounded-2xl shadow-sm">
									<svg class="text-[var(--color-accent)] flex-shrink-0" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
									<span class="font-semibold">Iniziative di Solidarietà</span>
								</li>
								<li class="flex items-center gap-4 bg-white p-4 rounded-2xl shadow-sm">
									<svg class="text-[var(--color-accent)] flex-shrink-0" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
									<span class="font-semibold">Mantenimento Tradizioni Locali</span>
								</li>
							</ul>
						</div>
					</div>

					<!-- Team/Staff Grid -->
                    <?php
                    $team_query = new WP_Query([
                        'post_type'      => 'santagatesi_team',
                        'posts_per_page' => -1,
                        'post_status'    => 'publish',
                        'meta_query'     => [
                            [
                                'key'     => '_visibilita_frontend',
                                'value'   => '1',
                                'compare' => '='
                            ]
                        ]
                    ]);

                    if ( $team_query->have_posts() ) :
                    ?>
                        <div class="mt-20">
                            <header class="text-center mb-16">
                                <h2 class="text-4xl font-bold text-[var(--color-primary)] font-display">La Redazione e lo Staff</h2>
                                <p class="text-[var(--color-on-surface-muted)] mt-4 font-body text-lg">Le persone dietro il progetto Artemisium e l'associazione.</p>
                            </header>

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
                                <?php while ( $team_query->have_posts() ) : $team_query->the_post();
                                    $ruolo = get_post_meta( get_the_ID(), '_team_ruolo', true );
                                ?>
                                    <div class="tonal-panel text-center bg-white transition-transform hover:-translate-y-2 p-8 rounded-2xl shadow-[var(--shadow-ambient)]">
                                        <div class="w-32 h-32 mx-auto rounded-full bg-[var(--color-surface-container-low)] mb-6 overflow-hidden shadow-lg border-4 border-white">
                                            <?php if ( has_post_thumbnail() ) : ?>
                                                <?php the_post_thumbnail( 'medium', ['class' => 'w-full h-full object-cover'] ); ?>
                                            <?php else : ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 24 24" fill="none" stroke="#0A2540" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="padding: 20px; opacity: 0.5;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                            <?php endif; ?>
                                        </div>
                                        <h3 class="text-2xl font-bold text-[var(--color-primary)] font-display"><?php the_title(); ?></h3>
                                        <?php if ( $ruolo ) : ?>
                                            <p class="text-[var(--color-accent)] font-semibold text-sm mt-2 uppercase tracking-wider font-body"><?php echo esc_html( $ruolo ); ?></p>
                                        <?php endif; ?>
                                    </div>
                                <?php endwhile; wp_reset_postdata(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

				</article>
			<?php endwhile; ?>
		</div>
	</section>

</main>

<?php
get_footer();
