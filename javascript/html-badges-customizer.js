wp.customize.CampTixHtmlBadgesCustomizer = ( function( $, api ) {
	'use strict';

	var self = {
		sectionID    : 'section_camptix_html_badges',
		cssSettingID : 'setting_camptix_html_badge_css',
		siteURL      : window.location.protocol + '//' + window.location.hostname,
		cmEditor     : null
	};

	self.badgesPageURL = self.siteURL + '?camptix-badges';

	$.extend( self, cbgHtmlCustomizerData );
	window.cbgHtmlCustomizerData = null;

	/**
	 * Initialize
	 */
	self.initialize = function() {
		try {
			api.section( self.sectionID ).container.bind( 'expanded',  self.loadBadgesPage   );
			api.section( self.sectionID ).container.bind( 'collapsed', self.unloadBadgesPage );
			api.section( self.sectionID ).container.bind( 'expanded',  self.setupCodeMirror  );

			$( '#customize-control-cbg_control_print_badges' ).find( 'input[type=button]' ).click( self.printBadges );
			$( '#customize-control-cbg_control_reset_css'    ).find( 'input[type=button]' ).click( self.resetCSS    );
		} catch( exception ) {
			self.log( exception );
		}
	};

	/**
	 * Load the Badges pages when navigating to our Section
	 *
	 * @param {object} event
	 */
	self.loadBadgesPage = function( event ) {
		try {
			if ( self.badgesPageURL !== api.previewer.previewUrl.get() ) {
				api.previewer.previewUrl.set( self.badgesPageURL );
			}
		} catch ( exception ) {
			self.log( exception );
		}
	};

	/**
	 * Unload the Badges page when navigating away from our Section
	 *
	 * @param {object} event
	 */
	self.unloadBadgesPage = function( event ) {
		if ( self.badgesPageURL === api.previewer.previewUrl.get() ) {
			api.previewer.previewUrl.set( self.siteURL );
		}
	};

	/**
	 * Replace the plain textarea with a nice CSS editor
	 *
	 * @param {object} event
	 */
	self.setupCodeMirror = function( event ) {
		try {
			self.cmEditor = CodeMirror.fromTextArea(
				$( '#customize-control-setting_camptix_html_badge_css' ).find( 'textarea' ).get(0),
				{
					tabSize        : 2,
					indentWithTabs : true,
					lineWrapping   : true
				}
			);

			self.cmEditor.setSize( null, 'auto' );

			self.cmEditor.on( 'change', function() {
				api( self.cssSettingID ).set( self.cmEditor.getValue() );
			} );
		} catch( exception ) {
			self.log( exception );
		}
	};

	/**
	 * Print the badges in the Previewer frame
	 *
	 * @param {object} event
	 */
	self.printBadges = function( event ) {
		try {
			window.frames[0].print();
		} catch( exception ) {
			self.log( exception );
		}
	};

	/**
	 * Reset to the default CSS
	 *
	 * @param {object} event
	 */
	self.resetCSS = function( event ) {
		try {
			api( self.cssSettingID ).set( self.defaultCSS );    // todo high - xss?
			self.cmEditor.setValue( self.defaultCSS );          // todo high - xss?
		} catch( exception ) {
			self.log( exception );
		}
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
