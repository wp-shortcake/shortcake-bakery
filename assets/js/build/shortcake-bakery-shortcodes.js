(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
(function (global){
var _                  = (typeof window !== "undefined" ? window['_'] : typeof global !== "undefined" ? global['_'] : null);
var $                  = (typeof window !== "undefined" ? window['jQuery'] : typeof global !== "undefined" ? global['jQuery'] : null);
var wp                 = (typeof window !== "undefined" ? window['wp'] : typeof global !== "undefined" ? global['wp'] : null);
var Backbone           = (typeof window !== "undefined" ? window['Backbone'] : typeof global !== "undefined" ? global['Backbone'] : null);
var ShortcakeBakery    = (typeof window !== "undefined" ? window['ShortcakeBakery'] : typeof global !== "undefined" ? global['ShortcakeBakery'] : null);

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


}).call(this,typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{}]},{},[1]);
