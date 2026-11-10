<?php

namespace ACP\oEmbed;

use AMP_Base_Embed_Handler;
use AMP_HTML_Utils;
use DOMDocument;

class Infogram_Embed_Handler extends AMP_Base_Embed_Handler {
	public function register_embed(): void {
		add_filter( 'embed_oembed_html', [ $this, 'filter_embed_oembed_html' ], 10, 3 );
	}

	public function unregister_embed(): void {
		remove_filter( 'embed_oembed_html', [ $this, 'filter_embed_oembed_html' ] );
	}

	/**
	 * @param string               $cache Cached embed HTML.
	 * @param string               $url Embed URL.
	 * @param array<string, mixed> $attr Embed attributes.
	 */
	public function filter_embed_oembed_html( string $cache, string $url, array $attr ): string {
		$parsed_url = wp_parse_url( $url );

		if ( ! isset( $parsed_url['host'], $parsed_url['path'] ) ) {
			return $cache;
		}

		if ( str_contains( $parsed_url['host'], 'infogram.com' ) ) {
			if ( empty( $attr['height'] ) ) {
				return $cache;
			}

			$infogram_id = $this->get_infogram_id_from_html( $cache );
			if ( $infogram_id === null || $infogram_id === '' ) {
				return $cache;
			}

			$attributes = wp_array_slice_assoc( $attr, [ 'width', 'height' ] );

			if ( empty( $attr['width'] ) ) {
				$attributes['layout'] = 'fixed-height';
				$attributes['width']  = 'auto';
			} else {
				$attributes['layout'] = 'responsive';
			}

			$attributes['src']             = 'https://e.infogram.com/' . $infogram_id . '?src=embed';
			$attributes['sandbox']         = 'allow-scripts allow-same-origin allow-popups';
			$attributes['resizable']       = '';
			$attributes['allowfullscreen'] = '';

			$cache = AMP_HTML_Utils::build_tag(
				'amp-iframe',
				$attributes,
				sprintf(
					'<div style="visibility: hidden" overflow tabindex=0 role=button aria-label="%1$s" placeholder>%1$s</div>',
					__( 'Loading...', 'oembed-infogram' )
				)
			);
		}

		return $cache;
	}

	protected function get_infogram_id_from_html( string $html ): ?string {
		$dom = new DOMDocument();
		if ( $html === '' || ! @$dom->loadHTML( $html ) ) {
			return null;
		}
		$div = $dom->getElementsByTagName( 'div' )->item( 0 );

		return $div === null ? null : $div->getAttribute( 'data-id' );
	}
}
