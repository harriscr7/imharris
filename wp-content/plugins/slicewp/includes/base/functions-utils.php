<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Sanitizes the values of an array recursively.
 *
 * @param array $array
 *
 * @return array
 *
 */
function _slicewp_array_sanitize_text_field( $array = array() ) {

    if ( empty( $array ) || ! is_array( $array ) ) {
        return array();
    }

    foreach ( $array as $key => $value ) {

        if ( is_array( $value ) ) {

            $array[$key] = _slicewp_array_sanitize_text_field( $value );

        } else {

            $array[$key] = sanitize_text_field( $value );

        }

    }

    return $array;

}


/**
 * Sanitizes the values of an array recursively and allows HTML tags.
 *
 * @param array $array
 *
 * @return array
 *
 */
function _slicewp_array_wp_kses_post( $array = array() ) {

    if ( empty( $array ) || ! is_array( $array ) ) {
        return array();
    }

    foreach ( $array as $key => $value ) {

        if ( is_array( $value ) ) {

            $array[$key] = _slicewp_array_wp_kses_post( $value );

        } else {

            $array[$key] = wp_kses_post( $value );

        }

    }

    return $array;

}


/**
 * Adds an associative array value, example array( 'key' => 'value' ) into an existing array
 * after the existing array's provided $key.
 *
 * @param array  $array
 * @param string $key
 * @param array  $value
 *
 * @return array
 *
 */
function _slicewp_array_assoc_push_after_key( $array, $key, $value ) {

    if ( ! isset( $value ) ) {
        return $array;
    }

    if ( ( $offset = array_search( $key, array_keys( $array ) ) ) === false ) {

        $offset = count( $array );

    }
    
    $offset++;

    return array_merge( array_slice( $array, 0, $offset ), $value, array_slice( $array, $offset ) );

}


/**
 * Returns a random generated string
 *
 * @param int $length
 *
 * @return string
 *
 */
function _slicewp_generate_random_string( $length = 20, $type = 'alphanumeric' ) {

    $chars_alphanumeric = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $chars_alpha        = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    $chars = ${'chars_' . $type};

    $chars_length  = strlen( $chars );
    $random_string = '';

    for ( $i = 0; $i < $length; $i++ ) {

        $random_string .= $chars[ rand( 0, $chars_length - 1 ) ];

    }

    return $random_string;

}


/**
 * Returns the logout url.
 *
 * @return string
 *
 */
function slicewp_get_logout_url() {

    /**
     * Filter the logout url before returning it
     *
     * @param string
     *
     */
    return apply_filters( 'slicewp_logout_url', wp_logout_url( get_permalink() ) );

}


/**
 * Checks to see if the provided date is a valid format
 *
 * @param string $date
 * @param string $format
 *
 * @return bool
 *
 */
function slicewp_is_date_valid( $date, $format = 'Y-m-d' ) {

    $d = DateTime::createFromFormat( $format, $date );

    return $d && $d->format($format) === $date;

}


/**
 * Returns the date and time format saved in WP's settings page
 *
 * @return string
 *
 */
function slicewp_get_datetime_format() {

    $format = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );

    /**
     * Filter the default date time format before returning
     *
     * @param string $format
     *
     */
    $format = apply_filters( 'slicewp_datetime_format', $format );

    return $format;

}


/**
 * Returns the current date and time in mysql format
 *
 * @return string
 *
 */
function slicewp_mysql_gmdate() {
    
    return current_time( 'mysql', true );

}


/**
 * Returns the date and time in user's language
 * 
 * @param string $date
 * 
 * @return string
 * 
 */
function slicewp_date_i18n( $date ) {

    return date_i18n( slicewp_get_datetime_format(), strtotime( get_date_from_gmt( $date ) ) );

}


/**
 * Function that return the IP address of the user. Checks for IPs (in order) in: 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'
 *
 * @return string
 *
 */
function slicewp_get_user_ip_address() {

    $ip_address = '';

    foreach ( array( 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR' ) as $key ) {

        if ( array_key_exists( $key, $_SERVER ) === true ) {

            foreach ( array_map( 'trim', explode( ',', $_SERVER[$key] ) ) as $ip ) {

                if ( filter_var( $ip, FILTER_VALIDATE_IP ) !== false ) {
                    return $ip;
                }

            }

        }

    }

    return $ip_address;
    
}


/**
 * Handles form errors
 *
 * @return WP_Error
 *
 */
function slicewp_form_errors() {

    static $wp_error;

    return ( isset( $wp_error ) ? $wp_error : ( $wp_error = new WP_Error( null, null, null ) ) );

}


/**
 * Adds a new message to the debug log
 *
 * @param string $message
 *
 * @return bool
 *
 */
function slicewp_add_log( $message ) {

    return slicewp()->services['debug_logger']->add_log( $message );

}


/**
 * Returns the entire debug log
 *
 * @return string
 *
 */
function slicewp_get_log() {

    return slicewp()->services['debug_logger']->get_file_contents();

}


/**
 * Clears the debug log file
 *
 */
function slicewp_clear_log() {

    return slicewp()->services['debug_logger']->delete_file();

}


/**
 * Returns an array with the properties and values of the given object
 *
 * @param object $object
 *
 * @return array
 *
 */
function slicewp_object_to_array( $object ) {

    return ( method_exists( $object, 'to_array' ) ? $object->to_array() : get_object_vars( $object ) );

}


/**
 * Returns an array of allowed HTML tags and attributes for the 'post' context
 *
 * @return array
 *
 */
function slicewp_get_kses_allowed_html() {

    $kses_defaults = wp_kses_allowed_html( 'post' );

    $svg_args = array(
        'svg'   => array(
            'class'           => true,
            'aria-hidden'     => true,
            'aria-labelledby' => true,
            'role'            => true,
            'xmlns'           => true,
            'fill'            => true,
            'stroke'          => true,
            'width'           => true,
            'height'          => true,
            'viewbox'         => true,
        ),
        'g'     => array( 'fill' => true ),
        'title' => array( 'title' => true ),
        'path'  => array(
            'd'               => true,
            'fill'            => true,
            'stroke'          => true,
            'stroke-linecap'  => true,
            'stroke-linejoin' => true,
            'stroke-width'    => true
        )
    );

    return array_merge( $kses_defaults, $svg_args );

}


/**
 * Returns an SVG from the collection based on the given slug.
 *
 * @param string $slug
 *
 * @return string
 *
 */
function slicewp_get_svg( $slug ) {

    $svg = array(

        // Outline icons. 2px stroke weight, 24x24 bounding box.
        'outline-adjustments'        => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" style="fill: none;" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg>',
        'outline-arrow-up'           => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" /></svg>',
        'outline-arrow-down'         => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" /></svg>',
        'outline-arrow-circle-right' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" style="fill: none;" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
        'outline-arrow-top-right-on-square' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>',
        'outline-calendar'           => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>',
        'outline-cash'               => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" style="fill: none;" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>',
        'outline-chart-bar'          => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" style="fill: none;" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>',
        'outline-chart-pie'          => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" style="fill: none;" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" /></svg>',
        'outline-check'              => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" style="fill: none;" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>',
        'outline-check-circle'       => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
        'outline-chevron-down'       => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>',
        'outline-chevron-left'       => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>',
        'outline-chevron-double-left' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M18.75 19.5l-7.5-7.5 7.5-7.5m-6 15L5.25 12l7.5-7.5" /></svg>',
        'outline-chevron-right'       => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>',
        'outline-chevron-double-right' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 4.5l7.5 7.5-7.5 7.5m-6-15l7.5 7.5-7.5 7.5" /></svg>',
        'outline-clock'              => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
        'outline-cloud-upload'       => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" style="fill: none;" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>',
        'outline-cog'                => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" style="fill: none;" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>',
        'outline-color-swatch'       => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" style="fill: none;" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" /></svg>',
        'outline-currency-dollar'    => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" style="fill: none;" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
        'outline-cursor-click'       => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" style="fill: none;" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" /></svg>',
		'outline-document-duplicate' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" /></svg>',
        'outline-duplicate'          => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" style="fill: none;" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>',
        'outline-eye'                => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" style="fill: none;" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>',
        'outline-globe'              => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" style="fill: none;" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
        'outline-home'               => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" style="fill: none;" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>',
        'outline-link'               => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" style="fill: none;" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>',
        'outline-logout'             => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" style="fill: none;" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>',
        'outline-photograph'         => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" style="fill: none;" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>',
        'outline-qrcode'             => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" style="fill: none;" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>',
        'outline-tag'                => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" style="fill: none;" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>',
        'outline-user-group'         => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>',
        'outline-x'                  => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" style="fill: none;" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>',
        'outline-x-circle'           => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" style="fill: none;" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',

        // Solid icons. Solid fill, 20x20 bounding box.
        'solid-adjustments'        => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 102 0V8.732a2 2 0 000-3.464V4zM16 3a1 1 0 011 1v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V4a1 1 0 011-1z" /></svg>',
        'solid-arrow-up'           => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>',
        'solid-arrow-down'         => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>',
        'solid-arrow-circle-right' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd" /></svg>',
        'solid-arrow-top-right-on-square' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M15.75 2.25H21a.75.75 0 01.75.75v5.25a.75.75 0 01-1.5 0V4.81L8.03 17.03a.75.75 0 01-1.06-1.06L19.19 3.75h-3.44a.75.75 0 010-1.5zm-10.5 4.5a1.5 1.5 0 00-1.5 1.5v10.5a1.5 1.5 0 001.5 1.5h10.5a1.5 1.5 0 001.5-1.5V10.5a.75.75 0 011.5 0v8.25a3 3 0 01-3 3H5.25a3 3 0 01-3-3V8.25a3 3 0 013-3h8.25a.75.75 0 010 1.5H5.25z" clip-rule="evenodd" /></svg>',
        'solid-calendar'           => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" /></svg>',
        'solid-cash'               => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>',
        'solid-chart-bar'          => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" /></svg>',
        'solid-chart-pie'          => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z" /><path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z" /></svg>',
        'solid-check'              => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>',
        'solid-check-circle'       => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"> <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" /> </svg>',
        'solid-chevron-down'       => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>',
        'solid-chevron-left'       => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M7.72 12.53a.75.75 0 010-1.06l7.5-7.5a.75.75 0 111.06 1.06L9.31 12l6.97 6.97a.75.75 0 11-1.06 1.06l-7.5-7.5z" clip-rule="evenodd" /></svg>',
        'solid-chevron-double-left' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M13.28 3.97a.75.75 0 010 1.06L6.31 12l6.97 6.97a.75.75 0 11-1.06 1.06l-7.5-7.5a.75.75 0 010-1.06l7.5-7.5a.75.75 0 011.06 0zm6 0a.75.75 0 010 1.06L12.31 12l6.97 6.97a.75.75 0 11-1.06 1.06l-7.5-7.5a.75.75 0 010-1.06l7.5-7.5a.75.75 0 011.06 0z" clip-rule="evenodd" /></svg>',
        'solid-chevron-right'       => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M16.28 11.47a.75.75 0 010 1.06l-7.5 7.5a.75.75 0 01-1.06-1.06L14.69 12 7.72 5.03a.75.75 0 011.06-1.06l7.5 7.5z" clip-rule="evenodd" /></svg>',
        'solid-chevron-double-right' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M4.72 3.97a.75.75 0 011.06 0l7.5 7.5a.75.75 0 010 1.06l-7.5 7.5a.75.75 0 01-1.06-1.06L11.69 12 4.72 5.03a.75.75 0 010-1.06zm6 0a.75.75 0 011.06 0l7.5 7.5a.75.75 0 010 1.06l-7.5 7.5a.75.75 0 11-1.06-1.06L17.69 12l-6.97-6.97a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg>',
        'solid-clock'              => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 6a.75.75 0 00-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 000-1.5h-3.75V6z" clip-rule="evenodd" /></svg>',
        'solid-cloud-upload'       => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.977A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z" /><path d="M9 13h2v5a1 1 0 11-2 0v-5z" /></svg>',
        'solid-cog'                => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" /></svg>',
        'solid-color-swatch'       => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 2a2 2 0 00-2 2v11a3 3 0 106 0V4a2 2 0 00-2-2H4zm1 14a1 1 0 100-2 1 1 0 000 2zm5-1.757l4.9-4.9a2 2 0 000-2.828L13.485 5.1a2 2 0 00-2.828 0L10 5.757v8.486zM16 18H9.071l6-6H16a2 2 0 012 2v2a2 2 0 01-2 2z" clip-rule="evenodd" /></svg>',
        'solid-currency-dollar'    => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" /><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" /></svg>',
        'solid-cursor-click'       => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6.672 1.911a1 1 0 10-1.932.518l.259.966a1 1 0 001.932-.518l-.26-.966zM2.429 4.74a1 1 0 10-.517 1.932l.966.259a1 1 0 00.517-1.932l-.966-.26zm8.814-.569a1 1 0 00-1.415-1.414l-.707.707a1 1 0 101.415 1.415l.707-.708zm-7.071 7.072l.707-.707A1 1 0 003.465 9.12l-.708.707a1 1 0 001.415 1.415zm3.2-5.171a1 1 0 00-1.3 1.3l4 10a1 1 0 001.823.075l1.38-2.759 3.018 3.02a1 1 0 001.414-1.415l-3.019-3.02 2.76-1.379a1 1 0 00-.076-1.822l-10-4z" clip-rule="evenodd" /></svg>',
		'solid-document-duplicate' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z" /><path d="M3 8a2 2 0 012-2v10h8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z" /></svg>',
        'solid-duplicate'          => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M7 9a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2H9a2 2 0 01-2-2V9z" /><path d="M5 3a2 2 0 00-2 2v6a2 2 0 002 2V5h8a2 2 0 00-2-2H5z" /></svg>',
        'solid-eye'                => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg>',
        'solid-globe'              => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 16v-2a2 2 0 00-2-2 2 2 0 01-2-2 2 2 0 00-1.668-1.973z" clip-rule="evenodd" /></svg>',
        'solid-home'               => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" /></svg>',
        'solid-link'               => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd" /></svg>',
        'solid-logout'             => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" /></svg>',
        'solid-photograph'         => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" /></svg>',
        'solid-qrcode'             => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 4a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm2 2V5h1v1H5zM3 13a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1v-3zm2 2v-1h1v1H5zM13 3a1 1 0 00-1 1v3a1 1 0 001 1h3a1 1 0 001-1V4a1 1 0 00-1-1h-3zm1 2v1h1V5h-1z" clip-rule="evenodd" /><path d="M11 4a1 1 0 10-2 0v1a1 1 0 002 0V4zM10 7a1 1 0 011 1v1h2a1 1 0 110 2h-3a1 1 0 01-1-1V8a1 1 0 011-1zM16 9a1 1 0 100 2 1 1 0 000-2zM9 13a1 1 0 011-1h1a1 1 0 110 2v2a1 1 0 11-2 0v-3zM7 11a1 1 0 100-2H4a1 1 0 100 2h3zM17 13a1 1 0 01-1 1h-2a1 1 0 110-2h2a1 1 0 011 1zM16 17a1 1 0 100-2h-3a1 1 0 100 2h3z" /></svg>',
        'solid-tag'                => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" /></svg>',
        'solid-user-group'         => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" /></svg>',
        'solid-x'                  => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>',
        'solid-x-circle'           => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>'

    );

    return ( ! empty( $svg[$slug] ) ? $svg[$slug] : '' );

}


/**
 * Calculates the percentage change from one value to another.
 * 
 * @param float $from_value
 * @param float $to_value
 * @param bool  $format
 * 
 * @return INF|float|string
 * 
 */
function slicewp_calculate_percentage_change( $from_value, $to_value, $format = false ) {

    if ( $from_value != 0 ) {

        $diff = round( ( ( $to_value - $from_value ) / $from_value * 100 ), 2 );

    } else {

        $diff = INF;

    }

    return ( $format ? ( ! is_infinite( $diff ) ? $diff . '%' : '-' ) : $diff );

}


/**
 * Returns an array with the mime type and extension based on the given array of file extensions.
 *
 * Example: $extensions = array( 'jpg', 'csv' ), the returned value will be array( 'jpg|jpeg|jpe' => 'image/jpeg', 'csv' => 'text/csv' )
 *
 * @param array $extensions
 *
 * @return array
 *
 */
function slicewp_extensions_to_mime_types( $extensions ) {

    $mime_types  = array();
    $_mime_types = wp_get_mime_types();

    // Sanitize the extensions array.
    foreach ( $extensions as $key => $extension ) {

        $extensions[$key] = str_replace( '.', '', $extension );

    }

    // Goes through each provided extension and check it againts the mime types from WP.
    foreach ( $extensions as $extension ) {

        foreach ( $_mime_types as $key => $mime_type ) {

            if ( false !== strpos( $key, $extension ) )
                $mime_types[$key] = $mime_type;

        }

    }

    return $mime_types;

}


/**
 * Checks whether the given function is disabled.
 *
 * @param string $function_name
 *
 * @return bool
 *
 */
function slicewp_is_func_disabled( $function_name ) {

    $disabled = explode( ',', ini_get( 'disable_functions' ) );

    return in_array( $function_name, $disabled );

}


/**
 * Returns a user friendly error message for the provided API action and error code,
 * when we are connecting to SliceWP website's API
 *
 * @param string $action
 * @param string $error_code
 *
 * @return string
 *
 */
function slicewp_get_api_action_response_error( $action, $error_code ) {

    $error_messages = array(
        'register_website' => array(
            'license_is_null'          => __( "The provided license key does not exist or is invalid.", 'slicewp' ),
            'license_inactive'         => __( "The provided license key is inactive.", 'slicewp' ),
            'license_expired'          => __( "The provided license key is expired.", 'slicewp' ),
            'activation_limit_reached' => __( "Your activation limit for this license key has been reached. Please upgrade your account if you'd like to register more websites.", 'slicewp' ),
            'register_website_failed'  => __( "Something went wrong. Could not activate the website. Please try again.", 'slicewp' )
        ),
        'deregister_website' => array(
            'license_is_null'           => __( "The provided license key does not exist or is invalid.", 'slicewp' ),
            'website_is_null'           => __( "This website is not registered on our system.", 'slicewp' ),
            'deregister_website_failed' => __( "Something went wrong. Could not activate the website. Please try again.", 'slicewp' )
        )
    );

    return ( ! empty( $error_messages[$action][$error_code] ) ? $error_messages[$action][$error_code] : '' );

}


/**
 * Returns the system status for the current installation
 *
 * @return string
 *
 */
function slicewp_system_status() {

    // Get system versions
    global $wp_version;

    $curl_version   = ( function_exists( 'curl_version' ) ? curl_version() : 'Not installed' );
    $curl_version   = ( is_array( $curl_version ) ? $curl_version['version'] : $curl_version );

    // Get all plugins and active plugins
    $plugins        = get_plugins();
    $active_plugins = array();

    foreach ( $plugins as $key => $plugin ) {

        if ( is_plugin_active( $key ) ) {
            $active_plugins[$key] = $plugin;
        }

    }


    // Prepare system status
    $status  = 'System:' . "\r\n";
    $status .= '---------------------------------------------------------------------' . "\r\n";
    $status .= 'PHP Version: ' . phpversion() . "\r\n";
    $status .= 'cUrl Version: ' . $curl_version . "\r\n";
    $status .= 'WP Version: ' . $wp_version . "\r\n";
    $status .= 'SliceWP Version: ' . SLICEWP_VERSION . "\r\n";

    $status .= "\r\n";

    // Prepare all plugins
    $status .= 'All Plugins:' . "\r\n";
    $status .= '---------------------------------------------------------------------' . "\r\n";

    if ( ! empty( $plugins ) ) {

        foreach ( $plugins as $key => $plugin ) {
            $status .= esc_attr( $plugin['Name'] ) . ' (' . esc_attr( $key ) . ')' . ' (v.' . esc_attr( $plugin['Version'] ) . ')' ."\r\n";
        }

    } else {
        $status .= 'None' . "\r\n";
    }

    $status .= "\r\n";

    // Prepare active plugins
    $status .= 'Active Plugins:' . "\r\n";
    $status .= '---------------------------------------------------------------------' . "\r\n";

    if ( ! empty( $active_plugins ) ) {

        foreach ( $active_plugins as $key => $plugin ) {
            $status .= esc_attr( $plugin['Name'] ) . ' (' . esc_attr( $key ) . ')' . ' (v.' . esc_attr( $plugin['Version'] ) . ')' ."\r\n";
        }

    } else {
        $status .= 'None' . "\r\n";
    }

    $status .= "\r\n";

    // Prepare database tables
    global $wpdb;
    $status .= 'Database:' . "\r\n";
    $status .= '---------------------------------------------------------------------' . "\r\n";

    $status .= 'Database Prefix: ' . esc_attr( $wpdb->prefix ) . "\r\n";

    foreach ( slicewp()->db as $table ) {
        
        $table_name = sanitize_text_field( $table->table_name );

        $status .= 'Table ' . esc_attr( $table_name ) . ' - ' . ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE '%s'", $table_name ) ) === $table_name ? 'yes' : 'no' ) . "\r\n";

    }


    // Return the system info
    return $status;

}


/**
 * Prefixes all keys of an array with the given prefix and returns the result
 *
 * @param array  $array
 * @param string $prefix
 *
 * @return array
 *
 */
function _slicewp_prefix_array_keys( $array = array(), $prefix = '' ) {

    if ( empty( $array ) ) {
        return array();
    }

    if ( empty( $prefix ) ) {
        return $array;
    }

    foreach ( $array as $key => $value ) {

        $array[$prefix . $key] = $value;
        unset( $array[$key] );

    }

    return $array;

}