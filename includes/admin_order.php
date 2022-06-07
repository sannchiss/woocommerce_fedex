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

    $order_post_status = null;
    $masterTrackingNumber = null;
    $labelBase64PDF = null;

    foreach ($result as $row) {
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


   

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => END_POINT_PRINT_LABEL,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>$request,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Cookie: _abck=8B703E59AB8F068FFC03F46AD1F9FC51~-1~YAAQHIkKuqM9F1N8AQAASkcwnwaeb5C2xCWK/aeeZP/QkpNcXGV1kNZx9886ivLLOJieyavy6mNGfwwVOz8fG9oEIXO8jQgMyaD5av5yyHaRnwIBqEfcA7ji8Q6ZA/UYsfw9i4890BvYr8mewLJJGf4Iw5WVg9mjvX4adSnXtkHr6xh0w0SrD175t8HPx6ihyv1SlMQAJArB2pqH6E0kSb5V9FXMlZRiA0uhoUR6sf/c8h6R9FKlWciNXcYrUxp4SROH3C4Gd7/6Gj8bcluBZe0RLoA+C3GVrCa7ragFMqDsjF6TShSvsUYm1f/vSs/SncnIxSEMgk9IkSXthF0UkDt1tSCja4pXZ9Zxt1yHFW16j++EvOrBGw==~-1~-1~-1; fdx_cbid=10880496071634758313009000438201; siteDC=wtc'
        ),
        ));

        $ws_response = curl_exec($curl);

        curl_close($curl);



    // tour array $ws_response->body
    $response = json_decode($ws_response, true);

    return $response;

}






    