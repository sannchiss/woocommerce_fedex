<?php
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
    $labelBase64Byte = null;


    foreach ($result as $row) {
        $order_post_status = $row->post_status;
        $masterTrackingNumber = $row->masterTrackingNumber;
        $pickupNumber = $row->pickupNumber;

        if($row->labelBase64PDF != null){
            $labelBase64Byte = $row->labelBase64PDF;
        }
        elseif($row->labelBase64PDF2 != null){
            $labelBase64Byte = $row->labelBase64PDF2;
        }
        elseif($row->labelBase64ZPL != null){
            $labelBase64Byte = $row->labelBase64ZPL;
        }

    }


    // get method shipping order
    $method_shipping = $order->get_shipping_method();

    // get status order
    $status_order = $order->get_status();


    if( $method_shipping == "FedEx Express" ):

     // get label shiping
    $label_shipping = get_label_shipping( $masterTrackingNumber, $labelBase64Byte ? $labelBase64Byte:null );

    // get manifest document
    $manifest_document_pdf = get_document_manifest( $pickupNumber );


    // label is null
    if($masterTrackingNumber == null){
        return;
    }
    
    else {    

    // parse code 64 bit
    $pdf = base64_decode($label_shipping['pdfMerge']);

    $pdfManifest = base64_decode($manifest_document_pdf['manifestPDF']);


    echo '<div class="card" style="width: 18rem;">

    <div class="card-header"><i class="fa fa-truck"></i>
    Order #'.$masterTrackingNumber.'</div>

    <iframe src="data:application/pdf;base64,'.$label_shipping['pdfMerge'].'" type="application/pdf" width="100%" height="100%" allowfullscreen></iframe>

    <div class="card-body">

    <div class="row">

    <div class="d-flex justify-content-center">
    
    
    <div class="ms-2 me-auto">
        <div class="btn-group btn-group-sm" role="group" aria-label="Default button group">';

        if($status_order == STATUS_CONFIRM_ORDER):
        echo '<a href="https://gtstnt.tntchile.cl/gtstnt/pub/clielocserv.seam?expedicion='.$masterTrackingNumber.'&cliente='.ACCOUNT_NUMBER.'" target="_blank" class="btn btn-primary btn-sm" type="application/pdf" >
        <icon class="fa fa-car" aria-hidden="true"></icon> Traking</a>

        <a href="data:application/pdf;base64,'.base64_encode($pdfManifest).'" download="manifiesto_fedex_'.$pickupNumber.'.pdf"  target="_blank" class="btn btn-secondary btn-sm" type="application/pdf" width="100%" height="100%" >
        <icon class="far fa-file-pdf" aria-hidden="true"></icon> Manifest</a>';

        endif;
        echo '
        <a href="etiqueta_fedex_'.$masterTrackingNumber.'.pdf" target="_blank" class="btn btn-secondary btn-sm" type="application/pdf" >
        <icon class="fa fa-print" aria-hidden="true"></icon> Label</a>
        <a href="data:application/pdf;base64,'.base64_encode($pdf).'" download="etiqueta_fedex_'.$masterTrackingNumber.'.pdf" class="btn btn-secondary btn-sm">
        <i class="fa fa-download"></i> Label</a>
      

        </div>
        </div>
    
    </div>
      

    </div>


    </div>
  </div>';


    // call function print label
    printAndDownloadLabel( $pdf, $masterTrackingNumber );

    }

    endif;

}


function  get_label_shipping($masterTrackingNumber, $labelBase64Byte){


    if( LABEL_TYPE == "PDF" || LABEL_TYPE == "PDF2" ){

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
            'Authorization: Basic U1BFUkVaOkhvbWUuMjAyMA=='        
        ),
        ));

        $ws_response = curl_exec($curl);

        curl_close($curl);



    // tour array $ws_response->body
    $response = json_decode($ws_response, true);

    return $response;


    }
    elseif(LABEL_TYPE == "ZPL"){


        $curl = curl_init();

                curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://api.labelary.com/v1/printers/8dpmm/labels/4x3/',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => base64_decode($labelBase64Byte),
                CURLOPT_HTTPHEADER => array(
                    'Accept: application/pdf',
                    'Content-Type: application/x-www-form-urlencoded'
                ),
                ));
                
                
            $response = curl_exec($curl);

            curl_close($curl);

            return array(
                'pdfMerge' => base64_encode($response),
               );         
    }

}

function get_document_manifest($pickupNumber){

    $request = '    
    {
        "MANIFIESTOS" : {
            "MANIFIESTO" : [
                {
                    "CLIENTE": "' . ACCOUNT_NUMBER . '",
                    "CENTRO": "01",
                    "RECOGIDA": "' . $pickupNumber . '"
                }
            ]
        }
    }';


        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => END_POINT_PRINT_MANIFEST_PDF,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $request,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Basic Mzg5MzgwM3dzdGVzdDpGZWRleDIwMjM='
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);


        $manifest_document = json_decode($response, true);

     

   // var_dump($manifest_document[0]["respuestaImprimirManifiesto"]["manifiesto"]);


        return array(
                'manifestPDF' => $manifest_document[0]["respuestaImprimirManifiesto"]["manifiesto"],
               ); 

//$response['respuestaImprimirManifiesto']['MANIFIESTO'][0]['PDF'])



}



function printAndDownloadLabel($pdf, $masterTrackingNumber){

    $file = 'etiqueta_fedex_'.$masterTrackingNumber.'.pdf';
    $fp = fopen($file, "w");
    fwrite($fp, $pdf);
    fclose($fp);

}  