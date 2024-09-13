<?php

// Exit if accessed directly.
if (! defined('ABSPATH')) exit;

require_once(EXORDERS__PLUGIN_DIR . '/core/api/fetch_orders.php');

/**
 * Displays a list of orders from the external shop for the current user.
 *
 * This function fetches the orders for the current user and displays them in a table.
 * If the user is not logged in, it displays a message asking the user to log in.
 * If no orders are found, it displays a message indicating that no orders were found.
 * TODO Add pagination
 *
 * @return void
 */
function display_orders()
{
    $user_id = get_current_user_id();
    if (!$user_id) {
        echo '<p>Bitte einloggen, um Bestellungen zu sehen.</p>';
        return;
    }

    $orders = fetch_orders();

    if (empty($orders)) {
        echo '<p>Keine Bestellungen aus External Shop gefunden.</p>';
        return;
    }

?>
    <div class="woocommerce woocommerce-page">
        <h3> Bestellungen aus External Shop (2020) </h3>
        <table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
            <thead>
                <tr>
                    <th scope="col" class="woocommerce-orders-table__header woocommerce-orders-table__header-order-number"><span class="nobr">Bestellung</span></th>
                    <th scope="col" class="woocommerce-orders-table__header woocommerce-orders-table__header-order-date"><span class="nobr">Datum</span></th>
                    <th scope="col" class="woocommerce-orders-table__header woocommerce-orders-table__header-order-status"><span class="nobr">Status</span></th>
                    <th scope="col" class="woocommerce-orders-table__header woocommerce-orders-table__header-order-total"><span class="nobr">Gesamtsumme</span></th>
                    <th scope="col" class="woocommerce-orders-table__header woocommerce-orders-table__header-order-actions"><span class="nobr">Aktionen</span></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order) : ?>
                    <?php $order = (object) $order;  ?>
                    <?php $order->url = '/external-order-detail/?order_id=' . $order->id; ?>
                    <tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-on-hold order">
                        <th class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-number" data-title="Bestellung" scope="row">
                            <a href="<?php echo $order->url; ?>" aria-label="Bestellnr.&nbsp;59 anzeigen">
                                #<?php echo $order->id ?> </a>
                        </th>
                        <td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-date" data-title="Datum">
                            <time datetime="<?php echo strtotime($order->date_created); ?>"><?php echo wp_date('j. F Y', strtotime($order->date_created), new DateTimeZone('UTC')) ?></time>
                        </td>
                        <td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-status" data-title="Status">
                            <?php echo wc_get_order_status_name($order->status); ?>
                        </td>
                        <td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-total" data-title="Gesamtsumme">
                            <span class="woocommerce-Price-amount amount"> <?php echo $order->total; ?> EUR</span> <?php wc_price($order->total, array('currency' => $order->currency)); ?>
                        </td>
                        <td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-actions" data-title="Aktionen">
                            <a href="<?php echo $order->url; ?>" class="woocommerce-button wp-element-button button view" aria-label="Bestellnr.&nbsp;59 anzeigen">Anzeigen</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php

//echo '<pre style="font-size: 11px;">' . print_r($orders, true) . '</pre>';
}
