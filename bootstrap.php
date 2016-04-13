<?php

/**
 * Plugin Name: CampTix Badge Generator
 * Description: Generates attendee badges for printing in multiple formats.
 * Version:     0.1
 * Author:      WordCamp.org
 * Author URI:  http://wordcamp.org
 * License:     GPLv2 or later
 */

namespace CampTix\Badge_Generator;
defined( 'WPINC' ) or die();

const REQUIRED_CAPABILITY = 'manage_options';   // todo editor would be fine?

if ( is_admin() ) {
	require_once( __DIR__ . '/includes/camptix-badge-generator.php' );
	require_once( __DIR__ . '/includes/indesign-badges.php'         );
}

require_once( __DIR__ . '/includes/html-badges.php' );
