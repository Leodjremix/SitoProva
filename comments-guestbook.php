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
		<h2 class="comments-title text-3xl font-extrabold text-white mb-10 flex items-center gap-4">
			<span class="bg-blue-600/20 p-3 rounded-xl border border-blue-500/30">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#60a5fa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
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

		<!-- Griglia Saluti -->
		<ol class="comment-list grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
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
			<p class="no-comments text-rose-300 bg-rose-500/10 p-4 rounded-xl text-center font-medium border border-rose-500/30 mb-8"><?php esc_html_e( 'Il Guestbook è temporaneamente chiuso.', 'santagatesi' ); ?></p>
			<?php
		endif;

	endif; // Check for have_comments().

	// Custom Comment Form
	$commenter = wp_get_current_commenter();
	$req       = get_option( 'require_name_email' );
	$html_req  = ( $req ? " required='required'" : '' );

	// Override default styling
	$args = array(
		'title_reply'          => '<span class="text-3xl font-extrabold text-white flex items-center gap-3"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#60a5fa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>Lascia il tuo messaggio</span>',
		'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title mb-8 border-t border-white/10 pt-16 mt-8">',
		'title_reply_after'    => '</h3>',
		'cancel_reply_link'    => __( 'Annulla', 'santagatesi' ),
		'label_submit'         => __( 'Invia Saluto', 'santagatesi' ),
		'class_submit'         => 'submit btn bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold py-4 px-8 rounded-full shadow-lg transition-all w-full md:w-auto',
		'class_form'           => 'comment-form flex flex-col gap-6',
		'comment_notes_before' => '<p class="comment-notes text-blue-200 text-sm mb-6"><span id="email-notes">' . __( 'Il tuo indirizzo email non sarà pubblicato.', 'santagatesi' ) . '</span></p>',

		// Campi Testo
		'comment_field'        => '<div class="comment-form-comment relative"><label for="comment" class="sr-only">' . _x( 'Messaggio', 'noun', 'santagatesi' ) . '</label><textarea id="comment" name="comment" cols="45" rows="5" maxlength="65525" required="required" placeholder="Scrivi il tuo messaggio qui..." class="w-full bg-[#0f172a]/50 border border-slate-600 text-white rounded-xl p-4 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition resize-y placeholder-slate-400 font-medium"></textarea></div>',

		// Campi Input
		'fields'               => array(
			'author' => '<div class="grid grid-cols-1 md:grid-cols-2 gap-6"><div class="comment-form-author relative">' .
						'<label for="author" class="sr-only">' . __( 'Nome', 'santagatesi' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label>' .
						'<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" maxlength="245"' . $html_req . ' placeholder="Nome *" class="w-full bg-[#0f172a]/50 border border-slate-600 text-white rounded-xl p-4 focus:ring-2 focus:ring-blue-500 outline-none transition placeholder-slate-400 font-medium" /></div>',
			'email'  => '<div class="comment-form-email relative">' .
						'<label for="email" class="sr-only">' . __( 'Email', 'santagatesi' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label>' .
						'<input id="email" name="email" type="email" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" maxlength="100" aria-describedby="email-notes"' . $html_req . ' placeholder="Email *" class="w-full bg-[#0f172a]/50 border border-slate-600 text-white rounded-xl p-4 focus:ring-2 focus:ring-blue-500 outline-none transition placeholder-slate-400 font-medium" /></div></div>',
			'url'    => '<div class="comment-form-url hidden">' .
						'<label for="url">' . __( 'Website', 'santagatesi' ) . '</label>' .
						'<input id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" maxlength="200" /></div>',
		),
	);

	comment_form( $args );
	?>

</div><!-- #comments -->