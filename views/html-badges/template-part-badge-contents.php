<?php

namespace CampTix\Badge_Generator\HTML;
defined( 'WPINC' ) or die();

?>

<header>
	<?php if ( has_custom_logo() ) : ?>
		<?php the_custom_logo(); ?>
		<?php // todo show 4.5 site logo, etc. header image? maybe reuse some of default_single_og_image()? ?>

	<?php else : ?>
		<h1>
			<?php echo esc_html( get_wordcamp_name() ); ?>
		</h1>

	<?php endif; ?>
</header>

<figure>
	<img src="<?php echo esc_url( $attendee_data['avatar_url'] ); ?>" alt="<?php echo esc_attr( strip_tags( $attendee_data['formatted_name'] ) ); ?>" />

	<figcaption>
		<h3 class="name">
			<?php echo wp_kses( $attendee_data['formatted_name'], $allowed_html ); ?>
		</h3>
	</figcaption>
</figure>
