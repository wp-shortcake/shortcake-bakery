(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
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
