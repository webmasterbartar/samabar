<?php
/**
 * Jalali (Persian) calendar helpers for PHP.
 *
 * @package Samabar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Convert Gregorian date to Jalali.
 *
 * @param int $gy Gregorian year.
 * @param int $gm Gregorian month.
 * @param int $gd Gregorian day.
 * @return array{jy: int, jm: int, jd: int}
 */
function samabar_gregorian_to_jalaali( $gy, $gm, $gd ) {
	$gdm = array( 0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334 );
	$jy  = $gy <= 1600 ? 0 : 979;
	$gy2 = $gy - ( $gy <= 1600 ? 621 : 1600 );
	$days =
		( 365 * $gy2 ) +
		(int) ( $gy2 / 4 ) -
		(int) ( $gy2 / 100 ) +
		(int) ( $gy2 / 400 ) -
		80 +
		$gd +
		$gdm[ $gm - 1 ];

	$jy += 33 * (int) ( $days / 12053 );
	$days %= 12053;
	$jy += 4 * (int) ( $days / 1461 );
	$days %= 1461;

	if ( $days > 365 ) {
		$jy += (int) ( ( $days - 1 ) / 365 );
		$days = ( $days - 1 ) % 365;
	}

	if ( $days < 186 ) {
		$jm = 1 + (int) ( $days / 31 );
		$jd = 1 + ( $days % 31 );
	} else {
		$jm = 7 + (int) ( ( $days - 186 ) / 30 );
		$jd = 1 + ( ( $days - 186 ) % 30 );
	}

	return array(
		'jy' => (int) $jy,
		'jm' => (int) $jm,
		'jd' => (int) $jd,
	);
}

/**
 * Get today's Jalali date in site timezone.
 *
 * @return array{jy: int, jm: int, jd: int}
 */
function samabar_jalaali_today() {
	$now = new DateTime( 'now', wp_timezone() );

	return samabar_gregorian_to_jalaali(
		(int) $now->format( 'Y' ),
		(int) $now->format( 'n' ),
		(int) $now->format( 'j' )
	);
}

/**
 * Build a normalized Jalali date key.
 *
 * @param int $jy Jalali year.
 * @param int $jm Jalali month.
 * @param int $jd Jalali day.
 * @return string
 */
function samabar_jalaali_date_key( $jy, $jm, $jd ) {
	return sprintf( '%d/%02d/%02d', (int) $jy, (int) $jm, (int) $jd );
}

/**
 * Whether a Jalali date is before today.
 *
 * @param int $jy Jalali year.
 * @param int $jm Jalali month.
 * @param int $jd Jalali day.
 * @return bool
 */
function samabar_is_jalaali_date_before_today( $jy, $jm, $jd ) {
	$today = samabar_jalaali_today();

	if ( $jy < $today['jy'] ) {
		return true;
	}
	if ( $jy > $today['jy'] ) {
		return false;
	}
	if ( $jm < $today['jm'] ) {
		return true;
	}
	if ( $jm > $today['jm'] ) {
		return false;
	}

	return $jd < $today['jd'];
}

/**
 * Extract Jalali date key from stored pickup value.
 *
 * @param string $value Pickup datetime string.
 * @return string
 */
function samabar_pickup_date_key( $value ) {
	if ( preg_match( '/^(\d{4})\/(\d{1,2})\/(\d{1,2})/', (string) $value, $matches ) ) {
		return samabar_jalaali_date_key( (int) $matches[1], (int) $matches[2], (int) $matches[3] );
	}

	return '';
}
