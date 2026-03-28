<?php
/**
 * The front page template file
 *
 * This is the template that displays the custom homepage.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package santagatesi
 */

get_header();
?>

	<main id="primary" class="site-main">

		<?php
		// 1. Hero Section (Parallax & Glassmorphism)
		get_template_part( 'template-parts/hero-section' );

		// 2. Webcams Section (Grid)
		get_template_part( 'template-parts/webcam-section' );

		// 3. News Section (Latest 3 posts)
		get_template_part( 'template-parts/news-section' );

        // 3.5. Donation Section
        get_template_part( 'template-parts/donation-section' );

		// 4. YouTube Video Gallery Section
		get_template_part( 'template-parts/youtube-section' );

		// 5. Facebook CTA Section
		get_template_part( 'template-parts/facebook-section' );
		?>

	</main><!-- #primary -->

<?php
get_footer();
