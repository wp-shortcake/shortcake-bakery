module.exports = function( grunt ) {

	'use strict';
	var banner = '/**\n * <%= pkg.homepage %>\n * Copyright (c) <%= grunt.template.today("yyyy") %>\n * This file is generated automatically. Do not edit.\n */\n';
	// Project configuration
	grunt.initConfig( {

		pkg:    grunt.file.readJSON( 'package.json' ),

		addtextdomain: {
			options: {
				textdomain: 'shortcake-bakery',
			},
			target: {
				files: {
					src: [ '*.php', '**/*.php', '!node_modules/**', '!php-tests/**', '!bin/**' ]
				}
			}
		},

		browserify : {
			dist: {
				src : ['assets/js/src/shortcake-bakery-admin.js'],
				dest : 'assets/js/build/shortcake-bakery-admin.js',
				options: {
					transform: ['browserify-shim']
				}
			}
		},

		wp_readme_to_markdown: {
			options: {
				screenshot_url: 'https://s.w.org/plugins/{plugin}/{screenshot}.png',
			},
			your_target: {
				files: {
					'README.md': 'readme.txt'
				}
			},
		},

		phpcs: {
			plugin: {
				src: './'
			},
			options: {
				bin: "vendor/bin/phpcs --extensions=php --ignore=\"*/vendor/*,*/node_modules/*\"",
				standard: "phpcs.ruleset.xml"
			}
		},

		sass: {
			dist: {
				files: {
					'assets/css/shortcake-bakery.css' : 'assets/css/sass/shortcake-bakery.scss',
				},
				options: {
					sourceMap: true
				}
			}
		},

		watch: {
			dev: {
				files: [ '*.php' ],
				tasks: [ 'phpcs' ]
			},
			scripts: {
				files: [ 'assets/js/src/**/*.js' ],
				tasks: [ 'browserify' ]
			},
			styles: {
				files: [ 'assets/css/sass/**/*.scss' ],
				tasks: [ 'sass' ]
			}
		},

		makepot: {
			target: {
				options: {
					domainPath: '/languages',
					mainFile: 'shortcake-bakery.php',
					potFilename: 'shortcake-bakery.pot',
					potHeaders: {
						poedit: true,
						'x-poedit-keywordslist': true
					},
					type: 'wp-plugin',
					updateTimestamp: true
				}
			}
		},
	} );

	grunt.loadNpmTasks( 'grunt-browserify' );
	grunt.loadNpmTasks( 'grunt-phpcs' );
	grunt.loadNpmTasks( 'grunt-sass' );
	grunt.loadNpmTasks( 'grunt-wp-i18n' );
	grunt.loadNpmTasks( 'grunt-wp-readme-to-markdown' );
	grunt.loadNpmTasks( 'grunt-contrib-watch' );
	grunt.registerTask( 'i18n', ['addtextdomain', 'makepot'] );
	grunt.registerTask( 'readme', ['wp_readme_to_markdown']);
	grunt.registerTask( 'default', ['sass','browserify']);


	grunt.util.linefeed = '\n';

};
