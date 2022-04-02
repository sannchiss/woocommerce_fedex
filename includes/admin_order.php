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
	


    $sql = "SELECT * FROM ".$table_prefix."posts 
    a INNER JOIN ".$table_prefix."fedex_shipping_intra_CL_orderSend at ON a.ID = at.orderNumber";
   
    $result = $wpdb->get_results($sql);

    foreach ($result as $row) {
        $order_number = $row->order_id;
        $order_post_status = $row->post_status;
        $masterTrackingNumber = $row->masterTrackingNumber;
        $labelBase64PDF = $row->labelBase64PDF;
        $order_length = $row->length;
        $order_length_send = $row->lengthSend;
        $order_width = $row->width;
        $order_width_send = $row->widthSend;
        $order_height = $row->height;
        $order_height_send = $row->heightSend;
        $order_volume = $row->volume;
        $order_volume_send = $row->volumeSend;
        $order_status = $row->status;
        $order_status_send = $row->statusSend;
        $order_date = $row->date;
        $order_date_send = $row->dateSend;
    }




    // get method shipping
    $method_shipping = get_post_meta($order_id, '_shipping_method_title', true);

    
    // get label shiping
    $label_shipping = get_label_shipping( $masterTrackingNumber );


    if( $order_post_status == "wc-procesado-fedex" || $order_post_status == "wc-fedex" ):

    echo '<div class="card" style="width: 100%;">
    <h5 class="card-title">Etiqueta FedEx</h5>
    <iframe id="contentLabelPrint" src="data:application/pdf;base64,'.$label_shipping['pdfMerge'].'" width="100%" height="100%" allowfullscreen></iframe>

    <div class="card-body">

    <div class="row">

    <ul class="list-group">
    <li class="list-group-item d-flex justify-content-between align-items-center">
      TRANSPORTE
      <span class="badge bg-primary rounded">FedEx Express</span>
    </li>
    <li class="list-group-item d-flex justify-content-between align-items-center">
      PESO
      <span class="badge bg-primary rounded">'.$total_weight.'</span>
      </li>
    <li class="list-group-item d-flex justify-content-between align-items-center">
      COSTO TOTAL
      <span class="badge bg-primary rounded">'.$order->get_total().'</span>
    </li>
    <li class="list-group-item d-flex justify-content-between align-items-center">
      NUMERO DE SEGUIMIENTO
      <span class="badge bg-primary rounded">'.$masterTrackingNumber.'</span>
    </li>
  </ul>

    </div>


    </div>
  </div>';

    endif;
                         

    // show the label code 64


}


function get_label_shipping($masterTrackingNumber){
    global $wpdb;
    global $table_prefix;

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






    