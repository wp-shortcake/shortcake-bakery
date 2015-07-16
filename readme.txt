=== Shortcake Bakery ===
Contributors: fusionengineering
Tags: comments, spam
Requires at least: 3.0.1
Tested up to: 3.4
Stable tag: 4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A fine selection of Shortcake-powered shortcodes.

== Description==
Shortcake Bakery adds a fine selection of shortcodes to your WordPress site. Use with [Shortcake](https://wordpress.org/plugins/shortcode-ui/) for the optimal experience.

The follow shortcodes are now available for your use within the content field:

- Image Comparison `[image-comparison left="9" right="10" position="center"]`
- Facebook `[facebook url="https://www.facebook.com/willpd/posts/1001217146572688"]`
- iFrames (requires some configuration) `[iframe src="http://www.buzzfeed.com"]`
- Infogram `[infogram url="http://infogr.am/washington_marijuana_sales"]`
- PDF's `[pdf url="http://www.gpo.gov/fdsys/pkg/BILLS-114hr2048enr/pdf/BILLS-114hr2048enr.pdf"]`
- Playbuzz `[playbuzz url="https://www.playbuzz.com/Fusion/5-mind-blowing-facts-about-cloning-from-jurassic-park-youll-never-believe-actually-exist-in-real"]`
- Rap Genius `[rap-genius]`
- Scribd `[scribd url="http://www.scribd.com/doc/269993358/Transgender-Care-Memorandum"]`
- Scripts (requires some configuration) `[script src="https://ajax.googleapis.com/ajax/libs/threejs/r69/three.min.js"]`

== Installation ==
It's a plugin! Install it like any other. 

Most of the shortcodes work out of the box, but you'll need to whitelist any domains you want to be eligible for script and iFrame tag use.

```php
	add_filter( 'shortcake_bakery_whitelisted_script_domains', function(){
		return array(
			'ajax.googleapis.com',		
		);
	});
```

```php
	add_filter( 'shortcake_bakery_whitelisted_iframe_domains', function(){
		return array(
			'buzzfeed.com',		
		);
	});
```

== Frequently Asked Questions ==

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

= 0.1.0 (???) =

* Initial release.
* [Full release notes](#)
