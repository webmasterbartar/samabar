<?php
/**
 * Tariff rates and Bandar Abbas hub route rules.
 *
 * @package Samabar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Logistics hub city (display label).
 *
 * @return string
 */
function samabar_get_hub_city() {
	return apply_filters( 'samabar_hub_city', 'بندرعباس' );
}

/**
 * Known aliases for hub city matching.
 *
 * @return string[]
 */
function samabar_get_hub_city_aliases() {
	return apply_filters(
		'samabar_hub_city_aliases',
		array(
			'بندرعباس',
			'بندر عباس',
			'bandarabbas',
			'bandar abbas',
		)
	);
}

/**
 * Normalize city name for comparison.
 *
 * @param string $city City label.
 * @return string
 */
function samabar_normalize_city_key( $city ) {
	$key = trim( (string) $city );
	$key = preg_replace( '/\s*\(.+?\)\s*/u', '', $key );
	$key = str_replace( array( ' ', '‌', 'ي', 'ك', '/', 'ـ' ), array( '', '', 'ی', 'ک', '', '' ), $key );
	$key = mb_strtolower( $key, 'UTF-8' );

	return $key;
}

/**
 * Whether a city label is the hub (Bandar Abbas).
 *
 * @param string $city City label.
 * @return bool
 */
function samabar_is_hub_city( $city ) {
	$key = samabar_normalize_city_key( $city );
	if ( '' === $key ) {
		return false;
	}

	foreach ( samabar_get_hub_city_aliases() as $alias ) {
		if ( samabar_normalize_city_key( $alias ) === $key ) {
			return true;
		}
	}

	return samabar_normalize_city_key( samabar_get_hub_city() ) === $key;
}

/**
 * Parsed tariff rates from JSON.
 *
 * @return array<string, mixed>
 */
function samabar_get_tariff_rates() {
	static $cache = null;

	if ( null !== $cache ) {
		return $cache;
	}

	$dir   = samabar_get_tariff_data_dir();
	$files = array( $dir . '/rates.json', $dir . '/collected-rates.json' );

	foreach ( $files as $file ) {
		if ( is_readable( $file ) ) {
			$data = json_decode( (string) file_get_contents( $file ), true );
			if ( is_array( $data ) && ! empty( $data['routes'] ) ) {
				$cache = $data;
				return $cache;
			}
		}
	}

	$cache = array();
	return $cache;
}

/**
 * Index tariff routes by normalized city keys.
 *
 * @return array<string, array<string, mixed>>
 */
function samabar_get_tariff_route_index() {
	static $index = null;

	if ( null !== $index ) {
		return $index;
	}

	$index = array();
	$rates = samabar_get_tariff_rates();
	$routes = isset( $rates['routes'] ) && is_array( $rates['routes'] ) ? $rates['routes'] : array();

	foreach ( $routes as $route ) {
		$labels = array(
			$route['destination'] ?? '',
			$route['destination_primary'] ?? '',
			$route['destination_alias'] ?? '',
		);

		foreach ( $labels as $label ) {
			$key = samabar_normalize_city_key( $label );
			if ( $key && ! isset( $index[ $key ] ) ) {
				$index[ $key ] = $route;
			}
		}
	}

	return $index;
}

/**
 * Cities served besides the hub (from tariff routes).
 *
 * @return string[] Display labels.
 */
function samabar_get_served_cities() {
	$rates  = samabar_get_tariff_rates();
	$routes = isset( $rates['routes'] ) && is_array( $rates['routes'] ) ? $rates['routes'] : array();
	$found  = array();

	foreach ( $routes as $route ) {
		$label = $route['destination'] ?? $route['destination_primary'] ?? '';
		if ( ! $label || samabar_is_hub_city( $label ) ) {
			continue;
		}
		$key = samabar_normalize_city_key( $label );
		if ( $key ) {
			$found[ $key ] = $label;
		}
	}

	return apply_filters( 'samabar_served_cities', array_values( $found ) );
}

/**
 * All selectable route cities: hub + every city in the tariff.
 *
 * @return string[] Display labels (hub first, then alphabetical).
 */
function samabar_get_all_route_cities() {
	static $cache = null;

	if ( null !== $cache ) {
		return $cache;
	}

	$hub    = samabar_get_hub_city();
	$unique = array(
		samabar_normalize_city_key( $hub ) => $hub,
	);

	foreach ( samabar_get_served_cities() as $label ) {
		$key = samabar_normalize_city_key( $label );
		if ( $key && ! isset( $unique[ $key ] ) ) {
			$unique[ $key ] = $label;
		}
	}

	$cities  = array_values( $unique );
	$hub_key = samabar_normalize_city_key( $hub );

	usort(
		$cities,
		function ( $a, $b ) use ( $hub_key ) {
			$ak = samabar_normalize_city_key( $a );
			$bk = samabar_normalize_city_key( $b );

			if ( $ak === $hub_key ) {
				return -1;
			}
			if ( $bk === $hub_key ) {
				return 1;
			}

			return strcmp( $a, $b );
		}
	);

	$cache = apply_filters( 'samabar_all_route_cities', $cities );

	return $cache;
}

/**
 * Match user/session input to a canonical tariff city label.
 *
 * @param string $city Raw city label.
 * @return string Canonical label or empty if unknown.
 */
function samabar_resolve_route_city_label( $city ) {
	$city = trim( (string) $city );

	if ( '' === $city ) {
		return '';
	}

	if ( samabar_is_hub_city( $city ) ) {
		return samabar_get_hub_city();
	}

	$key = samabar_normalize_city_key( $city );

	foreach ( samabar_get_all_route_cities() as $label ) {
		if ( samabar_normalize_city_key( $label ) === $key ) {
			return $label;
		}
	}

	$route = samabar_find_tariff_route( $city );

	if ( $route ) {
		return (string) ( $route['destination'] ?? '' );
	}

	return '';
}

/**
 * Markup for a city dropdown (hub + tariff cities only).
 *
 * @param array<string, mixed> $args id, name, selected, required, class, placeholder, attrs.
 * @return string
 */
function samabar_get_city_select_markup( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'id'          => '',
			'name'        => '',
			'selected'    => '',
			'required'    => false,
			'class'       => '',
			'placeholder' => __( 'انتخاب شهر…', 'samabar' ),
			'attrs'       => array(),
		)
	);

	$selected = samabar_resolve_route_city_label( $args['selected'] );
	$classes  = trim( (string) $args['class'] );
	$attrs    = '';

	if ( $args['id'] ) {
		$attrs .= ' id="' . esc_attr( $args['id'] ) . '"';
	}

	if ( $args['name'] ) {
		$attrs .= ' name="' . esc_attr( $args['name'] ) . '"';
	}

	if ( $classes ) {
		$attrs .= ' class="' . esc_attr( $classes ) . '"';
	}

	if ( $args['required'] ) {
		$attrs .= ' required';
	}

	if ( is_array( $args['attrs'] ) ) {
		foreach ( $args['attrs'] as $attr_key => $attr_val ) {
			$attrs .= ' ' . esc_attr( (string) $attr_key ) . '="' . esc_attr( (string) $attr_val ) . '"';
		}
	}

	$html  = '<select' . $attrs . '>';
	$html .= '<option value="">' . esc_html( $args['placeholder'] ) . '</option>';

	$hub     = samabar_get_hub_city();
	$hub_key = samabar_normalize_city_key( $hub );
	$others  = array();

	foreach ( samabar_get_all_route_cities() as $city ) {
		if ( samabar_normalize_city_key( $city ) === $hub_key ) {
			continue;
		}
		$others[] = $city;
	}

	$html .= '<option value="' . esc_attr( $hub ) . '"' . selected( $selected, $hub, false ) . '>' . esc_html( $hub ) . '</option>';

	if ( $others ) {
		$html .= '<optgroup label="' . esc_attr__( 'شهرهای نرخنامه', 'samabar' ) . '">';
		foreach ( $others as $city ) {
			$html .= '<option value="' . esc_attr( $city ) . '"' . selected( $selected, $city, false ) . '>' . esc_html( $city ) . '</option>';
		}
		$html .= '</optgroup>';
	}

	$html .= '</select>';

	return $html;
}

/**
 * Whether a city is in the served list (fuzzy match).
 *
 * @param string $city City label.
 * @return bool
 */
function samabar_is_served_city( $city ) {
	if ( ! samabar_get_served_cities() ) {
		return true;
	}

	return (bool) samabar_find_tariff_route( $city );
}

/**
 * Find tariff row for a non-hub city.
 *
 * @param string $city City label.
 * @return array<string, mixed>|null
 */
function samabar_find_tariff_route( $city ) {
	$key = samabar_normalize_city_key( $city );
	if ( ! $key ) {
		return null;
	}

	$index = samabar_get_tariff_route_index();
	if ( isset( $index[ $key ] ) ) {
		return $index[ $key ];
	}

	foreach ( $index as $route_key => $route ) {
		if ( false !== strpos( $route_key, $key ) || false !== strpos( $key, $route_key ) ) {
			return $route;
		}
	}

	return null;
}

/**
 * Non-hub city on a route (for tariff lookup).
 *
 * @param string $origin_city Origin.
 * @param string $destination_city Destination.
 * @return string
 */
function samabar_get_tariff_route_city( $origin_city, $destination_city ) {
	if ( samabar_is_hub_city( $origin_city ) ) {
		return trim( (string) $destination_city );
	}
	if ( samabar_is_hub_city( $destination_city ) ) {
		return trim( (string) $origin_city );
	}

	return '';
}

/**
 * Validate origin/destination against hub rules.
 *
 * @param string $origin_city Origin city.
 * @param string $destination_city Destination city.
 * @return true|WP_Error
 */
function samabar_validate_route_cities( $origin_city, $destination_city ) {
	$origin = trim( (string) $origin_city );
	$dest   = trim( (string) $destination_city );

	if ( '' === $origin || '' === $dest ) {
		return new WP_Error(
			'missing_route',
			__( 'شهر مبدا و مقصد را وارد کنید.', 'samabar' ),
			array( 'status' => 400 )
		);
	}

	if ( samabar_normalize_city_key( $origin ) === samabar_normalize_city_key( $dest ) ) {
		return new WP_Error(
			'same_city',
			__( 'مبدا و مقصد نمی‌توانند یک شهر باشند.', 'samabar' ),
			array( 'status' => 400 )
		);
	}

	$origin_is_hub = samabar_is_hub_city( $origin );
	$dest_is_hub   = samabar_is_hub_city( $dest );

	if ( ! $origin_is_hub && ! $dest_is_hub ) {
		return new WP_Error(
			'invalid_route',
			sprintf(
				/* translators: %s: hub city name */
				__( 'سما بار فقط با %s کار می‌کند: بارگیری از %s به سراسر کشور، یا از هر شهر به %s. مسیر بین دو شهر دیگر (مثل تهران ← یزد) پذیرفته نمی‌شود.', 'samabar' ),
				samabar_get_hub_city(),
				samabar_get_hub_city(),
				samabar_get_hub_city()
			),
			array( 'status' => 400 )
		);
	}

	if ( $origin_is_hub && $dest_is_hub ) {
		return new WP_Error(
			'invalid_route',
			__( 'مسیر داخل شهر بندرعباس در سامانه ثبت سفارش آنلاین پوشش داده نمی‌شود.', 'samabar' ),
			array( 'status' => 400 )
		);
	}

	$other_city = $origin_is_hub ? $dest : $origin;
	if ( ! samabar_is_served_city( $other_city ) ) {
		return new WP_Error(
			'city_not_served',
			sprintf(
				/* translators: %s: city name */
				__( 'شهر «%s» در نرخنامه ثبت نشده است. لطفاً نام شهر را دقیق‌تر وارد کنید یا با پشتیبانی تماس بگیرید.', 'samabar' ),
				$other_city
			),
			array( 'status' => 400 )
		);
	}

	return true;
}

/**
 * Minimum weight (kg) so freight meets base rate for a route.
 *
 * @param array<string, mixed> $route Tariff route row.
 * @return int
 */
function samabar_get_tariff_minimum_weight_kg( $route ) {
	$truck_15t = (int) ( $route['truck_15t_rial'] ?? 0 );
	$base      = (int) ( $route['base_rial'] ?? 0 );

	if ( $truck_15t <= 0 || $base <= 0 ) {
		return 1;
	}

	return (int) ceil( ( $base / $truck_15t ) * 1000 );
}

/**
 * Calculate freight: truck_15t × weight(ton), minimum = base rate.
 *
 * @param string $origin_city Origin.
 * @param string $destination_city Destination.
 * @param int    $weight_kg Weight in kilograms.
 * @return array<string, mixed>|WP_Error
 */
function samabar_calculate_tariff_freight( $origin_city, $destination_city, $weight_kg ) {
	$valid = samabar_validate_route_cities( $origin_city, $destination_city );
	if ( is_wp_error( $valid ) ) {
		return $valid;
	}

	$weight_kg = max( 1, (int) $weight_kg );
	$rates     = samabar_get_tariff_rates();

	if ( empty( $rates['routes'] ) ) {
		return new WP_Error(
			'tariff_pending',
			__( 'نرخنامه هنوز در سایت فعال نشده است.', 'samabar' ),
			array( 'status' => 503 )
		);
	}

	$route_city = samabar_get_tariff_route_city( $origin_city, $destination_city );
	$route      = samabar_find_tariff_route( $route_city );

	if ( ! $route ) {
		return new WP_Error(
			'city_not_in_tariff',
			sprintf(
				/* translators: %s: city name */
				__( 'نرخ مسیر شهر «%s» در نرخنامه پیدا نشد.', 'samabar' ),
				$route_city
			),
			array( 'status' => 400 )
		);
	}

	$truck_15t    = (int) $route['truck_15t_rial'];
	$base_rial    = (int) $route['base_rial'];
	$weight_tons  = $weight_kg / 1000;
	$freight      = (int) round( $truck_15t * $weight_tons );
	$min_weight   = samabar_get_tariff_minimum_weight_kg( $route );

	if ( $freight < $base_rial ) {
		return new WP_Error(
			'below_minimum',
			sprintf(
				/* translators: 1: calculated freight, 2: base rate, 3: minimum weight kg */
				__( 'کرایه محاسبه‌شده (%1$s) کمتر از حداقل نرخ پایه این مسیر (%2$s) است. حداقل وزن تقریبی: %3$s کیلوگرم.', 'samabar' ),
				samabar_format_price( $freight ),
				samabar_format_price( $base_rial ),
				number_format_i18n( $min_weight )
			),
			array(
				'status'          => 400,
				'freight'         => $freight,
				'base_rial'       => $base_rial,
				'min_weight_kg'   => $min_weight,
				'truck_15t_rial'  => $truck_15t,
				'weight_kg'       => $weight_kg,
				'destination'     => $route['destination'] ?? $route_city,
			)
		);
	}

	return array(
		'amount'          => $freight,
		'base_rial'       => $base_rial,
		'truck_15t_rial'  => $truck_15t,
		'per_ton_rial'    => (int) ( $route['per_ton_rial'] ?? 0 ),
		'weight_kg'       => $weight_kg,
		'weight_tons'     => $weight_tons,
		'min_weight_kg'   => $min_weight,
		'destination'     => $route['destination'] ?? $route_city,
		'distance_km'     => isset( $route['distance_km'] ) ? (int) $route['distance_km'] : null,
		'currency'        => 'toman',
		'label'           => __( 'کرایه حمل', 'samabar' ),
	);
}

/**
 * Route rules payload for front-end validation.
 *
 * @return array<string, mixed>
 */
function samabar_get_route_rules_config() {
	return array(
		'hubCity'       => samabar_get_hub_city(),
		'hubAliases'    => samabar_get_hub_city_aliases(),
		'servedCities'  => samabar_get_served_cities(),
		'allCities'     => samabar_get_all_route_cities(),
		'hasRates'      => (bool) samabar_get_tariff_rates(),
		'bidirectional' => true,
		'messages'      => array(
			'missing'       => 'شهر مبدا و مقصد را انتخاب کنید.',
			'sameCity'      => 'مبدا و مقصد نمی‌توانند یک شهر باشند.',
			'invalidRoute'  => sprintf(
				'یک طرف مسیر باید %s باشد: یا %s ← شهر دیگر (ارسال از هاب)، یا شهر دیگر ← %s (بار به هاب).',
				samabar_get_hub_city(),
				samabar_get_hub_city(),
				samabar_get_hub_city()
			),
			'bothHub'       => 'مسیر داخل بندرعباس در سامانه آنلاین ثبت نمی‌شود.',
			'notServed'     => 'این شهر در نرخنامه ثبت نشده است. با پشتیبانی تماس بگیرید.',
			'belowMinimum'  => 'کرایه کمتر از حداقل نرخ پایه است. وزن را افزایش دهید.',
		),
	);
}

/**
 * Format freight result for REST/JS.
 *
 * @param array<string, mixed> $result Calculation result.
 * @return array<string, mixed>
 */
function samabar_format_freight_response( $result ) {
	return array_merge(
		$result,
		array(
			'amount_label'      => samabar_format_price( $result['amount'] ),
			'base_label'        => samabar_format_price( $result['base_rial'] ),
			'formula'           => 'truck_15t × weight_tons',
		)
	);
}
