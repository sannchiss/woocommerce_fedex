<?php

require_once PLUGIN_DIR_PATH . 'fedex_shipping_intra_Chile.php';

class printLabelShippingController extends fedex_shipping_intra_Chile {

    public function __construct() {

        global $wpdb;
        global $table_prefix;

        $this->wpdb = $wpdb;
        $this->table_prefix = $table_prefix;
        $this->table_name_responseshipping = $table_prefix . 'fedex_shipping_intra_CL_responseShipping';
        $this->table_name_posts = $table_prefix . 'posts';

    }

    public function index($orderId) {

         // instancia para registrar log
         $logReg = new printLabelShippingController;


        $sql  = $this->wpdb->get_results("SELECT masterTrackingNumber, labelBase64".LABEL_TYPE." 
        FROM ".$this->table_name_responseshipping." 
        WHERE orderNumber = ".$orderId."", ARRAY_A);

         foreach ($sql as $key => $value) {

            $labelBase64 = $value;

        } 


  // Selecciono el tipo de etiqueta de la configuración

        if( LABEL_TYPE == 'PDF' || LABEL_TYPE == 'PDF2' ){
            


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
                        "trackingNumber": "' . $labelBase64['masterTrackingNumber'] . '"
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


             $label[] = array(
                'status' => 'success',
                'type' => 'PDF',
                'message' => 'Se ha generado el label correctamente',
                'labelBase64' => $response['pdfMerge'],
               );


               echo json_encode($label, true); 

               $logReg->register_log(
                array(
                'OT' => $labelBase64['masterTrackingNumber'],
                'fecha' => date('Y-m-d H:i:s'),
                'status' => 'success',
                'type' => 'PDF',
                'message' => 'Se ha generado la etiqueta correctamente'               
                ));



        }elseif( LABEL_TYPE == 'PNG' ){

            $labelType = 'PNG';

        }elseif( LABEL_TYPE == 'ZPL' ){

            //var_dump(base64_decode($labelBase64['labelBase64ZPL']));

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
                CURLOPT_POSTFIELDS => base64_decode($labelBase64['labelBase64ZPL']),
                CURLOPT_HTTPHEADER => array(
                    'Accept: application/pdf',
                    'Content-Type: application/x-www-form-urlencoded'
                ),
                ));
                
                
                $response = curl_exec($curl);


                $label[] = array(
                    'status' => 'success',
                    'type' => 'ZPL',
                    'message' => 'Se ha generado el label correctamente',
                    'labelBase64' => base64_encode($response),
                   );

                   curl_close($curl);

                   echo json_encode($label, true);

                   $logReg->register_log(
                    array(
                    'OT' => $labelBase64['masterTrackingNumber'],
                    'fecha' => date('Y-m-d H:i:s'),
                    'status' => 'success',
                    'type' => 'ZPL',
                    'message' => 'Se ha generado la etiqueta correctamente'               
                    ));



        }elseif($params['labelType']== 'EPL'){             

            $labelType = 'EPL';

        }



        die();

    }

}