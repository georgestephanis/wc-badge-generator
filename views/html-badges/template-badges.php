<?php

namespace CampTix\Badge_Generator\HTML;
defined( 'WPINC' ) or die();

/*
 * template-loader.php includes this file in the global scope, which is ugly. So, include this again from a
 * function, so that we get a nice, clean, local scope.
 */
if ( isset( $template ) && __FILE__ == $template ) {
	render_badges_template();
	return;
}

/*
 *
*/

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<title><?php _e( 'CampTix HTML Badges', 'wordcamporg' ); ?></title>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<?php wp_head(); ?>
</head>

<body>
	<?php
		if ( empty( $attendees ) ) :

			_e( 'No attendees were found. Please try again after registration has opened.', 'wordcamporg' );

		else :

			foreach ( $attendees as $attendee_id ) :
				$attendee_data = get_attendee_data( $attendee_id );
				
				?>

				<article class="attendee <?php echo esc_attr( $attendee_data['ticket_slug'] ); ?>">
					<section class="back">
						<?php require( __DIR__ . '/template-part-badge-contents.php' ); ?>
					</section>

					<section class="front">
						<div class="holepunch">&#9421;</div>

						<?php require( __DIR__ . '/template-part-badge-contents.php' ); ?>
					</section>
				</article>
			<?php endforeach; ?>
		<?php endif; ?>

	<?php wp_footer(); ?>
</body>
</html>
