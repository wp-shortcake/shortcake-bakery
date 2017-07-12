var _                  = require('underscore');
var $                  = require('jquery');
var wp                 = require('wp');
var Backbone           = require('Backbone');
var ShortcakeBakery    = require('ShortcakeBakery');

/**
 * Support JS for Shortcake Bakery shortcodes.
 */
jQuery( document ).ready( function ( $ ) {

	/**
	 * Attach JS hooks for shortcodes that need them.
	 *
	 */
	if ( typeof wp !== 'undefined' &&
		 typeof wp.shortcake !== 'undefined' &&
		 typeof wp.shortcake.hooks !== 'undefined' ) {

		/* Optional fields for GoogleDocs shortcode, displayed conditionally depending on the "type" field */
		var gdocUrlField = [ ShortcakeBakeryShortcodes.shortcodes['Shortcake_Bakery\\Shortcodes\\GoogleDocs'], 'url' ].join('.');
		var gdocFields = {
			all:          [ 'headers', 'start', 'loop', 'delayms' ],
			spreadsheets: [ 'headers' ],
			presentation: [ 'start', 'loop', 'delayms' ]
		};

		wp.shortcake.hooks.addAction( gdocUrlField, function( changed, collection, shortcode ) {
			if ( 'undefined' === typeof changed.value ) {
				return;
			}

			var docUrl = changed.value,
				docUrlParts = docUrl.split('/'),
				docType = docUrlParts.length > 3 ? docUrlParts[3] : false;

			_.each( gdocFields.all, function( fieldname ) {
				var field = sui.views.editAttributeField.getField( collection, fieldname );
				if ( 'undefined' !== typeof gdocFields[ docType ] && _.contains( gdocFields[ docType ], fieldname ) ) {
					field.$el.show()
				} else {
					field.$el.hide();
				}
			} );
		 } );
	}

});

