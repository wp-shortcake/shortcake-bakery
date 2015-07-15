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

== Installation ==
It's a plugin! Install it like any other. 

The follow shortcodes are now available for your use within the content field:
- Facebook
- Infogram
- PDF's
- Playbuzz
- Rap Genius
- Scribd
- Scripts (requires some configuration)

Most of the shortcodes work out of the box, but you'll need to whitelist any domains you want to be eligible for script tag use.

```php
	add_filter( 'shortcake_bakery_whitelisted_script_domains', function(){
		return array(
			'3vot.com',		
		);
	});
```

== Frequently Asked Questions ==

== Screenshots ==

1. Shortcodes are accessible through the Insert Element screen, exposed in the media library.
2. Shortcodes preview in the visual editor, with a quick edit button to provide easy access to attribute fields.
3. The image comparison shortcode is powered by JuxtaposeJS, a tool built by the Northwestern University Knight Lab. Sample: `[image-comparison left="9" right="10" position="center"]`
4. We've added an Infogram shortcode that accepts public Infogram URL's. Sample: `[infogram url="http://infogr.am/washington_marijuana_sales"]`
5. Scribd shortcodes let you embed documents easily in your WordPress content. Sample: `[scribd url="http://www.scribd.com/doc/269993358/Transgender-Care-Memorandum"]`
6. The script shortcode lets you embed whitelisted sources. Sample `[script src="https://ajax.googleapis.com/ajax/libs/threejs/r69/three.min.j"]`

== Changelog ==

= 0.1.0 (???) =

* Initial release.
* [Full release notes](#)
