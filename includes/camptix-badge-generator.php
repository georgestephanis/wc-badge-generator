<?php

namespace CampTix\Badge_Generator;
defined( 'WPINC' ) or die();

/*
 * todo
 *
 *
 * v2
 * let choose which ticket type, b/c will want diff badges for speaker/sponsor/organizer/attendee
 * integrate indesign bin script. push button to generate zip file to download with CSV and gravatars
 * display instructions for indesign data merge in contextual help
 */

add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\register_scripts' );
add_action( 'admin_menu',            __NAMESPACE__ . '\add_admin_page'   );

/**
 * Register common scripts and styles
 */
function register_scripts() {
	// todo only enqueue on our pages

	wp_enqueue_style(
		'camptix_badge_generator',
		plugins_url( 'css/camptix-badge-generator.css', __DIR__ ),
		array(),
		1
	);
}

/**
 * Register admin pages
 */
function add_admin_page() {
	// todo put this under tools? but then it won't be seen? probably correct to put it there, but need documentation
        //  add to handbook, write post on make/comm	

	add_submenu_page(
		'edit.php?post_type=tix_ticket',    // todo this should be slug instead of url?
		__( 'Generate Badges', 'wordcamporg' ),
		__( 'Generate Badges', 'wordcamporg' ),
		\CampTix\Badge_Generator\REQUIRED_CAPABILITY,
		'generate_badges',
		__NAMESPACE__ . '\render_admin_page'
	);
}

/**
 * Render admin pages
 */
function render_admin_page() {
	$html_customizer_url = add_query_arg(
		array(
			'camptix-html-badges' => '',
			'url'                 => rawurlencode( add_query_arg( 'camptix-badges', '', site_url() ) ),
		),
		admin_url( 'customize.php' )

		// todo set return url here and in js?
	);

	if ( isset( $_GET['method'] ) && 'indesign' == $_GET['method'] ) {
		$notify_tool_url = admin_url( 'edit.php?post_type=tix_ticket&page=camptix_tools&tix_section=notify' );

		require_once( dirname( __DIR__ ) . '/views/indesign-badges/page-indesign-badges.php' );
	} else {
		$indesign_page_url = add_query_arg( 'method', 'indesign' );

		require_once( dirname( __DIR__ ) . '/views/common/page-generate-badges.php' );
	}
}
