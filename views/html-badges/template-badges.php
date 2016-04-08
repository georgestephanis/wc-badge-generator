<?php

namespace CampTix\Badge_Generator\HTML;
defined( 'WPINC' ) or die();

?><!DOCTYPE html>
<html id="camptix-badge-generator" <?php language_attributes(); ?>>

<?php
/*
 // todo probabely break this up into differnt parts of name them like customizer-section.php, etc
// use get_header and get_footer? or at least call wp_head and wp_footer? maybe not b/c don't want scripts, etc
// use doctype etc from twentysixteen
*/

/** @var $camptix CampTix_Plugin */

extract( get_template_variables() );    // todo bad? make sure don't overwrite globals. any way to

?>

<head>
	<title><?php _e( 'CampTix Badges' ); ?></title>
	<!-- todo have to set via wp to have show up in customerizer? -->

		<meta charset="<?php bloginfo( 'charset' ); ?>">


	<style id="badges-css">
		/* Placeholder for dynamically-added styles */
	</style>

	<link rel="stylesheet" href="<?php echo esc_url( $cbg_page_css_url ); ?>">

	<?php if ( defined( 'JETPACK__PLUGIN_FILE' ) ) : ?>
		<!-- todo create vars in caller -->
		<script src="<?php echo esc_url( plugins_url( 'modules/custom-css/custom-css/js/codemirror.min.js', JETPACK__PLUGIN_FILE ) ); ?>"></script>
		<link rel="stylesheet" href="<?php echo esc_url( plugins_url( 'modules/custom-css/custom-css/css/codemirror.min.css', JETPACK__PLUGIN_FILE ) ); ?>">
	<?php endif; ?>
</head>


<body>
	<?php
	$attendees = get_posts( array(
								'post_type'      => 'tix_attendee',
								'posts_per_page' => -1,
								'post_status'    => array( 'publish', 'pending' ),
								'orderby'        => 'title',
								'fields'         => 'ids', // ! no post objects
								'cache_results'  => false,
							) );

	if ( ! is_array( $attendees ) || count( $attendees ) < 1 ) {
		echo esc_html__( 'No attendees found to make badges for.' ) . "\r\n</body>\r\n</html>";
		return;
	}
?>
	<p id="use-firefox"><?php esc_html_e( 'Make sure to use Firefox to print these badges.  Some other browsers (like Chrome) don\'t respect some CSS properties that we use to specify where page breaks should be.' ); ?></p>

	<form id="style-tweak">
		<label for="styletweak-css"><?php esc_html_e( 'You can modify the CSS for the badges here -- if you make a mistake, you can reset it back to the original markup.  Changes are not saved if you leave this page, so you may want to save them locally or on gist.github.com' ); ?></label>
		<textarea id="style-tweak-css"></textarea>
		<input id="style-tweak-update" type="submit" value="<?php esc_attr_e( 'Update' ); ?>" />
		<input id="style-tweak-reset" type="reset" value="<?php esc_attr_e( 'Reset' ); ?>" />
	</form>

	<br /><br /><br />
<?php
	// Disable object cache for prepared metadata.
	$camptix->filter_post_meta = $camptix->prepare_metadata_for( $attendees );

	foreach ( $attendees as $attendee_id ) :
		$first   = get_post_meta( $attendee_id, 'tix_first_name', true );
		$last    = get_post_meta( $attendee_id, 'tix_last_name', true );
		$name    = $camptix->format_name_string( '<span class="tix-first">%first%</span> <span class="tix-last">%last%</span>', esc_html( $first ), esc_html( $last ) );
		$email   = get_post_meta( $attendee_id, 'tix_email', true );
		$avatar  = get_avatar_url( $email, array( 'size' => 600 ) );
		?>
		<article class="attendee">
			<section class="back">
				<header>
					<?php if ( has_custom_logo() ) : ?>
						<?php the_custom_logo(); ?>
					<?php else : ?>
						<h1><?php bloginfo( 'name' ); ?></h1>
					<?php endif; ?>
				</header>
				<figure>
					<img src="<?php echo esc_url( $avatar ); ?>" />
					<figcaption>
						<h3 class="name"><?php echo $name; /* already escaped above */ ?></h3>
					</figcaption>
				</figure>
			</section>
			<section class="front">
				<div class="holepunch">&#9421;</div>
				<header>
					<?php if ( has_custom_logo() ) : ?>
						<?php the_custom_logo(); ?>
					<?php else : ?>
						<h1><?php bloginfo( 'name' ); ?></h1>
					<?php endif; ?>
				</header>
				<figure>
					<img src="<?php echo esc_url( $avatar ); ?>" />
					<figcaption>
						<h3 class="name"><?php echo $name; /* already escaped above */ ?></h3>
					</figcaption>
				</figure>
			</section>
		</article>
	<?php endforeach; ?>

	<script id="badges-css-original" type="text/css">
		<?php readfile( dirname( dirname( __DIR__ ) ) . '/css/html-badges.css' ); ?>
	</script>
	<script>
	(function(d){
		var styleElement     = d.getElementById( 'badges-css' ),
			stylesOriginal   = d.getElementById( 'badges-css-original' ).innerText.trim(),
			styleTweak       = d.getElementById( 'style-tweak' ),
			styleTweakCss    = d.getElementById( 'style-tweak-css' ),
			styleTweakUpdate = d.getElementById( 'style-tweak-update' ),
			styleTweakReset  = d.getElementById( 'style-tweak-reset' );

		styleElement.textContent = stylesOriginal;
		styleTweakCss.value      = stylesOriginal;

		<?php if ( defined( 'JETPACK__PLUGIN_FILE' ) ) : ?>

		var cmEditor = CodeMirror.fromTextArea( styleTweakCss, {
			lineNumbers    : true,
			tabSize        : 2,
			indentWithTabs : true,
			lineWrapping   : true
		});

		<?php else : ?>

		styleTweakCss.addEventListener( 'keydown', function(e) {
		    if( e.keyCode === 9 ) { // tab was pressed
		        // get caret position/selection
		        var start  = this.selectionStart,
		        	end    = this.selectionEnd,
		        	target = e.target,
		        	value  = target.value;

		        // set textarea value to: text before caret + tab + text after caret
		        target.value = value.substring( 0, start )
		                    + '\t'
		                    + value.substring( end );

		        // put caret at right position again (add one for the tab)
		        this.selectionStart = this.selectionEnd = start + 1;

		        // prevent the focus lose
		        e.preventDefault();
		    }
		} );

		<?php endif; ?>

		styleTweak.addEventListener( 'submit', function(e){
			e.preventDefault();
			styleElement.textContent = styleTweakCss.value;
		});

		styleTweak.addEventListener( 'reset', function(e){
			e.preventDefault();
			styleElement.textContent = stylesOriginal;
			<?php if ( defined( 'JETPACK__PLUGIN_FILE' ) ) : ?>
				cmEditor.setValue( stylesOriginal );
			<?php else : ?>
				styleTweakCss.value = stylesOriginal;
			<?php endif; ?>
		});

	})(document);
	</script>
</body>
</html>
