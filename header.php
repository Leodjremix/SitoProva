<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package santagatesi
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class( 'antialiased' ); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'santagatesi' ); ?></a>

	<!-- Sticky Header -->
	<header id="masthead" class="site-header fixed top-0 left-0 w-full z-50 transition-all duration-300">
		<div class="header-inner bg-white/90 backdrop-blur-md border-b border-gray-200/50 shadow-sm transition-all duration-300">
			<div class="container mx-auto px-4 h-20 flex justify-between items-center">

				<!-- Logo / Site Branding -->
				<div class="site-branding flex-shrink-0 relative z-50">
					<?php
					if ( has_custom_logo() ) :
						the_custom_logo();
					else :
						?>
						<h1 class="site-title text-2xl font-extrabold tracking-tight text-[#003d6c] hover:text-[#0066cc] transition-colors font-display">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
						</h1>
					<?php endif; ?>
				</div>

				<!-- Desktop Navigation -->
				<nav id="site-navigation" class="main-navigation hidden md:flex items-center space-x-2">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_id'        => 'primary-menu',
							'container'      => false,
							'fallback_cb'    => false,
							'menu_class'     => 'flex items-center space-x-2 m-0 p-0 list-none',
						)
					);
					?>
				</nav>

				<!-- Hamburger Menu Button (Mobile) -->
				<button id="mobile-menu-toggle" class="md:hidden relative z-50 w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100 transition-colors focus:outline-none focus:ring-2 focus:ring-[#003d6c]/50" aria-label="Toggle Menu" aria-expanded="false" aria-controls="mobile-navigation">
					<div class="hamburger-icon w-6 h-5 relative flex flex-col justify-between items-center">
						<span class="line line-1 w-full h-[2px] bg-[#003d6c] rounded-full transition-all duration-300 origin-left"></span>
						<span class="line line-2 w-full h-[2px] bg-[#003d6c] rounded-full transition-all duration-300"></span>
						<span class="line line-3 w-full h-[2px] bg-[#003d6c] rounded-full transition-all duration-300 origin-left"></span>
					</div>
				</button>

			</div>
		</div>

		<!-- Mobile Navigation Panel (Off-Canvas/Fullscreen) -->
		<div id="mobile-navigation" class="fixed inset-0 bg-[#f7fafe]/95 backdrop-blur-xl z-40 transform translate-x-full transition-transform duration-500 ease-in-out md:hidden overflow-y-auto">
			<div class="container px-4 py-24 min-h-screen flex flex-col justify-center">
				<nav class="mobile-nav-wrapper">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_id'        => 'mobile-primary-menu',
							'container'      => false,
							'fallback_cb'    => false,
							'menu_class'     => 'flex flex-col space-y-6 text-center m-0 p-0 list-none text-2xl font-bold font-display',
						)
					);
					?>
				</nav>

				<!-- Optional Mobile Socials/CTA -->
				<div class="mt-16 text-center border-t border-gray-200 pt-8 opacity-0 translate-y-4 transition-all duration-500 delay-300" id="mobile-nav-extras">
					<a href="https://www.facebook.com/groups/artemisiumwebtv/" target="_blank" rel="noopener noreferrer" class="text-[#0066cc] hover:text-[#003d6c] transition-colors inline-block mx-2">
						<svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor"><path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/></svg>
					</a>
				</div>
			</div>
		</div>
	</header><!-- #masthead -->

	<script>
	document.addEventListener('DOMContentLoaded', function() {
		const toggleBtn = document.getElementById('mobile-menu-toggle');
		const mobileNav = document.getElementById('mobile-navigation');
		const body = document.body;

		// Lines inside the hamburger for animation
		const line1 = toggleBtn.querySelector('.line-1');
		const line2 = toggleBtn.querySelector('.line-2');
		const line3 = toggleBtn.querySelector('.line-3');

		// Extras fade-in element
		const navExtras = document.getElementById('mobile-nav-extras');

		let isMenuOpen = false;

		function toggleMenu() {
			isMenuOpen = !isMenuOpen;

			// Accessibility
			toggleBtn.setAttribute('aria-expanded', isMenuOpen);

			if (isMenuOpen) {
				// Open logic
				mobileNav.classList.remove('translate-x-full');
				mobileNav.classList.add('translate-x-0');
				body.style.overflow = 'hidden'; // Prevent scrolling

				// Animate Hamburger to X
				line1.classList.add('rotate-45', 'translate-x-[2px]', '-translate-y-[1px]');
				line2.classList.add('opacity-0');
				line3.classList.add('-rotate-45', 'translate-x-[2px]', 'translate-y-[1px]');

				// Staggered fade in for extras
				setTimeout(() => {
					navExtras.classList.remove('opacity-0', 'translate-y-4');
					navExtras.classList.add('opacity-100', 'translate-y-0');
				}, 300);

			} else {
				// Close logic
				mobileNav.classList.remove('translate-x-0');
				mobileNav.classList.add('translate-x-full');
				body.style.overflow = ''; // Restore scrolling

				// Revert Hamburger
				line1.classList.remove('rotate-45', 'translate-x-[2px]', '-translate-y-[1px]');
				line2.classList.remove('opacity-0');
				line3.classList.remove('-rotate-45', 'translate-x-[2px]', 'translate-y-[1px]');

				// Reset extras
				navExtras.classList.remove('opacity-100', 'translate-y-0');
				navExtras.classList.add('opacity-0', 'translate-y-4');
			}
		}

		toggleBtn.addEventListener('click', toggleMenu);

		// Sticky Header Logic
		const headerInner = document.querySelector('.header-inner');

		window.addEventListener('scroll', () => {
			if (window.scrollY > 50) {
				headerInner.classList.add('shadow-md', 'bg-white/95');
				headerInner.classList.remove('shadow-sm', 'bg-white/90');
			} else {
				headerInner.classList.remove('shadow-md', 'bg-white/95');
				headerInner.classList.add('shadow-sm', 'bg-white/90');
			}
		});
	});
	</script>
