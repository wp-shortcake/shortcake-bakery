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
				files: {
					'assets/js/build/shortcake-bakery-add-embed-media-frame.js': ['assets/js/src/shortcake-bakery-add-embed-media-frame.js'],
					'assets/js/build/shortcake-bakery-shortcodes.js': ['assets/js/src/shortcake-bakery-shortcodes.js'],
				},
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

		wget: {
			basic: {
				files: {
					'assets/lib/pdfjs.zip': 'https://github.com/mozilla/pdf.js/archive/gh-pages.zip'
				}
			},
		},

		clean: {
			prepare: {
				src: 'assets/lib/pdfjs/'
			},
			cleanup: {
				src: 'assets/lib/pdfjs.zip'
			}
		},

		unzip: {
			'assets/lib/': 'assets/lib/pdfjs.zip'
		},

		rename: {
			main: {
				files: [
					{
						src: ['assets/lib/pdf.js-gh-pages/'],
						dest: 'assets/lib/pdfjs/'
					}
				]
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
	grunt.loadNpmTasks( 'grunt-contrib-clean' );
	grunt.loadNpmTasks( 'grunt-contrib-rename' );
	grunt.loadNpmTasks( 'grunt-phpcs' );
	grunt.loadNpmTasks( 'grunt-sass' );
	grunt.loadNpmTasks( 'grunt-wget' );
	grunt.loadNpmTasks( 'grunt-wp-i18n' );
	grunt.loadNpmTasks( 'grunt-wp-readme-to-markdown' );
	grunt.loadNpmTasks( 'grunt-zip' );
	grunt.loadNpmTasks( 'grunt-contrib-watch' );
	grunt.registerTask( 'dependencies', ['wget', 'clean:prepare', 'unzip', 'clean:cleanup', 'rename'] );
	grunt.registerTask( 'i18n', ['addtextdomain', 'makepot'] );
	grunt.registerTask( 'readme', ['wp_readme_to_markdown']);
	grunt.registerTask( 'default', ['sass','browserify']);


	grunt.util.linefeed = '\n';

};
