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

add_action( 'admin_menu', __NAMESPACE__ . '\add_admin_page' );

/**
 * Register admin pages
 */
function add_admin_page() {
	// todo put this under tools? but then it won't be seen? probably correct to put it there, but need documentation
        //  add to handbook, write post on make/comm	

	$hook_suffix = add_submenu_page(
		'edit.php?post_type=tix_ticket',    // todo this should be slug instead of url?
		__( 'Generate Badges', 'wordcamporg' ),
		__( 'Generate Badges', 'wordcamporg' ),
		\CampTix\Badge_Generator\REQUIRED_CAPABILITY,
		'generate_badges',
		__NAMESPACE__ . '\render_admin_page'
	);

	add_action( 'admin_print_styles-' . $hook_suffix, __NAMESPACE__ . '\print_admin_styles' );
}

/**
 * Print CSS styles for wp-admin
 */
function print_admin_styles() {
	?>

	<!-- BEGIN CampTix Badge Generator -->
	<style type="text/css">
		<?php require_once( dirname( __DIR__ ) . '/css/camptix-badge-generator.css' ); ?>
	</style>
	<!-- END CampTix Badge Generator -->

	<?php
}

/**
 * Render admin pages
 */
function render_admin_page() {
	$html_customizer_url = add_query_arg(
		array(
			'autofocus[section]' => 'section_camptix_html_badges',
			'url'                => rawurlencode( add_query_arg( 'camptix-badges', '', site_url() ) ),
		),
		admin_url( 'customize.php' )

		// todo set return url here and in js?
	);

	if ( isset( $_GET['method'] ) && 'indesign' == $_GET['method'] ) {
		require_once( dirname( __DIR__ ) . '/views/indesign-badges/page-indesign-badges.php' );
	} else {
		$notify_tool_url   = admin_url( 'edit.php?post_type=tix_ticket&page=camptix_tools&tix_section=notify' );
		$indesign_page_url = add_query_arg( 'method', 'indesign' );

		require_once( dirname( __DIR__ ) . '/views/common/page-generate-badges.php' );
	}
}
