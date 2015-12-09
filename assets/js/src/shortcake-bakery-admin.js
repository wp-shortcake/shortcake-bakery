var _                  = require('underscore');
var $                  = require('jquery');
var wp                 = require('wp');
var Backbone           = require('Backbone');
var ShortcakeBakery    = require('ShortcakeBakery');
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

