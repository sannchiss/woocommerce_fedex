<?php
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

// details order
add_action('woocommerce_order_details_after_order_table', 'details_order', 10, 1);


function details_order($order)
{


        // Declaración global Base de datos
        global $wpdb;
        global $table_prefix;
    
    
        $order_id = $order->get_id();
    
        // get total weight order
        $order  = wc_get_order( $order_id );
    
        /*********************************************************************** */
    
        $table = $table_prefix . "fedex_shipping_intra_CL_orderDetail";
    
          // Loop through cart items
        $order_items  = $order->get_items();
        $total_qty    = 0;
        $total_weight = 0;

        $length = 0;
        $width = 0;
        $height = 0;

        foreach ( $order_items as $item_id => $product_item ) {
            $product         = $product_item->get_product();
            if ( ! $product ) continue;
            $product_weight  = $product->get_weight();        
            $quantity        = $product_item->get_quantity();
            $total_qty      += $quantity;
            $total_weight   += floatval( $product_weight * $quantity );

            // get length and width
            $length += $product->get_length() * $quantity;
            $width += $product->get_width() * $quantity;
            $height += $product->get_height() * $quantity;

        }

        // get total volume order
     
        $data = array(
    
            'orderNumber' => $order->get_id(),
            'weight' => $total_weight,
            'weightUnits' => get_option('woocommerce_weight_unit'),
            'length' => $length ? $length : 0.1,
            'width' => $width ? $width : 0.1,
            'height' => $height ? $height : 0.1,
            'dimensionUnits' => get_option('woocommerce_dimension_unit'),
            'productDescription' => '',
            'quantity' =>  $order->get_item_count(),
            'totalPrice' => $order->get_total(),
            'created_at' =>  $order->get_date_created()->date('Y-m-d H:i:s'),
    
        );
    
    
        $wpdb->insert($table, $data, null);



}