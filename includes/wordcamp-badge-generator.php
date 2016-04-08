<?php

namespace WordCamp\Badge_Generator;

// todo what's the point of making it a camptix addon? it doesn't gain anything since it's not a payment oaddon

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
			'strstr'

			// todo link to customize.php instead
			// todo add a link like that for site cloner too
		);

		// todo just link to customizer for now. when add indesign, have a page that shows both options and explains them, and links to both.
		// indesign will just need a screen to pick options then push button to generate file to download, also display instructions for indesign data merge
	}
}
