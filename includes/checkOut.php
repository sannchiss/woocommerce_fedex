<?php


//Cambio el Total del Envio en Order details
add_action('woocommerce_checkout_create_order', 'change_total_on_checking', 20, 1);
function change_total_on_checking($order)
{
    // Get order total
    $total = $order->get_total();

    ## -- Make your checking and calculations -- ##
    $new_total = $total; // <== Fake calculation

    // Set the new calculated total
    $order->set_total($new_total);
}



add_action('woocommerce_checkout_order_processed', 'action_checkout_order_processed', 10, 1);


function action_checkout_order_processed($order_id)
{

    // Declaración global Base de datos
    global $wpdb;
    global $table_prefix;

    // get an instance of the order object
    $order = wc_get_order($order_id);

    $order_data = $order->get_data(); // The Order data

    /*********************************************************************** */

    $table = $table_prefix . "fedex_shipping_intra_CL_orderDetail";

      // Loop through cart items
      foreach (WC()->cart->get_cart() as $cart_item) {
        // Get an instance of the WC_Product object and cart quantity
        $product = $cart_item['data'];
        $qty     = $cart_item['quantity'];

        // Get product dimensions  
        $length += $product->get_length() * $qty; // Longitud
        $width  += $product->get_width() * $qty;  // Ancho
        $height += $product->get_height() * $qty; // Altura

        // Calculations a item level
        $volume += $length * $width * $height * $qty;

        
    }

 
    $data = array(

        'orderNumber' => $order->get_id(),
        'weight' => WC()->cart->cart_contents_weight,
        'weightUnits' => get_option('woocommerce_weight_unit'),
        'length' => $length,
        'width' => $width,
        'height' => $height,
        'dimensionUnits' => get_option('woocommerce_dimension_unit'),
        'productDescription' => '',
        'quantity' =>  $order->get_item_count(),
        'totalPrice' => $order->get_total(),
        'created_at' =>  $order_data['date_created']->date('Y-m-d H:i:s')

    );


    $wpdb->insert($table, $data, null);


}
