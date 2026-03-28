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
		<div class="header-inner bg-[var(--color-surface-container-lowest)] backdrop-blur-md border-b border-[var(--color-surface-container-low)] shadow-sm transition-all duration-300 py-2">
			<div class="container mx-auto px-4 flex justify-between items-center">

				<!-- Logo / Site Branding -->
				<div class="site-branding flex-shrink-0 relative z-50">
					<?php
					if ( has_custom_logo() ) :
						the_custom_logo();
					else :
						?>
						<div class="site-title text-2xl md:text-3xl font-extrabold tracking-tight text-[var(--color-primary)] hover:text-[var(--color-accent)] transition-colors font-display py-2">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
						</div>
					<?php endif; ?>
				</div>

				<!-- Desktop Navigation (Mega Menu Support) -->
				<nav id="site-navigation" class="main-navigation hidden lg:flex items-center space-x-1" aria-label="Menu Principale Desktop">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_id'        => 'primary-menu',
							'container'      => false,
							'fallback_cb'    => false,
							'menu_class'     => 'flex items-center space-x-1 m-0 p-0 list-none font-body text-[16px]',
						)
					);
					?>
				</nav>

				<!-- Hamburger Menu Button (Mobile/Tablet) -->
				<button id="mobile-menu-toggle" class="lg:hidden relative z-50 w-12 h-12 flex items-center justify-center rounded-full bg-[var(--color-surface-container-low)] hover:bg-[var(--color-surface)] transition-colors focus:outline-none focus:ring-4 focus:ring-[var(--color-accent)]/50" aria-label="Apri Menu Navigazione" aria-expanded="false" aria-controls="mobile-navigation">
					<div class="hamburger-icon w-6 h-5 relative flex flex-col justify-between items-center">
						<span class="line line-1 w-full h-[3px] rounded-full transition-all duration-300 origin-left"></span>
						<span class="line line-2 w-full h-[3px] rounded-full transition-all duration-300"></span>
						<span class="line line-3 w-full h-[3px] rounded-full transition-all duration-300 origin-left"></span>
					</div>
				</button>

			</div>
		</div>

		<!-- Mobile Navigation Panel (Off-Canvas/Fullscreen) -->
		<div id="mobile-navigation" class="fixed inset-0 bg-[var(--color-surface-container-lowest)] backdrop-blur-xl z-40 transform translate-x-full transition-transform duration-500 ease-in-out lg:hidden overflow-y-auto" role="dialog" aria-modal="true" aria-label="Menu Navigazione Mobile">
			<div class="container px-4 py-28 min-h-screen flex flex-col">
				<nav class="mobile-nav-wrapper w-full">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_id'        => 'mobile-primary-menu',
							'container'      => false,
							'fallback_cb'    => false,
							'menu_class'     => 'flex flex-col m-0 p-0 list-none',
						)
					);
					?>
				</nav>

				<!-- Mobile Socials/CTA -->
				<div class="mt-auto text-center border-t border-[var(--color-surface-container-low)] pt-8 pb-12 opacity-0 translate-y-4 transition-all duration-500 delay-300" id="mobile-nav-extras">
                    <p class="text-[var(--color-on-surface-muted)] text-sm mb-4 font-body">Seguici sui nostri canali</p>
					<a href="https://www.facebook.com/groups/artemisiumwebtv/" target="_blank" rel="noopener noreferrer" class="text-[#1877F2] hover:text-[var(--color-primary)] transition-colors inline-block mx-4 transform hover:scale-110" aria-label="Visita il nostro Gruppo Facebook">
						<svg width="40" height="40" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/></svg>
					</a>
                    <a href="https://www.youtube.com/user/nardino1000" target="_blank" rel="noopener noreferrer" class="text-[#FF0000] hover:text-[var(--color-primary)] transition-colors inline-block mx-4 transform hover:scale-110" aria-label="Visita il nostro Canale YouTube">
						<svg width="40" height="40" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
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
				line1.classList.add('rotate-45', 'translate-x-[2px]', '-translate-y-[2px]');
				line2.classList.add('opacity-0');
				line3.classList.add('-rotate-45', 'translate-x-[2px]', 'translate-y-[2px]');

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
				line1.classList.remove('rotate-45', 'translate-x-[2px]', '-translate-y-[2px]');
				line2.classList.remove('opacity-0');
				line3.classList.remove('-rotate-45', 'translate-x-[2px]', 'translate-y-[2px]');

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
				headerInner.classList.add('shadow-md');
				headerInner.classList.remove('shadow-sm', 'py-2');
			} else {
				headerInner.classList.remove('shadow-md');
				headerInner.classList.add('shadow-sm', 'py-2');
			}
		});

        // Accordion Dropdown toggle for Mobile (Vanilla JS, smooth height transition)
        const mobileMenuItemsWithChildren = document.querySelectorAll('.mobile-nav-wrapper .menu-item-has-children > a');

        mobileMenuItemsWithChildren.forEach(item => {
            // Aggiungiamo un'icona freccia
            const arrow = document.createElement('span');
            // Using an SVG chevron for a cleaner look
            arrow.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>';
            arrow.className = 'float-right transition-transform duration-300 opacity-70';
            item.appendChild(arrow);

            item.addEventListener('click', function(e) {
                e.preventDefault();
                const parent = this.parentElement;
                const subMenu = parent.querySelector('.sub-menu');

                if (subMenu) {
                    const isExpanded = subMenu.classList.contains('is-open');

                    // Close all other submenus at same level (Accordion logic)
                    const siblings = parent.parentElement.children;
                    for (let sibling of siblings) {
                        if (sibling !== parent) {
                            const siblingSub = sibling.querySelector('.sub-menu');
                            const siblingArrow = sibling.querySelector('a span');
                            if (siblingSub && siblingSub.classList.contains('is-open')) {
                                siblingSub.style.maxHeight = null;
                                siblingSub.classList.remove('is-open');
                                if (siblingArrow) siblingArrow.style.transform = 'rotate(0deg)';
                            }
                        }
                    }

                    // Toggle current smoothly using max-height
                    if (isExpanded) {
                        subMenu.style.maxHeight = null;
                        subMenu.classList.remove('is-open');
                        arrow.style.transform = 'rotate(0deg)';
                    } else {
                        subMenu.classList.add('is-open');
                        subMenu.style.maxHeight = subMenu.scrollHeight + "px";
                        arrow.style.transform = 'rotate(180deg)';

                        // Assicuriamoci che se il sottomenu ha a sua volta sottomenu (nested),
                        // l'altezza massima si adatti ricalcolandola
                        setTimeout(() => {
                            if (subMenu.classList.contains('is-open')) {
                                subMenu.style.maxHeight = 'none'; // Rimuove il limite per permettere espansioni interne
                            }
                        }, 300); // 300ms matches the CSS transition duration
                    }
                }
            });
        });
	});
	</script>

    <style>
        /* Stili avanzati per il menu mobile: override forzati per bypassare Astra e Cache */
        .mobile-nav-wrapper .menu-item > a {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            padding: 1rem 0 !important;
            font-size: 1.25rem !important;
            font-weight: 600 !important;
            color: var(--color-on-surface-muted) !important;
            border-bottom: 1px solid var(--color-surface-container-low) !important;
            transition: color 0.3s ease !important;
        }

        .mobile-nav-wrapper .menu-item > a:hover {
            color: var(--color-primary) !important;
        }

        /* Forza Astra a non bloccare il sottomenu e a renderlo gestibile dal nostro max-height */
        .mobile-nav-wrapper ul.sub-menu {
            display: block !important; /* Overrides Astra's display:none */
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out, padding 0.3s ease-out, margin 0.3s ease-out !important;
            padding-left: 1rem !important;
            background-color: var(--color-surface-container-low) !important;
            border-radius: 0 0 var(--radius-md) var(--radius-md) !important;
            margin: 0 !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }

        .mobile-nav-wrapper ul.sub-menu.is-open {
            /* max-height is set by JS inline, but we ensure padding/margin expand nicely */
            margin-bottom: 0.5rem !important;
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
        }

        .mobile-nav-wrapper ul.sub-menu a {
            font-size: 1.1rem !important;
            padding: 0.75rem 1rem !important;
            color: var(--color-on-surface-muted) !important;
            font-family: var(--font-body) !important;
            font-weight: 500 !important;
            border-bottom: none !important;
            border-left: 2px solid transparent !important;
            display: block !important;
        }

        .mobile-nav-wrapper ul.sub-menu a:hover {
            color: var(--color-accent) !important;
            border-left-color: var(--color-accent) !important;
            background-color: transparent !important;
        }
    </style>
