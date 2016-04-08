<?php

/**
 * Plugin Name: WordCamp Badge Generator
 * Description: Generates attendee badges for printing.
 * Version:     0.1
 * Author:      WordCamp.org
 * Author URI:  http://wordcamp.org
 * License:     GPLv2 or later
 */

namespace WordCamp\Badge_Generator;
defined( 'WPINC' ) or die();

if ( is_admin() ) {
	require_once( __DIR__ . '/includes/wordcamp-badge-generator.php' );
	require_once( __DIR__ . '/includes/indesign-badges.php'          );
}

require_once( __DIR__ . '/includes/html-badges.php' );
