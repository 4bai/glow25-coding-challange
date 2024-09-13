<?php

// Exit if accessed directly.
if (! defined('ABSPATH')) exit;

require_once(EXORDERS__PLUGIN_DIR . 'core/api/fetch.php');

function fetch_order_detail($order_id)
{
    $user_id = get_current_user_id();
    $args = [
        'customer' => $user_id, // Filter by user ID
        'status' => 'any', // Optional: Filter by status, adjust as needed
        'id' => $order_id,
        'limit' => 1,
        'page' => 1
    ];

    $order = fetch("orders/$order_id", $args);
    return $order;
}
