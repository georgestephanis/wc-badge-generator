wp.customize.CampTixHtmlBadgesCustomizer = ( function( $, api ) {
	'use strict';

	var self = {
		cmEditor : null
	};

	// todo add try/catch to event handlers
	// todo jsdoc for all
	// todo test in all browsers

	self.initialize = function() {
		try {
			api.section( 'section_camptix_html_badges' ).container.bind( 'expanded', self.setupCodeMirror );

			$( '#customize-control-cbg_control_print_badges' ).find( 'input[type=button]' ).click( self.printBadges );
			$( '#customize-control-cbg_control_reset_css'    ).find( 'input[type=button]' ).click( self.resetCSS    );
		} catch( exception ) {
			self.log( exception );
		}
	};

	self.setupCodeMirror = function( event ) {
		self.cmEditor = CodeMirror.fromTextArea(
			$( '#customize-control-setting_camptix_html_badge_css' ).find( 'textarea' ).get(0),
			{
				tabSize        : 2,
				indentWithTabs : true,
				lineWrapping   : true
			}
		);

		// todo set height? seems to be done automatically, but maybe want it 100% insetad of fixed 500px
		//self.cmEditor.setSize( null, 400 );  // todo high - probably don't need, or want to do 100%

		// Update the Customizer textarea when the CodeMirror textarea changes
		self.cmEditor.on( 'change', _.bind( function( editor ) {
			api( 'setting_camptix_html_badge_css' ).set( editor.getValue() );
		}, this ) );

		// todo modularize some of this?
	};

	self.printBadges = function( event ) {
		window.frames[0].print();
	};

	self.resetCSS = function( event ) {
		var defaultCSS = 'body { background-color: blue; }';    // todo
		// todo i think there's a way through api() to reset to orig value, or at least retrieve orig value and set()
			// nope, doesn't look like it's available in JS anywhere. just make your own by using data attribute or a js var or something
			// can use new 4.5 include_script whatever instead of localize_script

		api( 'setting_camptix_html_badge_css' ).set( defaultCSS );
		self.cmEditor.setValue( defaultCSS );
	};

	/**
	 * The CampTix HTML Badges panel
	 */
	api.sectionConstructor.cbgSection = api.Section.extend( {
		// todo can/should probably redo this as a function that gets called from self.init. prob no need to extend Section?

		/**
		 * Open this section when it's directly requested via a URL parameter
		 */
		ready : function() {
			var urlParams = self.getUrlParams( window.location.href );

			// todo could just do this with string search and get rid of the function?


			if ( ! urlParams.hasOwnProperty( 'url' ) ) {    // todo maybe need to be mroe specific, make sure it has camptix-badges in url
				// todo when open customizer directly, then click on section, need to set on previerwe url to `{site_url}?camptix-badges`
				// might need to be somewhere different than here, like onclick handler or something
				// window.parent.location = ...;

				// when opening customize.php manually and browsing to the section, detect if url has query param, if doesn't, then redirect previewer frame to that url

				// when navigating away from this section, need to refresh previewer w/out our URL, to add styles back etc

				// add click handler on section, or maybe the API already provides a way for that
					// maybe check section.expanded, listen for click event, override onChangeExpanded
					// window.parent.location = 'https://2014.content.wordcamp.dev/wp-admin/customize.php?camptix-html-badges&url=https%3A%2F%2F2014.content.wordcamp.dev%3Fcamptix-badges';   // todo dynamic
			}
		}
	} );

	/**
	 * todo make dry with site cloner?
	 * todo still using this now that have autofocus? maybe for url param
	 *
	 * Parse the URL parameters
	 *
	 * Based on https://stackoverflow.com/a/2880929/450127
	 *
	 * @param {string} url
	 *
	 * @returns {object}
	 */
	self.getUrlParams = function( url ) {
		var match, questionMarkIndex, query,
			urlParams = {},
			pl        = /\+/g,  // Regex for replacing addition symbol with a space
			search    = /([^&=]+)=?([^&]*)/g,
			decode    = function ( s ) {
				return decodeURIComponent( s.replace( pl, " " ) );
			};

		questionMarkIndex = url.indexOf( '?' );

		if ( -1 === questionMarkIndex ) {
			return urlParams;
		} else {
			query = url.substring( questionMarkIndex + 1 );
		}

		while ( match = search.exec( query ) ) {
			urlParams[ decode( match[ 1 ] ) ] = decode( match[ 2 ] );
		}

		return urlParams;
	};

	/**
	 * Log a message to the console
	 * 
	 * @todo make DRY with CampTixHtmlBadgesPreviewer
	 *
	 * @param {*} error
	 */
	self.log = function( error ) {
		var messageLabel = 'CampTix HTML Badges: ';

		if ( ! window.console ) {
			return;
		}

		if ( 'string' === typeof error ) {
			console.log( messageLabel + error );
		} else {
			console.log( messageLabel, error );
		}
	};

	api.bind( 'ready', self.initialize );
	return self;

} ( jQuery, wp.customize ) );
