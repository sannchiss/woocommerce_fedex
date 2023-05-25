<?php
require_once PLUGIN_DIR_PATH . 'fedex_shipping_intra_Chile.php';

class confirmShippingController extends fedex_shipping_intra_Chile{

    public function __construct() {

        global $wpdb;
        global $table_prefix;

        $this->wpdb = $wpdb;
        $this->table_prefix = $table_prefix;
        $this->table_name_responseshipping = $table_prefix . 'fedex_shipping_intra_CL_responseShipping';
        $this->table_name_confirmationshipping = $table_prefix . 'fedex_shipping_intra_CL_confirmationShipping';
        $this->table_name_posts = $table_prefix . 'posts';

    }

    public function index($orderId, $flt) {

        // instancia para registrar log
        $logReg = new confirmShippingController;
        

        $listMasterTrackingNumber = array();

        foreach ($orderId as $key => $orderNumber) {


            $getRow = $this->wpdb->get_row("
            SELECT orderNumber,masterTrackingNumber 
                FROM " . $this->table_name_responseshipping . " 
                    WHERE orderNumber = " . $orderNumber
                );


            try {
                //  select $getRow is not empty

                if(!empty($getRow)){

                    $orderNumber = $getRow->orderNumber;
                    $masterTrackingNumber = $getRow->masterTrackingNumber;

                    $listMasterTrackingNumber[$key] = array(

                        "trackingNumber"=> $masterTrackingNumber,
        
                    );

                }else{

                    throw new Exception('No se encontró el número de orden de transporte');
                    $logReg->register_log('No se encontró el número de orden de transporte'. date('Y-m-d H:i:s'). '___' . $e->getMessage());

                }
           


        }
        catch (Exception $e) {

            echo 'Ha habido una excepción: ' . $e->getMessage() . "<br>";
            $logReg->register_log('Ha habido una excepción: '. date('Y-m-d H:i:s'). '___' . $e->getMessage());
            

        }


        }



        // Request Shipping Confirmation
        $request = '
        {
            "credential": {
                "accountNumber": "'. ACCOUNT_NUMBER .'",
                "wskeyUserCredential": "' . WS_KEY_USER_CREDENTIAL . '",
                "wspasswordUserCredential": "' . WS_KEY_PASSWORD_CREDENTIAL . '"
            },
            "pickupConfiguration": {
                "shippingPickup": "S",
                "manifest": "S"
            },
            "shippingOrders": 
                ' . json_encode($listMasterTrackingNumber) . '
            
        }';


        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => END_POINT_CONFIRMATION,
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



      if($response['result'] == 'ERROR'){

       //Mensaje de error
        echo json_encode(
            array(
                'status' => $response['result'],
                'message' => 'Error en la solicitud: '.$response['message'],
            ), 
            true
        );

        $logReg->register_log('Ha habido una excepción:'. date('Y-m-d H:i:s'). '___' . json_encode(
            array(
                'status' => $response['result'],
                'message' => 'Error en la solicitud: '.$response['message'],
            ), 
            true
        ));



        }
        else{


            foreach ($orderId as $key => $orderNumber) {

                // update status order 

                $this->wpdb->update(
                    $this->table_name_posts,
                    array(
                        'post_status' => 'wc-'.STATUS_CONFIRM_ORDER
                    ),
                    array(
                        'id' => $orderNumber
                    )
                );


            }



            $select = $this->wpdb->get_results("
            SELECT * FROM " . $this->table_name_confirmationshipping . " 
            WHERE manifestNumber = " . $response['pickupNumber']
        );

            if(count($select) == 0){

                $this->wpdb->insert(
                    $this->table_name_confirmationshipping,
                    array(
                        'manifestNumber' => $response['pickupNumber'],
                        'manifestBase64PDF' => $response['manifest'],
                        'manifestDate' => date('Y-m-d H:i:s')
                    )
                );

            }
            else{

                $this->wpdb->update(
                    $this->table_name_confirmationshipping,
                    array(

                        'manifestBase64PDF' => $response['manifest'],
                        'manifestDate' => date('Y-m-d H:i:s')

                    ),
                    array(
                        
                        'manifestNumber' => $response['pickupNumber']
                    )
                );

            }

        $manifest[] = array(
            'status' => $response['result'],
            'message' => 'Se generó la confirmacion de entrega #'.$response['pickupNumber'],
            'manifestBase64' => $response['manifest'],
        ); 
        
        if($flt == true)
        {
        //Mensaje de exito
        echo json_encode(
            $manifest, 
            true
        );

        }

        $logReg->register_log('Se generó la confirmacion: ' . date('Y-m-d H:i:s'). '___' . json_encode(
            $manifest, 
            true
        ));




        } 



      //  die();


    }

}
?>