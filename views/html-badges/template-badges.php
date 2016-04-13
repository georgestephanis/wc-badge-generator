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
 * todo
 *
 * go through every line of this
 * show 4.5 site logo, etc. header image? maybe reuse some of default_single_og_image()?
 *
 * add to documentation  that can create different badges for speakers, sponsors, etc by targeting `attendee.{ticket_slug}`
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
				$first  = get_post_meta( $attendee_id, 'tix_first_name', true );
				$last   = get_post_meta( $attendee_id, 'tix_last_name',  true );
				$name   = $camptix->format_name_string( '<span class="tix-first">%first%</span> <span class="tix-last">%last%</span>', esc_html( $first ), esc_html( $last ) );    // todo escape late
				$email  = get_post_meta( $attendee_id, 'tix_email', true );
				$avatar = get_avatar_url( $email, array( 'size' => 600 ) );
				$ticket = get_post( get_post_meta( $attendee_id, 'tix_ticket_id', true ) );

				?>

				<article class="attendee <?php echo esc_attr( $ticket->post_name ); ?>">
					<section class="back">
						<header>
							<?php if ( has_custom_logo() ) : ?>
								<?php the_custom_logo(); ?>
							<?php else : ?>
								<h1><?php bloginfo( 'name' ); ?></h1>
							<?php endif; ?>
						</header>

						<figure>
							<img src="<?php echo esc_url( $avatar ); ?>" alt="<?php echo esc_attr( $first .' '. $last ); ?>" />
							<figcaption>
								<h3 class="name"><?php echo $name; /* already escaped above */ ?></h3>
							</figcaption>
						</figure>
					</section>

					<section class="front">
						<div class="holepunch">&#9421;</div>

						<!-- todo just reuse above instead of duplicating? -->
						<header>
							<?php if ( has_custom_logo() ) : ?>
								<?php the_custom_logo(); ?>
							<?php else : ?>
								<h1><?php bloginfo( 'name' ); ?></h1>
							<?php endif; ?>
						</header>

						<figure>
							<img src="<?php echo esc_url( $avatar ); ?>" alt="<?php echo esc_attr( $first .' '. $last ); ?>" />
							<figcaption>
								<h3 class="name"><?php echo $name; /* already escaped above */ ?></h3>
							</figcaption>
						</figure>
					</section>
				</article>
			<?php endforeach; ?>
		<?php endif; ?>

	<?php wp_footer(); ?>
</body>
</html>
