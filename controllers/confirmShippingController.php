<?php

class confirmShippingController {

    public function __construct() {

        global $wpdb;
        global $table_prefix;

        $this->wpdb = $wpdb;
        $this->table_prefix = $table_prefix;
        $this->table_name_ordersend = $table_prefix . 'fedex_shipping_intra_CL_orderSend';
        $this->table_name_responseshipping = $table_prefix . 'fedex_shipping_intra_CL_responseShipping';
        $this->table_name_confirmationshipping = $table_prefix . 'fedex_shipping_intra_CL_confirmationShipping';
        $this->table_name_posts = $table_prefix . 'posts';

    }

    public function index($orderIds) {


        foreach ($orderIds as $key => $orderNumber) {

            $getRow = $this->wpdb->get_row("
            SELECT orderNumber,masterTrackingNumber 
                FROM " . $this->table_name_ordersend . " 
                    WHERE orderNumber = " . $orderNumber
                );

            try {

                if(!empty($getRow)){

                    $orderNumber = $getRow->orderNumber;
                    $masterTrackingNumber = $getRow->masterTrackingNumber;


                    $listMasterTrackingNumber[$key] = array(

                        "trackingNumber"=> $masterTrackingNumber,
        
                    );

                    // update status order 

                    $this->wpdb->update(
                        $this->table_name_posts,
                        array(
                            'post_status' => 'wc-fedex'
                        ),
                        array(
                            'id' => $orderNumber
                        )
                    );


                }else{

                    throw new Exception('No se encontró el número de orden de transporte');

                }
           


        }
        catch (Exception $e) {

            echo 'Ha habido una excepción: ' . $e->getMessage() . "<br>";
            

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



  
        // Cabecera de la petición
        $headers = array(
            'Accept' => 'application/json', 
            'Content-Type' => 'application/json'
        );
        $options = array(
            'auth' => array(
                WS_KEY_USER_CREDENTIAL,
                WS_KEY_PASSWORD_CREDENTIAL
            ),
        );


        $ws_response = RestClient::post(END_POINT_CONFIRMATION, $headers, $request, $options);


        // tour array $ws_response->body
        $response = json_decode($ws_response->body, true);




     if($response['result'] == 'ERROR'){

       //Mensaje de error
        echo json_encode(
            array(
                'status' => $response['result'],
                'message' => 'Error en la solicitud: '.$response['message'],
            ), 
            true
        );


        }
        else{

            $select = $this->wpdb->get_results("
            SELECT * FROM " . $this->table_name_confirmationshipping . " 
            WHERE manifestNumber = " . $response['recogida']
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


        //Mensaje de exito
        echo json_encode(
            array(
                'status' => $response['result'],
                'message' => 'Se generó la confirmacion de entrega #'.$response['pickupNumber'],
                'manifestBase64' => $response['manifest'],
            ), 
            true
        );



        }



        die();


    }

}