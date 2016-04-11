<?php

namespace CampTix\Badge_Generator\HTML;
defined( 'WPINC' ) or die();

add_action( 'customize_register',     __NAMESPACE__ . '\register_customizer_components' );
add_action( 'admin_enqueue_scripts',  __NAMESPACE__ . '\enqueue_customizer_scripts'     );  // todo more generic name?
add_action( 'customize_preview_init', __NAMESPACE__ . '\enqueue_previewer_scripts'      );
add_filter( 'template_include',       __NAMESPACE__ . '\render_html_badges'             );

/**
 * Register our Customizer settings, panels, sections, and controls
 *
 * @param \WP_Customize_Manager $wp_customize
 */
function register_customizer_components( $wp_customize ) {
	// todo add checkbox to include twitter, etc - v2

	$wp_customize->add_section(
		'section_camptix_html_badges',
		array(
			'capability'  => \CampTix\Badge_Generator\REQUIRED_CAPABILITY,
			'title'       => __( 'CampTix HTML Badges',                       'wordcamporg' ),
			'description' => __( 'Design attendee badges with HTML and CSS.', 'wordcamporg' ),
			'type'        => 'cbgSection'
		)
	);

	$wp_customize->add_setting(
		'setting_camptix_html_badge_css',
		array(
			'default'           => file_get_contents( dirname( __DIR__ ) . '/css/html-badge-default-styles.css' ),
			'type'              => 'option',
			'capability'        => \CampTix\Badge_Generator\REQUIRED_CAPABILITY,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'esc_textarea',
			// todo esc_textarea fine for display, but on save need to run through our custom stuff in jetpack-tweaks, but disable the admin notice.
		)
	);

	$wp_customize->add_control(
		'setting_camptix_html_badge_css',   // todo shouldn't this be control_... ? but then it doesn't expand panel. need to update js too.
		array(
			'type'        => 'textarea',
			'section'     => 'section_camptix_html_badges',
			'label'       => __( 'Customize CSS', 'wordcamporg' ),
			'description' => 'foo instructions',
			// todo add a ticket type css selector, and write inline documentation about it
		)
	);
	// todo test that it saves modified css
}

// todo
function enqueue_customizer_scripts() {
	wp_register_script(
		'camptix-html-badges-customizer',
		plugins_url( 'javascript/html-badges-customizer.js', __DIR__ ),
		array( 'jquery', 'customize-controls' ),    // todo needs controls?
		1,
		true
	);

	if ( false ) {
		// todo only in customizer  -- is there another method for that?
		return;
	}

	wp_enqueue_script( 'camptix-html-badges-customizer' );

	// todo check if callable, if not, then require()
	//\Jetpack_Custom_CSS::enqueue_scripts( 'appearance_page_editcss' );
}

// todo
function enqueue_previewer_scripts() {

	// Remove all other stylesheets
		// todo does this have to be done at diff hook b/c prevenw_init too early?
	foreach( $GLOBALS['wp_styles']->queue as $stylesheet ) { 
	//	wp_dequeue_style( $stylesheet );
	}

	//remove_action( 'wp_head', array( 'Jetpack_Custom_CSS', 'link_tag' ), 101 );
	
	// Add our scripts and stylesheets
	wp_register_script(
		'camptix-html-badges-previewer',
		plugins_url( 'javascript/html-badges-previewer.js', __DIR__ ),
		array( 'jquery',  'customize-preview', 'underscore' ),    // todo needs controls? needs underscore?
		1,
		true
	);

	wp_register_style(
		'camptix-html-badges-previewer',  //todo rename after split
		plugins_url( 'css/camptix-badge-generator.css', __DIR__ ),
		array(),
		1
	);

	if ( false ) {
		// todo only in customizer  -- is there another method for that?
		return;
	}

	wp_enqueue_script( 'camptix-html-badges-previewer' );
	wp_enqueue_style( 'camptix-html-badges' );
}

// todo
function render_html_badges( $template ) {  // todo rename to more accurate
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
	
	return dirname( __DIR__ ) . '/views/html-badges/template-badges.php';
}
