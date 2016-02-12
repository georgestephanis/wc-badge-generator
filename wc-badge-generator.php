<?php
/**
 * Plugin Name: WordCamp Badge Generator
 * Plugin URI: http://make.wordpress.org/community/
 * Description: Generates badges for WordCamps.
 * Author: George Stephanis
 * Version: 0.1
 * Author URI: http://stephanis.info/
 */

class WC_Badge_Generator {
	public static function add_hooks() {
		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );
	}

	public static function admin_menu() {
		$hook = add_submenu_page( 'edit.php?post_type=tix_ticket',
						__( 'Badges' ),
						__( 'Badges' ),
						'manage_options',
						'attendee_badges',
						array( __CLASS__, 'badges_page' )
					);
		add_action( "load-{$hook}", array( __CLASS__, 'show_badges' ) );
	}

	public static function badges_page() {
		echo 'Hrm, something went wrong.  You shouldn\'t have gotten this far!';
	}

	public static function show_badges() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		include( 'badges.tpl.php' );
		exit;
	}
}

if ( is_admin() ) {
	WC_Badge_Generator::add_hooks();
}
