<?php

namespace CampTix\Badge_Generator;
defined( 'WPINC' ) or die();

/*
 * todo
 *
 *
 * v2 - let choose which ticket type, b/c will want diff badges for speaker/sponsor/organizer/attendee
 *
 */

add_action( 'admin_menu', __NAMESPACE__ . '\add_admin_page' );

// todo
function add_admin_page() {
	// todo put this under tools? but then it won't be seen? probably correct to put it there, but need documentation
        //  add to handbook, write post on make/comm	

	$hook = add_submenu_page(
		'edit.php?post_type=tix_ticket',    // todo this should be slug instead of url?
		__( 'Generate Badges', 'wordcamporg' ),
		__( 'Generate Badges', 'wordcamporg' ),
		\CampTix\Badge_Generator\REQUIRED_CAPABILITY,
		'generate_badges',
		__NAMESPACE__ . '\render_admin_page'
	);

	// todo just link to customizer for now. when add indesign, have a page that shows both options and explains them, and links to both.
	// indesign will just need a screen to pick options then push button to generate file to download, also display instructions for indesign data merge
}

// todo
function render_admin_page() {
	$html_customizer_url = admin_url( 'customize.php' );    // todo link directly to panel

	if ( isset( $_GET['method'] ) && 'indesign' == $_GET['method'] ) {
		$notify_tool_url = admin_url( 'edit.php?post_type=tix_ticket&page=camptix_tools&tix_section=notify' );

		require_once( dirname( __DIR__ ) . '/views/indesign-badges/page-indesign-badges.php' );
	} else {
		$indesign_page_url = add_query_arg( 'method', 'indesign' );

		require_once( dirname( __DIR__ ) . '/views/common/page-generate-badges.php' );
	}
}
