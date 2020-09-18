<?php
/**
 * PHP-Scoper configuration file.
 *
 * @package   Google\Web_Stories
 * @copyright 2020 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * @link      https://github.com/google/web-stories-wp
 */

use Isolated\Symfony\Component\Finder\Finder;

return [
	'prefix'                     => 'Google\\Web_Stories_Dependencies',

	// By default when running php-scoper add-prefix, it will prefix all relevant code found in the current working
	// directory. You can however define which files should be scoped by defining a collection of Finders in the
	// following configuration key.
	//
	// For more see: https://github.com/humbug/php-scoper#finders-and-paths.
	'finders'                    => [
		// Main AMP PHP Library.
		Finder::create()
			->files()
			->ignoreVCS( true )
			->ignoreDotFiles( true )
			->name( '*.php' )
			->notName(
				[
					'amp.php',
					'amp-frontend-actions.php',
					'amp-helper-functions.php',
					'amp-post-template-functions.php',
					'class-amp-autoloader.php',
					'class-amp-comment-walker.php',
					'class-amp-http.php',
					'class-amp-post-type-support.php',
					'class-amp-service-worker.php',
					'class-amp-theme-support.php',
					'deprecated.php',
					'amp-enabled-classic-editor-toggle.php',
					'amp-paired-browsing.php',
					'class-amp-content.php',
					'class-amp-post-template.php',
					'reader-template-loader.php',
				]
			)
			->exclude(
				[
					'assets',
					'back-compat',
					'bin',
					'docs',
					'includes/admin',
					'includes/cli',
					'includes/embeds',
					'includes/options',
					'includes/settings',
					'includes/validation',
					'includes/widgets',
					'patches',
					'src',
					'tests',
					'vendor',
					'wp-assets',
					'tests',
				]
			)
			->in(
				[
					'vendor/ampproject/amp-wp/includes',
					'vendor/ampproject/amp-wp/src/RemoteRequest',
					'vendor/ampproject/amp-wp/templates',
				]
			)
			->append( [ 'vendor/ampproject/amp-wp/composer.json' ] ),

		// AMP Common + Optimizer.
		Finder::create()
			->files()
			->ignoreVCS( true )
			->ignoreDotFiles( true )
			->name( '*.php' )
			->in(
				[
					'vendor/ampproject/amp-wp/lib',
				]
			),

		// FasterImage (used by AMP_Img_Sanitizer).
		Finder::create()
			->files()
			->ignoreVCS( true )
			->ignoreDotFiles( true )
			->name( '*.php' )
			->exclude(
				[
					'tests',
				]
			)
			->in( 'vendor/fasterimage/fasterimage' )
			->append( [ 'vendor/fasterimage/fasterimage/composer.json' ] ),

		// PHP-CSS-Parser (used by AMP_Style_Sanitizer).
		Finder::create()
			->files()
			->ignoreVCS( true )
			->ignoreDotFiles( true )
			->name( '*.php' )
			->exclude(
				[
					'tests',
				]
			)
			->in( 'vendor/sabberworm/php-css-parser' )
			->append( [ 'vendor/sabberworm/php-css-parser/composer.json' ] ),

		// Main composer.json file so that we can build a classmap.
		Finder::create()
			->append( [ 'composer.json' ] ),
	],

	// Whitelists a list of files. Unlike the other whitelist related features, this one is about completely leaving
	// a file untouched.
	// Paths are relative to the configuration file unless if they are already absolute.
	'files-whitelist'            => [],

	// When scoping PHP files, there will be scenarios where some of the code being scoped indirectly references the
	// original namespace. These will include, for example, strings or string manipulations. PHP-Scoper has limited
	// support for prefixing such strings. To circumvent that, you can define patchers to manipulate the file to your
	// heart contents.
	//
	// For more see: https://github.com/humbug/php-scoper#patchers.
	'patchers'                   => [
		function ( $file_path, $prefix, $contents ) {
			if ( preg_match( '#/class-amp-content-sanitizer\.php$#', $file_path ) ) {
				$contents = str_replace( "\\$prefix\\_doing_it_wrong", '\\_doing_it_wrong', $contents );
				$contents = str_replace( "\\$prefix\\__", '\\__', $contents );
				$contents = str_replace( "\\$prefix\\esc_html", '\\esc_html', $contents );
				$contents = str_replace( "\\$prefix\\do_action", '\\do_action', $contents );
			}

			if ( preg_match( '#/WpHttpRemoteGetRequest\.php$#', $file_path ) ) {
				$contents = str_replace( "\\$prefix\\WP_Http", '\\WP_Http', $contents );
				$contents = str_replace( "\\$prefix\\WP_Error", '\\WP_Error', $contents );
			}

			return $contents;
		},
	],

	// PHP-Scoper's goal is to make sure that all code for a project lies in a distinct PHP namespace. However, you
	// may want to share a common API between the bundled code of your PHAR and the consumer code. For example if
	// you have a PHPUnit PHAR with isolated code, you still want the PHAR to be able to understand the
	// PHPUnit\Framework\TestCase class.
	//
	// A way to achieve this is by specifying a list of classes to not prefix with the following configuration key. Note
	// that this does not work with functions or constants neither with classes belonging to the global namespace.
	//
	// Fore more see https://github.com/humbug/php-scoper#whitelist.
	'whitelist'                  => [],

	// If `true` then the user defined constants belonging to the global namespace will not be prefixed.
	//
	// For more see https://github.com/humbug/php-scoper#constants--constants--functions-from-the-global-namespace.
	'whitelist-global-constants' => false,

	// If `true` then the user defined classes belonging to the global namespace will not be prefixed.
	//
	// For more see https://github.com/humbug/php-scoper#constants--constants--functions-from-the-global-namespace.
	'whitelist-global-classes'   => false,

	// If `true` then the user defined functions belonging to the global namespace will not be prefixed.
	//
	// For more see https://github.com/humbug/php-scoper#constants--constants--functions-from-the-global-namespace.
	'whitelist-global-functions' => false,
];
