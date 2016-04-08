<?php

namespace CampTix\Badge_Generator;
defined( 'WPINC' ) or die();

// todo what's the point of making it a camptix addon? it doesn't gain anything since it's not a payment oaddon

add_action( 'admin_menu', __NAMESPACE__ . '\add_admin_page' );

// todo
function add_admin_page() {
	// todo new page,not under camptix ?

	$hook = add_submenu_page(
		'edit.php?post_type=tix_ticket',
		__( 'Badges' ),
		__( 'Badges' ),
		'manage_options',
		'attendee_badges',
		'strstr'

		// todo link to customize.php instead
		// todo add a link like that for site cloner too
	);

	// todo just link to customizer for now. when add indesign, have a page that shows both options and explains them, and links to both.
	// indesign will just need a screen to pick options then push button to generate file to download, also display instructions for indesign data merge
}

// todo v2 - let choose which ticket type, b/c will want diff badges for speaker/sponsor/organizer/attendee
