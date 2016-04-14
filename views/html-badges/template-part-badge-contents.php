<?php

namespace CampTix\Badge_Generator\HTML;
defined( 'WPINC' ) or die();

?>

<header>
	<?php if ( has_custom_logo() ) : ?>
		<?php the_custom_logo(); ?>

	<?php else : ?>
		<h2 class="wordcamp-name">
			<?php echo esc_html( get_wordcamp_name() ); ?>
		</h2>

	<?php endif; ?>
</header>

<figure>
	<img
	    class="attendee-avatar"
		src="<?php echo esc_url( $attendee_data['avatar_url'] ); ?>"
		alt="<?php echo esc_attr( strip_tags( $attendee_data['formatted_name'] ) ); ?>"
	/>

	<figcaption>
		<h1 class="attendee-name">
			<?php echo wp_kses( $attendee_data['formatted_name'], $allowed_html ); ?>
		</h1>
	</figcaption>
</figure>
