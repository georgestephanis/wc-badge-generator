<?php

namespace CampTix\Badge_Generator\HTML;
use \CampTix\Badge_Generator;
defined( 'WPINC' ) or die();

/*
 * todo v1
 *
 * high - need someone to actually test printing, since i don't have a printer
 * high - test in all browsers
 *
 * can use the ? icon like the Menus section does if too much help text
 * in help text
 * - link to notify tool to email people to sign up for gravatar w/ their camptix email addr
 * - can create different badges for speakers, sponsors, etc by targeting `attendee.ticket-{ticket_slug}` and/or attendee.coupon-{coupon_slug}
 * - can target individual attendees with .attendee-{name} when need to tweak spedific badges. e.g., make a long name have smaller last name font size, etc
 * - can target first and last name seperatly
 * - if accidentally reset, hit ctrl-z inside textarea to undo
 * - note that normalize.css is enqueued before your styles. you can override them.
 * - assembly intstructions: cut, folder in half, attach lanyard (fold in half bit may not be intuitive, so should mention it)
 *
 * update handbook/plan docs
 * write a launch post for make/community
 * leave comment on george's post
 */

/*
 * todo v2
 *
 * shows sample image in admin page for both types.
 *	use wcsf14, see if can find originals or ask jan. maybe in a8c design repo
 *	or maybe
 *	https://blogldc.s3.amazonaws.com/wp-content/uploads/2014/10/wordcamp_sf_mortenbadge.jpg
 *	https://ma.tt/files/2014/10/MCM_2862.jpg
 *
 * improve the default design
 * add checkbox to include twitter, option for which image, option for name instead of image, etc
 * - can use the [gear] icon like the Menus section does for options like including twitter field, etc
 */

add_action( 'customize_register',    __NAMESPACE__ . '\register_customizer_components'   );
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\enqueue_customizer_scripts'       );
add_action( 'admin_print_styles',    __NAMESPACE__ . '\print_customizer_styles'          );
add_action( 'wp_enqueue_scripts',    __NAMESPACE__ . '\remove_all_previewer_styles', 998 );
add_action( 'wp_enqueue_scripts',    __NAMESPACE__ . '\enqueue_previewer_scripts',   999 );  // after remove_all_previewer_styles()
add_filter( 'template_include',      __NAMESPACE__ . '\use_badges_template'              );

/**
 * Register our Customizer settings, panels, sections, and controls
 *
 * @param \WP_Customize_Manager $wp_customize
 */
function register_customizer_components( $wp_customize ) {
	$wp_customize->add_section(
		'section_camptix_html_badges',
		array(
			'capability' => Badge_Generator\REQUIRED_CAPABILITY,
			'title'      => __( 'CampTix HTML Badges', 'wordcamporg' ),
			'type'       => 'cbgSection'

			//'description' => __( 'Design attendee badges with HTML and CSS.', 'wordcamporg' ),  // todo probably move this to ? icon, to save space
		)
	);

	// todo better names for settings and controls. prefix instead of entire name, more descriptive

	$wp_customize->add_control( 'cbg_control_print_badges', array(
		'section'     => 'section_camptix_html_badges',
		'settings'    => array(),
		'type'        => 'button',
		'priority'    => 1,
		'capability'  => Badge_Generator\REQUIRED_CAPABILITY,
		'input_attrs' => array(
			'class' => 'button button-primary',
			'value' => __( 'Print', 'wordcamporg' ),
		),
	) );

	$wp_customize->add_control( 'cbg_control_reset_css', array(
		'section'     => 'section_camptix_html_badges',
		'settings'    => array(),
		'type'        => 'button',
		'priority'    => 1,
		'capability'  => Badge_Generator\REQUIRED_CAPABILITY,
		'input_attrs' => array(
			'class' => 'button button-secondary',
			'value' => __( 'Reset to Default', 'wordcamporg' ),
		),
	) );

	// todo remove 'setting_', 'control_', etc prefixes to be consistent w/ core?

	$wp_customize->add_setting(
		'setting_camptix_html_badge_css',
		array(
			'default'           => file_get_contents( dirname( __DIR__ ) . '/css/html-badges-default-styles.css' ),
			'type'              => 'option',
			'capability'        => Badge_Generator\REQUIRED_CAPABILITY,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'esc_textarea',
			// todo high - esc_textarea fine for display, but on save need to run through our custom stuff in jetpack-tweaks, but disable the admin notice.
		)
	);

	$wp_customize->add_control(
		'setting_camptix_html_badge_css',   // todo shouldn't this be control_... ? but then it doesn't expand panel. need to update js too.
		array(
			'type'        => 'textarea',
			'section'     => 'section_camptix_html_badges',
			'priority'    => 2,
			'label'       => __( 'Customize Badge CSS', 'wordcamporg' ),

			//'description' => 'add instructions here?'   // todo probably move this to ? icon, to save space
		)
	);

	// todo high - test that it saves modified css - looks good but haven't looked close
}

/**
 * Get the URL for the HTML Badges section in the Customizer
 *
 * @return string
 */
function get_customizer_section_url() {
	$url = add_query_arg(
		array(
			'autofocus[section]' => 'section_camptix_html_badges',
			'url'                => rawurlencode( add_query_arg( 'camptix-badges', '', site_url() ) ),
		),
		admin_url( 'customize.php' )
	);

	return $url;
}

/**
 * Enqueue scripts and styles for the Customizer
 */
function enqueue_customizer_scripts() {
	if ( false ) {
		// todo only in customizer  -- is there another method for that?
		return;
	}

	// Enqueue CodeMirror script and style, but dequeue extraneous Jetpack scripts and styles
	// todo high - check if callable, if not, then require()
	\Jetpack_Custom_CSS::enqueue_scripts( 'appearance_page_editcss' );

	wp_dequeue_script( 'postbox' );
	wp_dequeue_script( 'custom-css-editor' );
	//wp_dequeue_style( 'custom-css-editor' );           //todo high - sometimes breaks
	//wp_dequeue_style( 'jetpack-css-use-codemirror' );  //todo sometimes breaks
	wp_dequeue_script( 'jetpack-css-use-codemirror' );

	wp_enqueue_script(
		'camptix-html-badges-customizer',
		plugins_url( 'javascript/html-badges-customizer.js', __DIR__ ),
		array( 'jquery', 'jetpack-css-codemirror' ),
		1,
		true
	);

	$default_css = file_get_contents( dirname( __DIR__ ) . '/css/html-badges-default-styles.css' );

	wp_localize_script(
		'camptix-html-badges-customizer',
		'cbgHtmlCustomizerData',
		array(
			'defaultCSS' => get_option( 'setting_camptix_html_badge_css', $default_css ),
			// todo high - esc_js or json_encode or something needed here? pretty sure it's safe at this point b/c localize_script() json-encodes it. maybe not later on though.
		)
	);
}

/**
 * Print styles for the Customizer
 */
function print_customizer_styles() {
	if ( false ) {
		// todo only in customizer  -- is there another method for that?
		return;
	}

	?>

	<!-- BEGIN CampTix Badge Generator -->
	<style type="text/css">
		<?php require_once( dirname( __DIR__ ) . '/css/html-badges-customizer.css' ); ?>
	</style>
	<!-- END CampTix Badge Generator -->

	<?php
}

/**
 * Check if the current page is our section of the Previewer
 *
 * @return bool
 */
function is_badges_preview() {
	global $wp_customize;

	return isset( $_GET['camptix-badges'] ) && $wp_customize->is_preview();
}

/**
 * Use our custom template when the Badges page is requested
 *
 * @param string $template
 *
 * @return string
 */
function use_badges_template( $template ) {
	if ( ! is_badges_preview() ) {
		return $template;
	}

	if ( ! current_user_can( Badge_Generator\REQUIRED_CAPABILITY ) ) {
		return $template;
	}

	return dirname( __DIR__ ) . '/views/html-badges/template-badges.php';
}

/**
 * Render the template for HTML badges
 */
function render_badges_template() {
	/** @var $camptix \CampTix_Plugin */
	global $camptix;
	
	$allowed_html = array(
		'span' => array(
			'class' => true,
		),
	);
	
	$attendees = get_posts( array(
		'post_type'      => 'tix_attendee',
		'posts_per_page' => -1,
		'orderby'        => 'title',
		'cache_results'  => false,  // todo necessary?
	) );
	
	// Disable object cache for prepared metadata.
	$camptix->filter_post_meta = $camptix->prepare_metadata_for( $attendees );  // todo necessary?

	require( dirname( __DIR__ ) . '/views/html-badges/template-badges.php' );
}

/**
 * Get all the data required to render an attendee badge
 *
 * @param \WP_Post $attendee
 *
 * @return array
 */
function get_attendee_data( $attendee ) {
	/** @var $camptix \CampTix_Plugin */
	global $camptix;
	$css_classes = array( 'attendee-' . $attendee->post_name );

	$first_name     = get_post_meta( $attendee->ID, 'tix_first_name', true );
	$last_name      = get_post_meta( $attendee->ID, 'tix_last_name',  true );
	$formatted_name = $camptix->format_name_string(
		'<span class="first-name">%first%</span>
		 <span class="last-name">%last%</span>',
		$first_name,
		$last_name
	);

	$email_address = get_post_meta( $attendee->ID, 'tix_email', true );
	$avatar_url    = get_avatar_url( $email_address, array( 'size' => 600 ) );

	$ticket        = get_post( get_post_meta( $attendee->ID, 'tix_ticket_id', true ) );
	$css_classes[] = 'ticket-' . $ticket->post_name;

	$coupon_id = get_post_meta( $attendee->ID, 'tix_coupon_id', true );
	if ( $coupon_id ) {
		$coupon        = get_post( $coupon_id );
		$css_classes[] = 'coupon-' . $coupon->post_name;
	}

	$css_classes = implode( ' ', $css_classes );

	return compact( 'formatted_name', 'email_address', 'avatar_url', 'css_classes' );
}

/**
 * Remove all other styles from the Previewer, to avoid conflicts
 *
 * todo make dry w/ coming soon page?
 */
function remove_all_previewer_styles() {
	global $wp_styles;

	if ( ! is_badges_preview() ) {
		return;
	}

	foreach( $wp_styles->queue as $stylesheet ) {
		wp_dequeue_style( $stylesheet );
	}

	remove_all_actions( 'wp_print_styles' );

	remove_action( 'wp_head', array( 'Jetpack_Custom_CSS', 'link_tag' ), 101 );

	// todo high - remove remote-css?
}

/**
 * Enqueue scripts and styles for the Previewer
 */
function enqueue_previewer_scripts() {
	wp_register_script(
		'camptix-html-badges-previewer',
		plugins_url( 'javascript/html-badges-previewer.js', __DIR__ ),
		array( 'jquery', 'customize-preview' ),
		1,
		true
	);

	wp_register_style(
		'cbg_normalize',
		plugins_url( 'css/normalize.min.css', __DIR__ ),
		array(),
		'4.1.1'
	);

	if ( ! is_badges_preview() ) {
		return;
	}

	wp_enqueue_script( 'camptix-html-badges-previewer' );
	wp_enqueue_style( 'cbg_normalize' );

	/*
	 * Register the callback in this function so that the stylesheet is registered after remove_all_styles().
	 *
	 * Also, register it at wp_print_scripts instead of wp_print_styles, so that it gets printed _after_
	 * normalize.min.css.
	 */
	add_action( 'wp_print_scripts', __NAMESPACE__ . '\print_saved_styles' );
}

/**
 * Print the saved custom badge CSS
 */
function print_saved_styles() {
	if ( ! is_badges_preview() ) {
		return;
	}

	?>

	<!-- todo rename to better id,update everywhere -->
	<style id="camptix-html-badges-css" type="text/css">
		<?php echo esc_html( get_option( 'setting_camptix_html_badge_css' ) ); // todo high - is this the correct escaping function? ?>
	</style>

	<?php
}
