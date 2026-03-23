<?php
/**
 * Template part for displaying the Webcams section
 *
 * @package santagatesi
 */

// The URLs are taken from the current website's iframes.
$webcam_1_url = 'https://www.liveincam.com/?w=588'; // Vico V. Emanuele
$webcam_2_url = 'https://www.skylinewebcams.com/webcam/italia/puglia/foggia/santagata-di-puglia.html?w=453'; // Piazza Toni Santagata
?>

<section id="webcam" class="section">
	<div class="container">
		<header class="section-header text-center mb-12">
			<h2 class="text-4xl font-bold mb-4">Webcam H24</h2>
			<p class="text-lg text-color-on-surface-muted font-body">Guarda Sant'Agata di Puglia in diretta streaming.</p>
		</header>

		<div class="grid grid-cols-1 md:grid-cols-2 gap-8">

			<!-- Webcam 1 -->
			<div class="tonal-panel text-center">
				<h3 class="text-2xl mb-6">Vico V. Emanuele</h3>
				<div class="webcam-container">
					<iframe src="<?php echo esc_url( $webcam_1_url ); ?>" allowfullscreen title="Webcam Vico V. Emanuele"></iframe>
				</div>
			</div>

			<!-- Webcam 2 -->
			<div class="tonal-panel text-center">
				<h3 class="text-2xl mb-6">Piazza Toni Santagata</h3>
				<div class="webcam-container">
					<iframe src="<?php echo esc_url( $webcam_2_url ); ?>" allowfullscreen title="Webcam Piazza Toni Santagata"></iframe>
				</div>
			</div>

		</div>
	</div>
</section>
