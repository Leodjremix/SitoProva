<?php
/**
 * The template for displaying Guestbook Comments
 *
 * The area of the page that contains both current comments
 * and the comment form, styled explicitly for the guestbook.
 *
 * @package santagatesi
 */

/*
 * Se questo post è protetto da password, non mostriamo nulla.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area relative z-10 pt-8">

	<?php
	// You can start editing here -- including this check!
	if ( have_comments() ) :
		?>
		<h2 class="comments-title text-4xl font-extrabold text-[var(--color-primary)] mb-12 flex items-center gap-4 font-display">
			<span class="bg-[var(--color-surface)] p-3 rounded-xl border border-[var(--color-surface-container-low)] shadow-sm">
				<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[var(--color-accent)]"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
			</span>
			<?php
			$santagatesi_comment_count = get_comments_number();
			if ( '1' === $santagatesi_comment_count ) {
				printf(
					/* translators: 1: title. */
					esc_html__( '1 Saluto Ricevuto', 'santagatesi' )
				);
			} else {
				printf(
					/* translators: 1: comment count number, 2: title. */
					esc_html( _nx( '%1$s Saluto', '%1$s Saluti Ricevuti', $santagatesi_comment_count, 'comments title', 'santagatesi' ) ),
					number_format_i18n( $santagatesi_comment_count )
				);
			}
			?>
		</h2><!-- .comments-title -->

		<?php the_comments_navigation(); ?>

		<!-- Griglia Saluti (Masonry) -->
		<ol class="comment-list columns-1 md:columns-2 lg:columns-3 gap-6 space-y-6 mb-20 relative">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 64,
					'callback'    => 'santagatesi_guestbook_comment_format', // Defined in functions.php
				)
			);
			?>
		</ol><!-- .comment-list -->

		<?php
		the_comments_navigation();

		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() ) :
			?>
			<p class="no-comments text-rose-600 bg-rose-50 p-6 rounded-2xl text-center font-medium border border-rose-100 mb-10 shadow-sm font-body"><?php esc_html_e( 'Il Guestbook è temporaneamente chiuso.', 'santagatesi' ); ?></p>
			<?php
		endif;

	endif; // Check for have_comments().

	// Custom Comment Form
	$commenter = wp_get_current_commenter();
	$req       = get_option( 'require_name_email' );
	$html_req  = ( $req ? " required='required'" : '' );

	// Override default styling
	$args = array(
		'title_reply'          => '<span class="text-4xl font-extrabold text-[var(--color-primary)] flex items-center gap-4 font-display"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[var(--color-accent)]"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>Lascia il tuo messaggio</span>',
		'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title mb-10 border-t border-[var(--color-surface-container-low)] pt-16 mt-12">',
		'title_reply_after'    => '</h3>',
		'cancel_reply_link'    => __( 'Annulla', 'santagatesi' ),
		'label_submit'         => __( 'Invia Saluto', 'santagatesi' ),
		'class_submit'         => 'submit btn btn-primary text-lg px-10 py-4 shadow-lg w-full md:w-auto mt-4 font-body',
		'class_form'           => 'comment-form flex flex-col gap-6',
		'comment_notes_before' => '<p class="comment-notes text-[var(--color-on-surface-muted)] text-sm mb-8 font-body"><span id="email-notes">' . __( 'Il tuo indirizzo email non sarà pubblicato.', 'santagatesi' ) . '</span></p>',

		// Campi Testo
		'comment_field'        => '<div class="comment-form-comment relative col-span-1 md:col-span-2"><label for="comment" class="sr-only">' . _x( 'Messaggio', 'noun', 'santagatesi' ) . '</label><textarea id="comment" name="comment" cols="45" rows="6" maxlength="65525" required="required" placeholder="Il tuo pensiero, un ricordo, un saluto..." class="w-full bg-yellow-50/50 backdrop-blur-sm border border-yellow-200 text-gray-800 rounded-2xl p-6 focus:ring-4 focus:ring-yellow-400/30 outline-none transition-all resize-y placeholder-gray-500 font-body shadow-inner text-lg focus:bg-yellow-50 hover:shadow-md"></textarea><div class="absolute bottom-4 right-6 text-gray-400"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg></div></div>',

		// Campi Input
		'fields'               => array(
			'author' => '<div class="grid grid-cols-1 md:grid-cols-2 gap-8 col-span-1 md:col-span-2"><div class="comment-form-author relative group">' .
						'<label for="author" class="sr-only">' . __( 'Nome', 'santagatesi' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label>' .
						'<div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-[var(--color-accent)] transition-colors"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg></div>' .
						'<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" maxlength="245"' . $html_req . ' placeholder="Il tuo Nome *" class="w-full pl-12 bg-white border border-gray-200 text-gray-800 rounded-xl p-4 focus:ring-4 focus:ring-[var(--color-accent)]/20 outline-none transition-all placeholder-gray-400 font-body shadow-sm text-lg hover:border-gray-300 focus:border-[var(--color-accent)]" /></div>',
			'email'  => '<div class="comment-form-email relative group">' .
						'<label for="email" class="sr-only">' . __( 'Email', 'santagatesi' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label>' .
						'<div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-[var(--color-accent)] transition-colors"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg></div>' .
						'<input id="email" name="email" type="email" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" maxlength="100" aria-describedby="email-notes"' . $html_req . ' placeholder="Indirizzo Email *" class="w-full pl-12 bg-white border border-gray-200 text-gray-800 rounded-xl p-4 focus:ring-4 focus:ring-[var(--color-accent)]/20 outline-none transition-all placeholder-gray-400 font-body shadow-sm text-lg hover:border-gray-300 focus:border-[var(--color-accent)]" /></div></div>',
			'url'    => '<div class="comment-form-url hidden">' .
						'<label for="url">' . __( 'Website', 'santagatesi' ) . '</label>' .
						'<input id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" maxlength="200" /></div>',
		),
	);

	echo '<div class="bg-gray-50/50 p-8 rounded-3xl border border-gray-100 shadow-sm">';
	comment_form( $args );
	echo '</div>';
	?>

</div><!-- #comments -->