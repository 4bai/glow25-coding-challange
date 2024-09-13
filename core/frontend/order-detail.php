<?php

// Exit if accessed directly.
if (! defined('ABSPATH')) exit;

require_once(EXORDERS__PLUGIN_DIR . '/core/api/fetch_order_detail.php');

/**
 * Displays the order details from the external shop for the current user.
 *
 * This function first checks if the user is logged in and if an order ID is provided.
 * If both conditions are met, it fetches the order details and displays them in a formatted table.
 *
 * @return void
 */
function display_order_detail()
{
    $user_id = get_current_user_id();
    if (!$user_id) {
        echo '<p>Bitte einloggen, um Bestellungen zu sehen.</p>';
        return;
    }

    $order_id = get_query_var('order_id');

    if (!$order_id) {
        echo '<p>Bestell-ID fehlt.</p>';
        return;
    }

    $order =  (object) fetch_order_detail($order_id);
    $billing = (object) $order->billing;
    $shipping = (object) $order->shipping;

    if (empty($order)) {
        echo '<p>Keine Bestellung gefunden.</p>';
        return;
    }
?>

    <div class="woocommerce woocommerce-page woocommerce-account">
        <div style="width: 100%;" class="woocommerce-MyAccount-content">
            <h2>Bestellung #<?php echo $order->id; ?></h2>

            <div class="woocommerce-notices-wrapper"></div>
            <p>
                Bestellung #<mark class="order-number"><?php echo $order->id; ?></mark> vom <mark class="order-date"><?php echo wp_date('j. F Y', strtotime($order->date_created), new DateTimeZone('UTC')) ?></mark> ist aktuell <mark class="order-status"><?php echo wc_get_order_status_name($order->status); ?></mark>.</p>


            <section class="woocommerce-order-details">
                <h2 class="woocommerce-order-details__title">Bestelldetails</h2>
                <table class="woocommerce-table woocommerce-table--order-details shop_table order_details">
                    <thead>
                        <tr>
                            <th class="woocommerce-table__product-name product-name">Produkt</th>
                            <th class="woocommerce-table__product-table product-total">Gesamtsumme</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order->line_items as $item) : ?>
                            <?php $item = (object) $item;  ?>
                            <tr class="woocommerce-table__line-item order_item">
                                <td class="woocommerce-table__product-name product-name">
                                    <a href="#"><?php echo $item->name; ?></a>
                                </td>
                                <td class="woocommerce-table__product-table product-total">
                                    <span class="woocommerce-Price-amount amount"> <?php echo $item->total; ?> EUR</span> <?php wc_price($item->total, array('currency' => $order->currency)); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th scope="row">Zwischensumme:</th>
                            <td><span class="woocommerce-Price-amount amount"><?php echo wc_price($order->total, array('currency' => $order->currency)); ?></span> (<?php echo $order->currency; ?>)</td>
                        </tr>
                        <tr>
                            <th scope="row">Zahlungsmethode:</th>
                            <td><?php echo $order->payment_method_title; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Gesamt:</th>
                            <td><span class="woocommerce-Price-amount amount"><?php echo wc_price($order->total, array('currency' => $order->currency)); ?></span> (<?php echo $order->currency; ?>)</td>
                        </tr>
                    </tfoot>
                </table>
            </section>

            <section class="woocommerce-customer-details">
                <h2 class="woocommerce-column__title">Rechnungsadresse</h2>
                <address>
                    <?php echo $billing->first_name . ' ' . $billing->last_name; ?>
                    <br>
                    <?php echo $billing->address_1; ?>
                    <br>
                    <?php echo $billing->address_2; ?>
                    <br>
                    <?php echo $billing->postcode . ' ' . $billing->city; ?>
                    <br>
                    <?php echo $billing->country; ?>
                    <br>
                    <p class="woocommerce-customer-details--phone"><?php echo $billing->phone; ?></p>
                    <p class="woocommerce-customer-details--email"><?php echo $billing->email; ?></p>
                </address>
            </section>
        </div>
    </div>
<?php

    //echo '<pre style="font-size: 11px;">' . print_r($order, true) . '</pre>';
}
