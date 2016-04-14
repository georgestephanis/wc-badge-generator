<?php

namespace CampTix\Badge_Generator\InDesign;
use \CampTix\Badge_Generator;
use \CampTix\Badge_Generator\HTML;
defined( 'WPINC' ) or die();

/*
 * todo v1
 *
 * take latest version of bin script and convert it to work in wp-admin
 * push button to generate zip file to download with CSV and gravatars. see notes in #262
 * add option for ticket type, twitter, etc
 * display instructions for indesign data merge in contextual help
 */

/*
 * todo v2
 *
 * pick options and generate a zip file w/ csv and gravatars
 */

add_action( 'camptix_menu_tools_indesign_badges', __NAMESPACE__ . '\render_indesign_page' );

/**
 * Render the Indesign Badges page
 */
function render_indesign_page() {
	if ( ! current_user_can( Badge_Generator\REQUIRED_CAPABILITY ) ) {
		return;
	}

	$html_customizer_url = HTML\get_customizer_section_url();

	require_once( dirname( __DIR__ ) . '/views/indesign-badges/page-indesign-badges.php' );
}