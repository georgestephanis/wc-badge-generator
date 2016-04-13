<?php

namespace CampTix\Badge_Generator\HTML;
defined( 'WPINC' ) or die();

add_action( 'customize_register',    __NAMESPACE__ . '\register_customizer_components' );
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\enqueue_customizer_scripts'     );
add_action( 'wp_enqueue_scripts',    __NAMESPACE__ . '\remove_all_styles',         998 );
add_action( 'wp_enqueue_scripts',    __NAMESPACE__ . '\enqueue_previewer_scripts', 999 );  // after remove_all_styles()
add_filter( 'template_include',      __NAMESPACE__ . '\use_badges_template'            );

/*
 * todo v1
 *
 * wordcamp name smaller, attendee name bigger
 * wordcamp logo smaller, attendee avatar bigger
 *
 * high - compare PDF from this branch to PDF from master, to make sure it's close.
 * - will still need to test actual printing, but this'll be good until can do that
 * high - need someone to actually test printing, since i don't have a printer
 * 
 * can use the ? icon like the Menus section does if too much help text
 * in help text
 * - link to notify tool to email people to sign up for gravatar w/ their camptix email addr
 * - can create different badges for speakers, sponsors, etc by targeting `attendee.ticket-{ticket_slug}` and/or attendee.coupon-{coupon_slug}
 * - if accidentally reset, hit ctrl-z inside textarea to undo
 * - note that normalize.css is enqueued before your styles. you can override them.
 *
 * update handbook/plan docs
 * write a launch post for make/community
 * leave comment on george's post
 */

/*
 * todo v2
 *
 * improve the default design
 * add checkbox to include twitter, option for which image, option for name instead of image, etc
 * - can use the [gear] icon like the Menus section does for options like including twitter field, etc
 */

	
/**
 * Register our Customizer settings, panels, sections, and controls
 *
 * @param \WP_Customize_Manager $wp_customize
 */
function register_customizer_components( $wp_customize ) {
	$wp_customize->add_section(
		'section_camptix_html_badges',
		array(
			'capability'  => \CampTix\Badge_Generator\REQUIRED_CAPABILITY,
			'title'       => __( 'CampTix HTML Badges',                       'wordcamporg' ),
			'description' => __( 'Design attendee badges with HTML and CSS.', 'wordcamporg' ),  // todo probably move this to ? icon, to save space
			'type'        => 'cbgSection'
		)
	);

	// todo better names for settings and controls. prefix instead of entire name, more descriptive

	$wp_customize->add_control( 'cbg_control_print_badges', array(
		'section'     => 'section_camptix_html_badges',
		'settings'    => array(),
		'type'        => 'button',
		'priority'    => 1,
		'capability'  => \CampTix\Badge_Generator\REQUIRED_CAPABILITY,
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
		'capability'  => \CampTix\Badge_Generator\REQUIRED_CAPABILITY,
		'input_attrs' => array(
			'class' => 'button button-secondary',
			'value' => __( 'Reset to Default', 'wordcamporg' ), // todo is that clear enough that it will be the default css, not their last saved version?

			// todo make this an icon in the section title, but would need some kind of AYS.
				// todo or just align next to each other. flex, but would need container. maybe float?
		),
	) );

	// todo remove 'setting_', 'control_', etc prefixes to be consistent w/ core?

	$wp_customize->add_setting(
		'setting_camptix_html_badge_css',
		array(
			'default'           => file_get_contents( dirname( __DIR__ ) . '/css/html-badge-default-styles.css' ),
			'type'              => 'option',
			'capability'        => \CampTix\Badge_Generator\REQUIRED_CAPABILITY,
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
			'label'       => __( 'Customize CSS', 'wordcamporg' ),
			'description' => 'add instructions here?'   // todo probably move this to ? icon, to save space
		)
	);

	// todo test that it saves modified css - looks good but haven't looked close
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
	// todo check if callable, if not, then require()
	\Jetpack_Custom_CSS::enqueue_scripts( 'appearance_page_editcss' );

	wp_dequeue_script( 'postbox' );
	wp_dequeue_script( 'custom-css-editor' );
	//wp_dequeue_style( 'custom-css-editor' );           //todo sometimes breaks
	//wp_dequeue_style( 'jetpack-css-use-codemirror' );  //todo sometimes breaks
	wp_dequeue_script( 'jetpack-css-use-codemirror' );

	wp_enqueue_script(
		'camptix-html-badges-customizer',
		plugins_url( 'javascript/html-badges-customizer.js', __DIR__ ),
		array( 'jquery', 'jetpack-css-codemirror' ),
		1,
		true
	);
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

	if ( ! current_user_can( \CampTix\Badge_Generator\REQUIRED_CAPABILITY ) ) {
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
		'fields'         => 'ids',
		'cache_results'  => false,  // todo necessary?
	) );
	
	// Disable object cache for prepared metadata.
	$camptix->filter_post_meta = $camptix->prepare_metadata_for( $attendees );  // todo necessary?

	require( dirname( __DIR__ ) . '/views/html-badges/template-badges.php' );
}

/**
 * Get all the data required to render an attendee badge
 *
 * @param int $attendee_id
 *
 * @return array
 */
function get_attendee_data( $attendee_id ) {
	/** @var $camptix \CampTix_Plugin */
	global $camptix;
	$css_classes = array();

	$first_name     = get_post_meta( $attendee_id, 'tix_first_name', true );
	$last_name      = get_post_meta( $attendee_id, 'tix_last_name',  true );
	$formatted_name = $camptix->format_name_string(
		'<span class="first-name">%first%</span>
		 <span class="last-name">%last%</span>',
		$first_name,
		$last_name
	);

	$email_address = get_post_meta( $attendee_id, 'tix_email', true );
	$avatar_url    = get_avatar_url( $email_address, array( 'size' => 600 ) );

	$ticket        = get_post( get_post_meta( $attendee_id, 'tix_ticket_id', true ) );
	$css_classes[] = 'ticket-' . $ticket->post_name;

	$coupon_id = get_post_meta( $attendee_id, 'tix_coupon_id', true );
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
function remove_all_styles() {
	global $wp_styles;

	if ( ! is_badges_preview() ) {
		return;
	}

	foreach( $wp_styles->queue as $stylesheet ) {
		wp_dequeue_style( $stylesheet );
	}

	remove_all_actions( 'wp_print_styles' );

	remove_action( 'wp_head', array( 'Jetpack_Custom_CSS', 'link_tag' ), 101 );

	// todo remove remote-css?
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
 * Print the saved badge CSS
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
