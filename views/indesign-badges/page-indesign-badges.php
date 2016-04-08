<?php

namespace CampTix\Badge_Generator\InDesign;
defined( 'WPINC' ) or die();

// todo v2 - pick options and generate a zip file w/ csv and gravatars

?>

<div class="wrap">
	<h1>
		<?php _e( 'InDesign Badges', 'wordcamporg' ); ?>
	</h1>

	<p>
		<?php _e(
			'The process for building InDesign badges hasn\'t been automated yet, so it requires a developer to run a script.
			 That script will create a CSV file and will download Gravatar images for all attendees.
			 Once that is done, a designer can take those files into InDesign and use the Data Merge feature to create personalized badges for each attendee.',
			'wordcamporg'
		); ?>
	</p>

	<p>
		<?php printf(
			__(
				'Full instructions are <a href="%s">available in the WordCamp Organizer Handbook</a>.
				If you\'d prefer an easier way, <a href="%s">the HTML/CSS method</a> is much more automated.',
				'wordcamporg'
			),
			'https://make.wordpress.org/community/handbook/wordcamp-organizer-handbook/first-steps/helpful-documents-and-templates/create-wordcamp-badges-with-gravatars/',
			esc_url( $html_customizer_url )
		); ?>
	</p>

	<p>
		<?php printf(
			__( 'if you\'d like to help automate this process, you can contribute to <a href="%s">Meta ticket #262</a>.', 'wordcamporg' ),
			'https://meta.trac.wordpress.org/ticket/262'
		); ?>
	</p>
</div>
