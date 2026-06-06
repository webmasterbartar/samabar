<?php
/**
 * Tariff (نرخنامه) source images — collection phase.
 * Images live in data/tariff/ until user confirms import to site.
 *
 * @package Samabar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Directory for tariff source images (absolute path).
 *
 * @return string
 */
function samabar_get_tariff_data_dir() {
	return get_template_directory() . '/data/tariff';
}

/**
 * Read tariff collection manifest.
 *
 * @return array<string, mixed>
 */
function samabar_get_tariff_manifest() {
	$file = samabar_get_tariff_data_dir() . '/manifest.json';
	if ( ! is_readable( $file ) ) {
		return array(
			'status' => 'collecting',
			'images' => array(),
		);
	}

	$data = json_decode( (string) file_get_contents( $file ), true );
	return is_array( $data ) ? $data : array( 'status' => 'collecting', 'images' => array() );
}

/**
 * Register a new tariff image in manifest.
 *
 * @param string $filename Saved filename (e.g. 01.webp).
 * @param string $note   Optional user note.
 * @return bool
 */
function samabar_register_tariff_image( $filename, $note = '' ) {
	$manifest = samabar_get_tariff_manifest();
	$manifest['images'][] = array(
		'file'       => $filename,
		'added'      => gmdate( 'c' ),
		'note'       => $note,
		'parsed'     => false,
	);

	return (bool) file_put_contents(
		samabar_get_tariff_data_dir() . '/manifest.json',
		wp_json_encode( $manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE )
	);
}

/**
 * Merge all data/tariff/pages/page-*.json into collected-rates.json.
 *
 * @return array{total: int, pages: int}|false
 */
function samabar_merge_tariff_collected_rates() {
	$dir   = samabar_get_tariff_data_dir() . '/pages';
	$files = glob( $dir . '/page-*.json' );
	if ( ! $files ) {
		return false;
	}

	sort( $files, SORT_NATURAL );
	$routes = array();
	$meta   = null;
	$merged = array();

	foreach ( $files as $file ) {
		$data = json_decode( (string) file_get_contents( $file ), true );
		if ( ! is_array( $data ) || empty( $data['routes'] ) ) {
			continue;
		}
		if ( null === $meta && ! empty( $data['meta'] ) ) {
			$meta = $data['meta'];
		}
		$routes  = array_merge( $routes, $data['routes'] );
		$merged[] = (int) preg_replace( '/\D/', '', basename( $file ) );
	}

	if ( ! $routes ) {
		return false;
	}

	usort(
		$routes,
		function ( $a, $b ) {
			return ( $a['row'] ?? 0 ) <=> ( $b['row'] ?? 0 );
		}
	);

	$output = array(
		'meta'   => array_merge(
			is_array( $meta ) ? $meta : array(),
			array(
				'status'       => 'collecting',
				'pages_merged' => $merged,
				'total_routes' => count( $routes ),
				'updated_at'   => gmdate( 'Y-m-d' ),
			)
		),
		'routes' => $routes,
	);

	file_put_contents(
		samabar_get_tariff_data_dir() . '/collected-rates.json',
		wp_json_encode( $output, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE )
	);

	return array(
		'total' => count( $routes ),
		'pages' => count( $merged ),
	);
}

/**
 * Publish collected tariff to active rates.json.
 *
 * @return bool
 */
function samabar_publish_tariff_rates() {
	$source = samabar_get_tariff_data_dir() . '/collected-rates.json';
	$target = samabar_get_tariff_data_dir() . '/rates.json';

	if ( ! is_readable( $source ) ) {
		return false;
	}

	$ok = copy( $source, $target );
	if ( $ok ) {
		$manifest = samabar_get_tariff_manifest();
		$manifest['status']       = 'imported';
		$manifest['imported_at']  = gmdate( 'c' );
		file_put_contents(
			samabar_get_tariff_data_dir() . '/manifest.json',
			wp_json_encode( $manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE )
		);
	}

	return $ok;
}

add_action(
	'after_setup_theme',
	function () {
		$rates = samabar_get_tariff_data_dir() . '/rates.json';
		if ( ! is_readable( $rates ) && is_readable( samabar_get_tariff_data_dir() . '/collected-rates.json' ) ) {
			samabar_publish_tariff_rates();
		}
	},
	20
);
