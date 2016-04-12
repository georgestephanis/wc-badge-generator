<?php

namespace CampTix\Badge_Generator\HTML;
defined( 'WPINC' ) or die();

?><!DOCTYPE html>
<html id="camptix-badge-generator" <?php language_attributes(); ?>>

<head>
	<title><?php _e( 'CampTix HTML Badges', 'wordcamporg' ); ?></title>

	<meta charset="<?php bloginfo( 'charset' ); ?>">

	<?php
		/** @var $camptix \CampTix_Plugin */
		wp_head();

		/*
		 * todo
		 *
		 * call this view from a function so vars aren't in global space - todo high
		 * create vars in that function so this is a pure view
		 * 
		 * go through every line of this
		 * show site logo, etc. maybe reuse some of default_single_og_image()?
		 * more stuff from twentysixteen?
		 * maybe break this up into differnt parts of name them like customizer-section.php, etc
		*/
	?>
</head>

<body>
	<?php
		$attendees = get_posts( array(
			'post_type'      => 'tix_attendee',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'fields'         => 'ids', // ! no post objects
			'cache_results'  => false,
		) );

		if ( ! is_array( $attendees ) || count( $attendees ) < 1 ) {
			echo esc_html__( 'No attendees found to make badges for.' ) . "\r\n</body>\r\n</html>";
			return;
		}
	?>

	<?php
	// Disable object cache for prepared metadata.
	$camptix->filter_post_meta = $camptix->prepare_metadata_for( $attendees );

	foreach ( $attendees as $attendee_id ) :
		$first   = get_post_meta( $attendee_id, 'tix_first_name', true );
		$last    = get_post_meta( $attendee_id, 'tix_last_name', true );
		$name    = $camptix->format_name_string( '<span class="tix-first">%first%</span> <span class="tix-last">%last%</span>', esc_html( $first ), esc_html( $last ) );
		$email   = get_post_meta( $attendee_id, 'tix_email', true );
		$avatar  = get_avatar_url( $email, array( 'size' => 600 ) );
		?>

		<article class="attendee">
			<section class="back">
				<header>
					<?php if ( has_custom_logo() ) : ?>
						<?php the_custom_logo(); ?>
					<?php else : ?>
						<h1><?php bloginfo( 'name' ); ?></h1>
					<?php endif; ?>
				</header>

				<figure>
					<img src="<?php echo esc_url( $avatar ); ?>" /> <!-- todo add alt -->
					<figcaption>
						<h3 class="name"><?php echo $name; /* already escaped above */ ?></h3>
					</figcaption>
				</figure>
			</section>

			<section class="front">
				<div class="holepunch">&#9421;</div>
				<header>
					<?php if ( has_custom_logo() ) : ?>
						<?php the_custom_logo(); ?>
					<?php else : ?>
						<h1><?php bloginfo( 'name' ); ?></h1>
					<?php endif; ?>
				</header>

				<figure>
					<img src="<?php echo esc_url( $avatar ); ?>" /> <!-- todo add alt -->
					<figcaption>
						<h3 class="name"><?php echo $name; /* already escaped above */ ?></h3>
					</figcaption>
				</figure>
			</section>
		</article>
	<?php endforeach; ?>

	<?php wp_footer(); ?>
</body>
</html>
