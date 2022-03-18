<?php


class printLabelShippingController {

    use configurationTrait;

    public function __construct() {

        global $wpdb;
        global $table_prefix;

        $this->wpdb = $wpdb;
        $this->table_prefix = $table_prefix;
        $this->table_name_ordersend = $table_prefix . 'fedex_shipping_intra_CL_orderSend';
        $this->table_name_responseshipping = $table_prefix . 'fedex_shipping_intra_CL_responseShipping';
        $this->table_name_posts = $table_prefix . 'posts';

    }

    public function index($orderId) {

        $params = configurationTrait::account();

        /**Datos de la cuenta */
        $accountNumber = $params['accountNumber'];
        $meterNumber = $params['meterNumber'];
        $wskeyUserCredential = $params['wskeyUserCredential'];
        $wskeyPasswordCredential = $params['wskeyPasswordCredential'];

        $labelType = $params['labelType'];

        //var_dump("esta es la orden: ".$orderId);


        $labelPrint = $params['labelType'];
        $sql  = $this->wpdb->get_results("SELECT masterTrackingNumber, labelBase64".$labelPrint." FROM ".$this->table_name_responseshipping." WHERE orderNumber = ".$orderId."", ARRAY_A);

       

         foreach ($sql as $key => $value) {

            $labelBase64 = $value;


        } 


        // url de la peticion a la API
        if($params['environment'] == 'PRODUCTION'){

            $url = 'https://gtstnt.tntchile.cl/gtstnt/seam/resource/restv1/auth/etiquetarService/etiquetar';

        }else{

            $url = 'https://gtstntpre.alertran.net/gts/seam/resource/restv1/auth/etiquetarService/etiquetar';
        } 



  // Selecciono el tipo de etiqueta de la configuración

        if($params['labelType']== 'PDF' || $params['labelType']== 'PDF2'){

            $labelType = $params['labelType'];


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
            CURLOPT_POSTFIELDS =>'{
                "ETIQUETAS" : {
                    "ETIQUETA" : [
                    { 
                        "CLIENTE": "' . $accountNumber . '",
                        "CENTRO": "01",
                        "EXPEDICION": "' . $labelBase64['masterTrackingNumber'] . '",
                        "BULTO": "1", 
                        "FORMATO": "' . $labelType . '",
                        "POSICION": "",
                        "ETIQUETAR":"R"
                    }
                            ]
                        }
            }
            ',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic U1BFUkVaOkhvbWUuMjAyMA==',
                'Content-Type: application/json'
            ),
            ));

            $response = curl_exec($curl);

           

           /*  foreach (json_decode($response, true) as $key => $value) {

                $labelBase64 = $value;

                echo $labelBase64;

            }
 */
            foreach ($response as $key => $value) {

                $labelBase64 = $value;
                
            }



             $label[] = array(
                'status' => 'success',
                'type' => 'PDF',
                'message' => 'Se ha generado el label correctamente',
                'labelBase64' => $labelBase64['labelBase64PDF'],
               );

               curl_close($curl);

               echo json_encode($label, true); 




        }elseif($params['labelType']== 'PNG'){

            $labelType = 'PNG';

        }elseif($params['labelType']== 'ZPL'){


            $curl = curl_init();

                curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://api.labelary.com/v1/printers/8dpmm/labels/4x3/0/',
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





        }elseif($params['labelType']== 'EPL'){             

            $labelType = 'EPL';

        }



        die();

    }

}