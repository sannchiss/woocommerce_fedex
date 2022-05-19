<?php

// woocommerce_admin_order_data_after_order_details

add_action('woocommerce_admin_order_data_after_billing_address', 'add_custom_order_data_to_admin_order_page', 10, 1);


function add_custom_order_data_to_admin_order_page($order)
{
    // Declaración global Base de datos
    global $wpdb;
    global $table_prefix;


    $table = $table_prefix . "fedex_shipping_intra_CL_orderDetail";

    $order_id = $order->get_id();



    // get total weight order
    $order        = wc_get_order( $order_id );
	$order_items  = $order->get_items();
	$total_qty    = 0;
        $total_weight = 0;
    
	foreach ( $order_items as $item_id => $product_item ) {
		$product         = $product_item->get_product();
		if ( ! $product ) continue;
                $product_weight  = $product->get_weight();        
		        $quantity        = $product_item->get_quantity();
                $total_qty      += $quantity;
                $total_weight   += floatval( $product_weight * $quantity );
	}
	

  // SELECT JOIN WHERE
  

    $sql = "SELECT * FROM ".$table_prefix."posts 
    a INNER JOIN ".$table_prefix."fedex_shipping_intra_CL_responseShipping at ON a.ID = at.orderNumber WHERE a.ID = ".$order_id;
   
    $result = $wpdb->get_results($sql);

    foreach ($result as $row) {
        $order_number = $row->order_id;
        $order_post_status = $row->post_status;
        $masterTrackingNumber = $row->masterTrackingNumber;
        $labelBase64PDF = $row->labelBase64PDF;
    }




    // get method shipping
    $method_shipping = get_post_meta($order_id, '_shipping_method_title', true);

    
    // get label shiping
    $label_shipping = get_label_shipping( $masterTrackingNumber );


    if( $order_post_status == "wc-procesado-fedex" || $order_post_status == "wc-fedex" ):

      


    echo '<div class="card" style="width: 100%; height: 100%;">

    <div class="card-header">
    Etiqueta de envío FedEx
    </div>
    <iframe id="contentLabelPrint" src="data:application/pdf;base64,'.$label_shipping['pdfMerge'].'" width="100%" height="100%" allowfullscreen></iframe>

    <div class="card-body">

    <div class="row">

    <ul class="list-group">
    <li class="list-group-item">Transporte: <b>FedEx Express</b></li>
    <li class="list-group-item">Peso: <b>'.$total_weight.'Kg</b></li>
    <li class="list-group-item">Costo Envío: <b>$'.get_post_meta($order->get_order_number(), '_order_shipping', true).'</b></li>
    <li class="list-group-item">Orden Transporte: <b>'.$masterTrackingNumber.'</b></li>
  </ul>

    </div>


    </div>
  </div>';

    endif;
                         

    // show the label code 64


}


function get_label_shipping($masterTrackingNumber){

    $request = '{
        "credential": {
            "accountNumber": "' . ACCOUNT_NUMBER . '",
            "wskeyUserCredential": "' . WS_KEY_USER_CREDENTIAL . '",
            "wspasswordUserCredential": "' . WS_KEY_PASSWORD_CREDENTIAL . '"
        },
        "labelConfiguration": {
            "packageNumber": "",
            "format": "' . LABEL_TYPE . '",
            "label": "R",
            "consolidated": true
        },
        "shippingOrders": [
            {
                "trackingNumber": "' . $masterTrackingNumber . '"
            }
        ]
    }';

    // Cabecera de la petición
    $headers = array(
        'Accept' => 'application/json', 
        'Content-Type' => 'application/json'
    );
   

   

    $ws_response = RestClient::post(END_POINT_PRINT_LABEL, $headers, $request, null);

        // validate time out request
        if($ws_response->code == "408"){
            $ws_response = RestClient::post(END_POINT_PRINT_LABEL, $headers, $request, null);
        }

        



    // tour array $ws_response->body
    $response = json_decode($ws_response->body, true);

    return $response;

}






    