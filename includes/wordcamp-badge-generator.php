<?php

namespace WordCamp\Badge_Generator;

class WordCamp_Badge_Generator extends \CampTix_Addon {     // todo rename b/c redundant within namespace?

	// todo
	public function __construct() {
		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );
	}

	// todo
	public static function admin_menu() {
		$hook = add_submenu_page(
			'edit.php?post_type=tix_ticket',
			__( 'Badges' ),
			__( 'Badges' ),
			'manage_options',
			'attendee_badges',
			array( __CLASS__, 'badges_page' )   // todo can probably find something better that retuns an empty string or something
		);

		// todo just link to customizer

		add_action( "load-{$hook}", array( __CLASS__, 'show_badges' ) );    // todo use template_include filter instead
	}

	// todo
	public static function badges_page() {
		echo 'Hrm, something went wrong.  You shouldn\'t have gotten this far!';
		// todo remove
	}

	// todo
	public static function show_badges() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		require_once( dirname( __DIR__ ) . '/views/badges.php' );
		exit;
	}
}
