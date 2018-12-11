<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Business Professional
 */

if ( ! function_exists( 'business_professional_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function business_professional_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

		if ( is_single() ) {
			$posted_on = sprintf( esc_html__( 'Posted on %1$s', 'business-professional' ),
							'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
						);
		}
		elseif ( is_sticky() ) {
			$posted_on = '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . esc_html__( 'Featured', 'business-professional' ) . '</a>';
		}
		else {
			$posted_on = '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>';
		}

		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'business-professional' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span><span class="byline">' . $byline . '</span>'; // WPCS: XSS OK.

	}
endif;

if ( ! function_exists( 'business_professional_categories' ) ) :
/**
 * Prints HTML for categories
 */
function business_professional_categories( $separator = true ) {
	if ( $separator ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'business-professional' ) );
	} else {
		$categories_list = get_the_category_list( ' ' );
	}

	if ( $categories_list ) {
		echo '<span class="cat-links"><span class="screen-reader-text">' . esc_html__( 'Posted in', 'business-professional' ) . '</span> ' . $categories_list . '</span>'; // WPCS: XSS OK.
	}
}
endif;

if ( ! function_exists( 'business_professional_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function business_professional_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list();
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				echo( '<span class="tags-links"><span class="screen-reader-text">' . esc_html__( 'Tagged', 'business-professional' ) . '</span>' . $tags_list ) . '</span>'; // WPCS: XSS OK.
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'business-professional' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'business-professional' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

if ( ! function_exists( 'business_professional_comment' ) ) :

	function business_professional_comment($comment, $args, $depth) {
	if ( 'div' === $args['style'] ) {
		$tag       = 'div';
		$add_below = 'comment';
	} else {
		$tag       = 'li';
		$add_below = 'div-comment';
	}?>
	<<?php echo $tag; ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?> id="comment-<?php comment_ID() ?>"><?php
	if ( 'div' != $args['style'] ) { ?>
		<div id="div-comment-<?php comment_ID() ?>" class="comment-body"><?php
	} ?>
		<div class="comment-author vcard"><?php
			if ( $args['avatar_size'] != 0 ) {
				echo get_avatar( $comment, $args['avatar_size'] );
			} ?>
			<cite class="fn"><?php echo get_comment_author_link(); ?></cite>
		</div><?php
		if ( $comment->comment_approved == '0' ) { ?>
			<em class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'business-professional' ); ?><?php
		} ?>
		<div class="comment-meta commentmetadata">
			<a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>"><?php
				/* translators: 1: date, 2: time */
				printf(
					__('%1$s at %2$s'),
					get_comment_date(),
					get_comment_time()
				); ?>
			</a><?php
			edit_comment_link( esc_html__( 'Edit', 'business-professional' ), '  ', '' ); ?>
		</div>

		<?php comment_text(); ?>

		<div class="reply"><?php
				comment_reply_link(
					array_merge(
						$args,
						array(
							'add_below' => $add_below,
							'depth'     => $depth,
							'max_depth' => $args['max_depth']
						)
					)
				); ?>
		</div><?php
	if ( 'div' != $args['style'] ) : ?>
		</div><?php
	endif;
}
endif;

if ( ! function_exists( 'business_professional_social_menu' ) ) :
	/**
	 * Jetpack social menu.
	 */
	function business_professional_social_menu() {
		if ( function_exists( 'jetpack_social_menu' ) ) {
			jetpack_social_menu();
		}
	}
endif;

if ( ! function_exists( 'business_professional_author_bio' ) ) :
	/**
	 * Return early if Author Bio is not available.
	 */
	function business_professional_author_bio() {
		if ( function_exists( 'jetpack_author_bio' ) ) {
			jetpack_author_bio();
		}
	}
endif;

if ( ! function_exists( 'business_professional_author_bio_avatar_size' ) ) :
	/**
	 * Author Bio Avatar Size.
	 */
	function business_professional_author_bio_avatar_size() {
		return 60; // in px
	}
endif;

add_filter( 'jetpack_author_bio_avatar_size', 'business_professional_author_bio_avatar_size' );
