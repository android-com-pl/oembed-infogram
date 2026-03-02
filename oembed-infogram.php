<?php
/**
 * @wordpress-plugin
 * Plugin Name: oEmbed Infogram
 * Description: A simple plugin that adds support for embedding Infogram.
 * Version: 1.3.0
 * Plugin URI: https://github.com/android-com-pl/oembed-infogram
 * Author: android.com.pl
 * Author URI: https://android.com.pl/
 * License: GPL v3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace ACP\oEmbed;

if ( ! defined( 'ABSPATH' ) ) {
	http_response_code( 403 );
	exit();
}

class Infogram {
	public static function init() {
		add_action( 'init', [ self::class, 'add_provider' ] );
        add_action('enqueue_block_editor_assets', [self::class, 'enqueue_block_editor_assets']);
		add_filter( 'amp_content_embed_handlers', [ self::class, 'add_amp_handler' ], 10, 2 );
		add_filter( 'plugin_row_meta', [ self::class, 'add_plugin_row_meta_links' ], 10, 4 );
	}

	public static function add_provider(): void {
		wp_oembed_add_provider( 'https://infogram.com/*', 'https://infogram.com/oembed/?format=json' );
	}

    public static function enqueue_block_editor_assets(): void {
        $asset_file_path = plugin_dir_path( __FILE__ ) . 'build/index.asset.php';
        if(!file_exists($asset_file_path)) {
            return;
        }

        $asset = require $asset_file_path;
        wp_enqueue_script(
            'oembed-infogram-block-editor',
            plugins_url( 'build/index.js', __FILE__ ),
            $asset['dependencies'],
            $asset['version']
        );
    }

	/** @see https://amp-wp.org/documentation/playbooks/custom-embed-handler/ */
	public static function add_amp_handler( array $handler_classes ): array {
		require_once( plugin_dir_path( __FILE__ ) . 'class-amp-infogram-oembed-handler.php' );
		$handler_classes[ __NAMESPACE__ . '\\Infogram_Embed_Handler' ] = [];

		return $handler_classes;
	}

	public static function add_plugin_row_meta_links( array $plugin_meta, string $plugin_file ): array {
		if ( str_contains( $plugin_file, basename( __FILE__ ) ) ) {
			$plugin_meta[] = '<a href="https://github.com/android-com-pl/oembed-infogram">GitHub</a>';
			$plugin_meta[] = sprintf(
				'<a href="https://github.com/android-com-pl/oembed-infogram?sponsor=1">%s</a>',
				__( 'Donate', 'oembed-infogram' )
			);
		}

		return $plugin_meta;
	}
}

Infogram::init();
