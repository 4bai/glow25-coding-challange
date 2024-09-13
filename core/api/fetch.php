<?php

// Exit if accessed directly.
if (! defined('ABSPATH')) exit;

/**
 * Fetches data from the WooCommerce REST API.
 * TODO cache the response
 *
 * @param string $path The API endpoint path. Defaults to 'orders'.
 * @param array  $args Additional query arguments.
 *
 * @return array The response data from the API.
 */
function fetch($path = 'orders', $args)
{
    // WooCommerce REST API credentials
    $consumer_key = EXORDERS__CONFIG['consumer_key'];
    $consumer_secret = EXORDERS__CONFIG['consumer_secret'];
    $shop_external_url = EXORDERS__CONFIG['shop_external_url'];
    $api_url = $shop_external_url . '/wp-json/wc/v3/' . $path;

    $oauth_params = [
        'oauth_consumer_key' => $consumer_key,
        'oauth_nonce' => wp_generate_password(12, false),
        'oauth_signature_method' => 'HMAC-SHA1',
        'oauth_timestamp' => time(),
        'oauth_version' => '1.0',
    ];

    $params = array_merge($args, $oauth_params);
    ksort($params);

    $base_string = 'GET&' . rawurlencode($api_url) . '&' . rawurlencode(http_build_query($params, '', '&', PHP_QUERY_RFC3986));
    $signing_key = rawurlencode($consumer_secret) . '&';
    $oauth_signature = base64_encode(hash_hmac('sha1', $base_string, $signing_key, true));
    $params['oauth_signature'] = $oauth_signature;
    $request_url = $api_url . '?' . http_build_query($params, '', '&', PHP_QUERY_RFC3986);

    // Make the API request
    $response = wp_remote_get($request_url);
    $response_code = wp_remote_retrieve_response_code( $response );

    if (is_wp_error($response) || $response_code !== 200) {
        return []; // Return empty if there is an error
    }

    // Decode the response body
    $response_body = json_decode(wp_remote_retrieve_body($response), true);
    return $response_body ?: [];
}
