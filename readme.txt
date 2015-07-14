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
Used alongside [Shortcake](https://wordpress.org/plugins/shortcode-ui/), Shortcake Bakery adds a fine selection of shortcodes to your WordPress site.

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

== Changelog ==

= 0.1.0 (July 16, 2015) =

* Initial release.
* [Full release notes](#)
