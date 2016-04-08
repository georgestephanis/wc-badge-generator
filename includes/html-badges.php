<?php

namespace CampTix\Badge_Generator\HTML;
defined( 'WPINC' ) or die();

add_action( 'customize_register', __NAMESPACE__ . '\register_customizer_components' );
add_filter( 'template_include',   __NAMESPACE__ . '\render_html_badges'             );

/**
 * Register our Customizer settings, panels, sections, and controls
 *
 * @param \WP_Customize_Manager $wp_customize
 */
function register_customizer_components( $wp_customize ) {
	// todo add checkbox to include twitter, etc
	// todo use new 4.5 live refresh

	$wp_customize->add_section(
		'section_camptix_html_badges',
		array(
			'capability'     => \CampTix\Badge_Generator\REQUIRED_CAPABILITY,
			'title'          => __( 'CampTix HTML Badges',                       'wordcamporg' ),
			'description'    => __( 'Design attendee badges with HTML and CSS.', 'wordcamporg' ),
		)
	);

	$wp_customize->add_setting(
		'setting_camptix_html_badge_css',
		array(
			'default'           => file_get_contents( dirname( __DIR__ ) . '/css/html-badge-default-styles.css' ),
			'type'              => 'option',
			'capability'        => \CampTix\Badge_Generator\REQUIRED_CAPABILITY,
			'transport'         => '',  //todo
			'sanitize_callback' => 'esc_textarea',
			// todo esc_textarea fine for display, but on save need to run through our custom stuff in jetpack-tweaks, but disable the admin notice.
		)
	);

	$wp_customize->add_control(
		'setting_camptix_html_badge_css',
		array(
			'type'        => 'textarea',
			'section'     => 'section_camptix_html_badges',
			'label'       => __( 'Customize CSS', 'wordcamporg' ),
			'description' => 'foo instructions',
		)
	);
}

// todo
function render_html_badges( $template ) {
	if ( ! isset( $_GET['camptix-badges'] ) ) {
		return $template;
	}

	/*
	if ( 'customize.php' != basename( $_SERVER['SCRIPT_NAME'] ) && empty( $_REQUEST['wp_customize'] ) ) {
		return array();
	}
	*/

	if ( 'on' !== $_REQUEST['wp_customize'] ) {
		//return $template;   // todo probably better way to detect if customizer

		// todo still need to detect if our panel
			// no, just detect if url has query param, then when open panel, redirect customzeir frame to that url
	}

	if ( ! current_user_can( \CampTix\Badge_Generator\REQUIRED_CAPABILITY ) ) {
		return $template;
	}

	// dequeue all plugin/theme css and js - see coming soon page
	// todo enqueue scripts
	// or maybe just directly add it to markup b/c this is a special case
	
	return dirname( __DIR__ ) . '/views/html-badges/badges.php';
}
