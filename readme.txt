=== Shortcake Bakery ===
Contributors: fusionengineering, davisshaver, danielbachhuber
Tags: shortcodes, Facebook, Infogram, Playbuzz, Rap Genius, Scribd
Requires at least: 4.2
Tested up to: 4.7.4
Stable tag: 0.2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A fine selection of Shortcake-powered shortcodes.

== Description ==

Shortcake Bakery adds a fine selection of shortcodes to your WordPress site. Use with [Shortcake](https://wordpress.org/plugins/shortcode-ui/) for the optimal experience.

The follow shortcodes are now available for your use within the content field:

- Image Comparison `[image-comparison left="9" right="10" position="center"]`
- Facebook `[facebook url="https://www.facebook.com/willpd/posts/1001217146572688"]`
- iFrames (requires code-level configuration of accepted domains) `[iframe src="http://www.buzzfeed.com"]`
- Infogram `[infogram url="http://infogr.am/washington_marijuana_sales"]`
- PDF's (requires PDF be served locally or from domain with `Access-Control-Allow-Origin` header) `[pdf url="https://assets.fusion.net/edit/pdfs/the_interview_budget_excerpts.pdf"]`
- Playbuzz `[playbuzz url="https://www.playbuzz.com/Fusion/5-mind-blowing-facts-about-cloning-from-jurassic-park-youll-never-believe-actually-exist-in-real"]`
- Rap Genius `[rap-genius]`
- Scribd `[scribd url="http://www.scribd.com/doc/269993358/Transgender-Care-Memorandum"]`
- Scripts (requires code-level configuration of accepted domains) `[script src="https://ajax.googleapis.com/ajax/libs/threejs/r69/three.min.js"]`

See the [Installation](#Installation) section for code-level configuration details. Get involved with the project and [submit your own shortcodes](https://github.com/fusioneng/shortcake-bakery) on Github.

== Installation ==

It's a plugin! Install it like any other.

Most of the shortcodes work out of the box, but you'll need to whitelist any domains you want to be eligible for script and iFrame tag use.

`
add_filter( 'shortcake_bakery_whitelisted_script_domains', function(){
	return array(
		'ajax.googleapis.com',
	);
});
`

`
add_filter( 'shortcake_bakery_whitelisted_iframe_domains', function(){
	return array(
		'buzzfeed.com',
	);
});
`

== Screenshots ==

1. Shortcodes are accessible through the Insert Element screen, exposed in the media library.
2. Shortcodes preview in the visual editor, with a quick edit button to provide easy access to attribute fields.
3. The image comparison shortcode is powered by JuxtaposeJS, a tool built by the Northwestern University Knight Lab.
4. We've added an Infogram shortcode that accepts public Infogram URL's.
5. Scribd shortcodes let you embed documents easily in your WordPress content.
6. The script shortcode lets you embed whitelisted sources.
7. Same with the iFrame shortcode.
8. The RapGenius shortcode doesn't do anything in the admin – but on the frontend, it adds annotations to your post.
9. The PDF shortcode wraps your document in a nifty viewer tool.
10. Most Facebook URL's can be embedded with ease.
11. Playbuzz quizs can be embedded, with a few options supported.

== Changelog ==

= 0.2.0 (April 17, 2017) =

This release includes 14 new shortcodes, and some major new features for users.

* Add "Add embed" media button; allow users to enter an arbitrary code from a provider, and convert it to a shortcode if that shortcode is available (as defined by the shortcode's "reversal" method). Developers: note that this only works with shortcodes that extend \Shortcode_Bakery\Shortcode and implement the reversal()" method.
* Updated the format of Instagram embeds.
* Added several new URL patterns for Facebook embeds. Groups, Pages, and videos are now supported in addition to Posts.
* New shortcode: `[soundcloud]` Shortcode for Soundcloud embeds.
* New shortcode: `[pdf]` Embed local or external PDF documents using pdf.js.
* New shortcode: `[silk]` Shortcode for Silk embeds
* New shortcode: `[flickr]` Shortcode for Flickr embeds.
* New shortcode: `[instagram]` Shortcode for Instagram embeds.
* New shortcode: `[livestream]` Shortcode for Livestream embeds.
* New shortcode: `[abc-news]` Shortcode for ABC News embeds.
* New shortcode: `[guardian]` Shortcode for embeds from The Guardian.
* New shortcode: `[giphy]` Shortcode for Giphy embeds.
* New shortcode: `[vine]` Shortcode for Vine embeds.
* New shortcode: `[vimeo]` Shortcode for Vimeo embeds.
* New shortcode: `[videoo]` Shortcode for Videoo embeds.
* New shortcode: `[google-docs]` Shortcode for Google Docs embeds.
* Improved UI for selecting post elements; consistant icons for all embeds.
* Bug fix: Allow Giphy embeds with hyp0hens in URLs.
* Added Japanese translation
* Allow the source of iframe and script embeds to be filtered, for SSL compatability.

= 0.1.0 (July 17, 2015) =

* Initial release.
* [Full release notes](http://fusion.net/story/167993/introducing-shortcake-bakery-a-selection-of-fine-shortcodes/)
