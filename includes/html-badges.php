<?php

namespace WordCamp\Badge_Generator\HTML;
defined( 'WPINC' ) or die();

add_action( 'customize_register', __NAMESPACE__ . '\register_customizer_components' );
add_filter( 'template_include',   __NAMESPACE__ . '\render_html_badges'             );

/**
 * Register our Customizer settings, panels, sections, and controls
 *
 * @param \WP_Customize_Manager $wp_customize
 */
function register_customizer_components( $wp_customize ) {
	return;

	/*
	require_once( __DIR__ . '/includes/source-site-id-setting.php' );
	require_once( __DIR__ . '/includes/sites-section.php' );
	require_once( __DIR__ . '/includes/site-control.php' );


	$wp_customize->register_control_type( __NAMESPACE__ . '\Site_Control' );

	$wp_customize->add_setting( new Source_Site_ID_Setting(
		$wp_customize,
		'wcsc_source_site_id',
		array()
	) );
*/

	$wp_customize->add_panel(
		'wordcamp_site_cloner',
		array(
			'type'        => 'wcscPanel',
			'title'       => __( 'Clone Another WordCamp', 'wordcamporg' ),
			'description' => __( "Clone another WordCamp's theme and custom CSS as a starting point for your site.", 'wordcamporg' ),
		)
	);

	$wp_customize->add_section( new Sites_Section(
		$wp_customize,
		'wcsc_sites',
		array(
			'panel' => 'wordcamp_site_cloner',
			'title' => __( 'WordCamp Sites', 'wordcamporg' ),
		)
	) );

	/*
		$wp_customize->add_control( new Site_Control(
			$wp_customize,
			'wcsc_site_id_' . $wordcamp['site_id'],
			array(
				'type'           => 'wcscSite',                      // todo should be able to set this in control instead of here, but if do that then control contents aren't rendered
				'site_id'        => $wordcamp['site_id'],
				'site_name'      => $wordcamp['name'],
				'theme_slug'     => $wordcamp['theme_slug'],
				'screenshot_url' => $wordcamp['screenshot_url'],
			)
		) );
	*/
}

// todo
function render_html_badges( $template ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		return $template;
	}

	if ( 'on' !== $_REQUEST['wp_customize'] ) {
		return $template;   // todo probably better way to detect if customizer

		// todo still need to detect if our panel
	}

	// dequeue all plugin/theme css and js - see coming soon page

	// todo enqueue scripts


	return dirname( __DIR__ ) . '/views/html-badges/badges.php';
}

