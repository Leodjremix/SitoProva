<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package santagatesi
 */

?>
	<footer id="colophon" class="site-footer">
		<div class="container">
			<div class="footer-content flex flex-col items-center gap-4">
				<div class="footer-socials flex gap-4">
					<a href="https://www.facebook.com/groups/artemisiumwebtv/" target="_blank" rel="noopener noreferrer" aria-label="Facebook Group">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
							<path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/>
						</svg>
					</a>
					<a href="https://www.youtube.com/user/nardino1000" target="_blank" rel="noopener noreferrer" aria-label="YouTube Channel">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
							<path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
						</svg>
					</a>
				</div>

				<div class="footer-info">
					<p>&copy; <?php echo date( 'Y' ); ?> Santagatesi nel Mondo. Tutti i diritti riservati.</p>
					<p><small>Associazione di Promozione Sociale - Via G. Garibaldi 44, 71028 Sant'Agata di Puglia (FG)</small></p>
				</div>
			</div>
		</div><!-- .container -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
