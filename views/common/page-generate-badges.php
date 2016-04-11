<?php

namespace CampTix\Badge_Generator;
defined( 'WPINC' ) or die();

?>

<div class="wrap">
	<h1><?php _e( 'Generate Badges', 'wordcamporg' ); ?></h1>

	<p>
		<?php _e(
			'This tool will help you create personalized badges for attendees to wear during the event.
			There are two methods for this, depending on your preferences:',
			'wordcamporg'
		); ?>
	</p>

	<div id="cbg-method-overviews">
		<div id="html-badge-overview">
			<h2><?php _e( 'HTML and CSS', 'wordcamporg' ); ?></h2>

			<ul class="ul-disc">
				<li><?php _e( 'The Easiest method.',                                                                              'wordcamporg' ); ?></li>
				<li><?php _e( 'Can be as simple as using the default design and printing at home.',                               'wordcamporg' ); ?></li>
				<li><?php _e( 'Design is customizable by a designer or developer, but options are limited compared to InDesign.', 'wordcamporg' ); ?></li>
				<li><?php _e( "Can't be taken to a professional printer.",                                                        'wordcamporg' ); ?></li>
			</ul>

			<!-- todo show a sample image. ask george for one from his camp -->

			<a class="button button-primary" href="<?php echo esc_url( $html_customizer_url ); ?>">
				<?php _e( 'Create Badges with HTML and CSS', 'wordcamporg' ); ?>
			</a>
		</div>

		<div id="indesign-badges-overview" class="cbg-method-overview">
			<h2><?php _e( 'InDesign', 'wordcamporg' ); ?></h2>

			<ul class="ul-disc">
				<li><?php _e( 'The best results, but requires more work.', 'wordcamporg' ); ?></li>
				<li><?php _e( 'Most flexible design options.',             'wordcamporg' ); ?></li>
				<li>
					<?php printf(
						__( 'Requires a designer and a copy of InDesign. <a href="%s">Free trial copies are available</a>.', 'wordcamporg' ),
						'https://www.adobe.com/products/indesign.html'
					); ?>
				</li>
				<li><?php _e( 'Printed by a professional printer, or at home.', 'wordcamporg' ); ?></li>
			</ul>

			<!-- todo show a sample image. use wcsf14, see if can find originals or ask jan. maybe in a8c design repo
			or maybe
			https://blogldc.s3.amazonaws.com/wp-content/uploads/2014/10/wordcamp_sf_mortenbadge.jpg
			https://ma.tt/files/2014/10/MCM_2862.jpg
			-->

			<a class="button button-primary" href="<?php echo esc_url( $indesign_page_url ); ?>">
				<?php _e( 'Create Badges with InDesign', 'wordcamporg' ); ?>
			</a>
		</div>
	</div>

	<p>
		<?php printf(
			__(
				'Regardless of which method you choose, you\'ll get the best results if you encourage attendees to create <a href="%s">Gravatar</a> accounts before you create the badges.
				You can use <a href="%s">the Notify tool</a> to e-mail everyone.
				Make sure to tell them to use the same e-mail address that they used when purchasing a ticket.',
				'wordcamporg'
			),
			'https://gravatar.com',
			esc_url( $notify_tool_url )
		); ?>
	</p>
</div>
