<?php

class confirmShippingController {

    use configurationTrait;

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

        $params = configurationTrait::account();

        /**Datos de la cuenta */
        $accountNumber = $params['accountNumber'];
        $meterNumber = $params['meterNumber'];
        $wskeyUserCredential = $params['wskeyUserCredential'];
        $wskeyPasswordCredential = $params['wskeyPasswordCredential'];

        if($params['environment'] == 'PRODUCTION'){

            $url = 'https://gtstnt.tntchile.cl/gtstnt/seam/resource/restv1/auth/entregaRecogedorService/entregaRecogida';

        }else{

            $url = 'https://gtstntpre.alertran.net/gts/seam/resource/restv1/auth/entregaRecogedorService/entregaRecogida';
        } 
        
        $confirmation = array();

        foreach ($orderIds as $key => $orderNumber) {

            $ot = $this->wpdb->get_row("SELECT masterTrackingNumber FROM " . $this->table_name_ordersend . " WHERE orderNumber = " . $orderNumber);

            $masterTrackingNumber = $ot->masterTrackingNumber;

            //var_dump("Esta es la masterTrackingNumber: ".$masterTrackingNumber);

       
            $confirmation[$key] = array(
                
                'ENTREGAS' => (
                    array(
                        'ENTREGA' => array(
                            array(

                            "CLIENTE" => $accountNumber,
                            "CENTRO" => "01",
                            "EXPEDICION" => $masterTrackingNumber,
                            "ENVIO_CON_RECOGIDA" => "S",
                            "MANIFIESTO" => "S"
                                
                                )
                            )
                        )
                    )
                );

            $confirmation[$key] = json_encode($confirmation[$key]);


            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $confirmation[$key],
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic U1BFUkVaOkhvbWUuMjAyMA==',
                'Content-Type: application/json',
                'Cookie: JSESSIONID=tA-6PAWthKuiGn76LioJvwQQ8fKDe-xXp2bmei2S.master:backend2'
            ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            $arrayResponse[] = json_decode($response, true);


            foreach ($arrayResponse as $key => $value) {

                $arrayLevel1 = $value;

                foreach($arrayLevel1 as $key => $value2){

                    $arrayLevel2 = $value2;

                    foreach($arrayLevel2 as $key => $value3){

                        $result = $value3;

                        if($result['resultado'] == 'OK'){
                            
                            //Edito el estado de la orden a Enviado con FedEx
                            $post_status = "wc-fedex";

                            $this->wpdb->update(
                                $this->table_name_posts,
                                array(
                                    'post_status' => $post_status,
                                ),
                                array(

                                    'id' => $orderNumber

                                )
                            );

                        }


                    }

                }

            }


        }


     if($result['resultado'] == 'ERROR'){

       //Mensaje de error
        echo json_encode(
            array(
                'status' => $result['resultado'],
                'message' => 'Error en la solicitud: '.$result['mensaje'],
            ), 
            true
        );


        }
        else{

            $select = $this->wpdb->get_results("SELECT * FROM " . $this->table_name_confirmationshipping . " WHERE manifestNumber = " . $result['recogida']);

            if(count($select) == 0){

                $this->wpdb->insert(
                    $this->table_name_confirmationshipping,
                    array(
                        'manifestNumber' => $result['recogida'],
                        'manifestBase64PDF' => $result['manifiesto'],
                        'manifestDate' => date('Y-m-d H:i:s')
                    )
                );

            }
            else{

                $this->wpdb->update(
                    $this->table_name_confirmationshipping,
                    array(

                        'manifestBase64PDF' => $result['manifiesto'],
                        'manifestDate' => date('Y-m-d H:i:s')

                    ),
                    array(
                        
                        'manifestNumber' => $result['recogida']
                    )
                );

            }


        //Mensaje de exito
        echo json_encode(
            array(
                'status' => $result['resultado'],
                'message' => 'Se generó la confirmacion de entrega #'.$result['recogida'],
                'manifestBase64' => $result['manifiesto'],
            ), 
            true
        );



        }



        die();


    }

}