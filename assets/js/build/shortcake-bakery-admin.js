(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
(function (global){
var wp = (typeof window !== "undefined" ? window.wp : typeof global !== "undefined" ? global.wp : null);
var Backbone = (typeof window !== "undefined" ? window.Backbone : typeof global !== "undefined" ? global.Backbone : null);
var $ = (typeof window !== "undefined" ? window.jQuery : typeof global !== "undefined" ? global.jQuery : null);

var addEmbedController = wp.media.controller.State.extend({

	initialize: function(){
		this.props = new Backbone.Model({
			custom_embed_code: '',
			doing_ajax: false,
			no_matches: false
		});

		this.props.on( 'change:custom_embed_code', this.refresh, this );
	},

	refresh: function() {
		if ( this.frame && this.frame.toolbar ) {
			this.frame.toolbar.get().refresh();
		}
	},

	embedReverse: function() {
		var self = this;

		this.props.set( 'doing_ajax', true );
		this.refresh();

		var promise = jQuery.post(ajaxurl + '?action=shortcake_bakery_embed_reverse', {
			custom_embed_code: this.props.get( 'custom_embed_code' ),
			post_id: wp.media.view.settings.post.id,
			_wpnonce: ShortcakeBakery.nonces.customEmbedReverse
		});

		promise.then(function( response ) {
			self.props.set( 'doing_ajax', false );

			if ( response.success ) {
				send_to_editor( response.reversal );
				self.reset();
				self.frame.close();
			} else {
				self.props.set( 'no_matches', true );
				self.refresh();
			}
		});
	},

});

wp.media.controller.addEmbed = addEmbedController;
module.exports = addEmbedController;

}).call(this,typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{}],2:[function(require,module,exports){
(function (global){
var _               = (typeof window !== "undefined" ? window._ : typeof global !== "undefined" ? global._ : null);
var wp              = (typeof window !== "undefined" ? window.wp : typeof global !== "undefined" ? global.wp : null);
var ShortcakeBakery = (typeof window !== "undefined" ? window.ShortcakeBakery : typeof global !== "undefined" ? global.ShortcakeBakery : null);

var addEmbedToolbar = wp.media.view.Toolbar.extend({
	initialize : function() {
		_.defaults(this.options, {
			event: 'embedReverse',
			clone: false,
			items: {
				embedReverse: {
					text: ShortcakeBakery.text.insertButton,
					style: 'primary',
					priority: 80,
					requires: false,
					click: this.embedReverse
				}
			}
		});

		// Call 'initialize' directly on the parent class.
		wp.media.view.Toolbar.prototype.initialize.apply(this, arguments);
	},

	refresh : function() {
		var custom_embed_code = this.controller.state().props.get('custom_embed_code');
		var doing_ajax = this.controller.state().props.get('doing_ajax');

		this.get( 'embedReverse' ).model.set( 'disabled', ! custom_embed_code || doing_ajax );

		wp.media.view.Toolbar.prototype.refresh.apply( this, arguments );
	},

	embedReverse: function() {
		this.controller.state().embedReverse();
	}
});

wp.media.view.Toolbar.addEmbed = addEmbedToolbar;
module.exports = addEmbedToolbar;

}).call(this,typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{}],3:[function(require,module,exports){
(function (global){
var wp = (typeof window !== "undefined" ? window.wp : typeof global !== "undefined" ? global.wp : null);

var addEmbedView = wp.media.View.extend({
	className: 'media-add-embed',

	// bind view events
	events: {
		'input':  'custom_update',
		'keyup':  'custom_update',
		'change': 'custom_update'
	},

	initialize: function() {

	    // create an input
		var form = jQuery( '<div></div>', {
			class: "embed-reverse"
		});

		var label = jQuery( '<label></label>', {
			class: "custom_embed"
		}).text( ShortcakeBakery.text.customEmbedLabel );

	    this.input = jQuery( '<textarea></textarea>', {
			name: 'custom_embed_code',
			class: 'custom-embed-entry',
			value: this.model.get('custom_embed_code')
		});

		this.noMatches = jQuery( '<p></p>', {
			class: 'error',
			style: 'display: none'
		}).text( ShortcakeBakery.text.noReversalMatches );

		label.appendTo(form);
		this.input.appendTo(form);
		this.noMatches.appendTo(form);

		// insert it in the view
	    this.$el.append(form);

	    // re-render the view when the model changes
	    this.model.on( 'change:custom_embed_code', this.render, this );
	    this.model.on( 'change:no_matches', this.toggle_no_matches, this );
	},

	render: function(){
	    this.input.value = this.model.get('custom_embed_code');
	    return this;
	},

	toggle_no_matches: function() {
		this.noMatches.toggle( this.model.get('no_matches') );
		return this;
	},

	custom_update: function( event ) {
		this.model.set( 'custom_embed_code', event.target.value );
	}
});

wp.media.view.addEmbed = addEmbedView;
module.exports = addEmbedView;

}).call(this,typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{}],4:[function(require,module,exports){
(function (global){
var _                  = (typeof window !== "undefined" ? window._ : typeof global !== "undefined" ? global._ : null);
var $                  = (typeof window !== "undefined" ? window.jQuery : typeof global !== "undefined" ? global.jQuery : null);
var wp                 = (typeof window !== "undefined" ? window.wp : typeof global !== "undefined" ? global.wp : null);
var Backbone           = (typeof window !== "undefined" ? window.Backbone : typeof global !== "undefined" ? global.Backbone : null);
var ShortcakeBakery    = (typeof window !== "undefined" ? window.ShortcakeBakery : typeof global !== "undefined" ? global.ShortcakeBakery : null);
var addEmbedView       = require('./addEmbed/view');
var addEmbedToolbar    = require('./addEmbed/toolbar');
var addEmbedController = require('./addEmbed/controller');

/**
 * Adds an "Insert embed code" form in WordPress's Add Media modal
 *
 * Adds a form with a single input, "custom_embed_code". Submitting that form
 * will run the embed code inserted through Shortcake Bakery's reversal filter.
 * If any "reversals" are found, they will be made, and the resulting content
 * sent to the editor. If not, a warning message will be displayed, saying that
 * no matching post elements could be found.
 */
jQuery( document ).ready( function ( $ ) {

	var postMediaFrame = wp.media.view.MediaFrame.Post;

	var mediaFrame = postMediaFrame.extend( {

		initialize: function() {

			postMediaFrame.prototype.initialize.apply( this, arguments );

			var id = 'shortcake-bakery-embed';

			this.states.add([
				new addEmbedController({
					id:         id,
					menu:       'default', // menu event = menu:render:default
					content:    id + '-content-insert',
					title:      ShortcakeBakery.text.addEmbed,
					priority:   100,
					toolbar:    id + '-toolbar',
					type:       'link'
				})
			]);

			this.on( 'content:render:' + id + '-content-insert', _.bind( this.renderEmbedReversalFrame, this, 'shortcake-bakery-embed', 'insert' ) );
			this.on( 'toolbar:create:' + id + '-toolbar', this.createEmbedReversalToolbar, this );
			this.on( 'toolbar:render:' + id + '-toolbar', this.renderEmbedReversalToolbar, this );
			this.on( 'menu:render:default', this.renderEmbedReversalMenu );
		},

		events: function() {
			return _.extend( {}, postMediaFrame.prototype.events, {
			} );
		},

		renderEmbedReversalFrame : function( id, tab ) {
			this.$el.addClass('hide-router');

			var view = new addEmbedView({
				controller: this,
				model: this.state().props
			});

			this.content.set( view );
		},

		renderEmbedReversalToolbar: function( toolbar ) {},

		createEmbedReversalToolbar : function( toolbar ) {
			toolbar.view = new addEmbedToolbar({
				controller: this
			});
		},

		renderEmbedReversalMenu: function( view ) {
			// Add a menu separator link.
			view.set({
				'shortcake-bakery-embed-separator': new wp.media.View({
					className: 'separator',
					priority: 105
				})
			});

		},

		insertAction: function() {
			this.controller.state().insert();
		},

	} );

	wp.media.view.MediaFrame.Post = mediaFrame;

	$(document.body)
		.on( 'click.insert-embed-button', '.shortcake-bakery-insert-embed', function( event ) {
			var elem = $( event.currentTarget ),
				editor = elem.data('editor'),
				options = {
					frame:    'post',
					state:    'shortcake-bakery-embed',
					title:    ShortcakeBakery.text.addEmbed
				};

			event.preventDefault();

			// Remove focus from the `.insert-embed` button.
			// Prevents Opera from showing the outline of the button
			// above the modal.
			//
			// See: https://core.trac.wordpress.org/ticket/22445
			elem.blur();

			wp.media.editor.open( editor, options );
		});

	/**
	 * Attach JS hooks for shortcodes that need them.
	 *
	 */
	if ( typeof wp !== 'undefined' &&
		 typeof wp.shortcake !== 'undefined' &&
		 typeof wp.shortcake.hooks !== 'undefined' ) {

		/* Optional fields for GoogleDocs shortcode, displayed conditionally depending on the "type" field */
		var gdocUrlField = [ ShortcakeBakery.shortcodes['Shortcake_Bakery\\Shortcodes\\GoogleDocs'], 'url' ].join('.');
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
},{"./addEmbed/controller":1,"./addEmbed/toolbar":2,"./addEmbed/view":3}]},{},[4]);
