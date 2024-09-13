<?php

// Exit if accessed directly.
if (! defined('ABSPATH')) exit;

require_once(EXORDERS__PLUGIN_DIR . 'core/api/fetch.php');

function fetch_orders()
{
    $user_id = get_current_user_id();
    $args = [
        'customer' => $user_id, // Filter by user ID
        'after' => '2020-01-01T00:00:00', // Start of the year 2020
        'before' => '2020-12-31T23:59:59', // End of the year 2020
        'status' => 'any', // Optional: Filter by status, adjust as needed
        'page' => 1,
        'limit' => 20,
        '_fields' => 'id,date_created,total,currency,status', // Specify the fields needed
        'order' => 'desc'
    ];

    return fetch("orders", $args);
}
