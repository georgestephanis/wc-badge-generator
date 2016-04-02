<?php global $camptix; ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php esc_html_e( 'WordCamp Badges' ); ?></title>
	<link rel="stylesheet" href="<?php echo esc_url( plugins_url( 'badges.css', __FILE__ ) ); ?>" />
</head>
<body>
	<?php
	$attendees = get_posts( array(
								'post_type'      => 'tix_attendee',
								'posts_per_page' => -1,
								'post_status'    => array( 'publish', 'pending' ),
								'orderby'        => 'title',
								'fields'         => 'ids', // ! no post objects
								'cache_results'  => false,
							) );

	if ( ! is_array( $attendees ) || count( $attendees ) < 1 ) {
		echo esc_html__( 'No attendees found to make badges for.' ) . "\r\n</body>\r\n</html>";
		return;
	}
?>
	<p><?php esc_html_e( 'Make sure to use Firefox to print these badges.  Some other browsers (like Chrome) don\'t respect some CSS properties that we use to specify where page breaks should be.' ); ?></p>
	<br /><br /><br />
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
					<img src="<?php echo esc_url( $avatar ); ?>" />
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
					<img src="<?php echo esc_url( $avatar ); ?>" />
					<figcaption>
						<h3 class="name"><?php echo $name; /* already escaped above */ ?></h3>
					</figcaption>
				</figure>
			</section>
		</article>
	<?php endforeach; ?>
</body>
</html>
