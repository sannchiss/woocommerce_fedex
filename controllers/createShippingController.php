<?php


class createShippingController {

    use configurationTrait;
    
    public function __construct(){
        
        global $wpdb;
        global $table_prefix;
        
        $this->wpdb = $wpdb;
        $this->table_prefix = $table_prefix;
        $this->table_name_ordersend = $table_prefix . 'fedex_shipping_intra_CL_orderSend';
        $this->table_name_responseshipping = $table_prefix . 'fedex_shipping_intra_CL_responseShipping';
        $this->table_name_localidades = $table_prefix . 'fedex_shipping_intra_CL_localidades';
        $this->table_name_posts = $table_prefix . 'posts';
        
    }

    public function index($collection){

        $params  = configurationTrait::account();

         /**Datos de configuración */
         $accountNumber = $params['accountNumber'];
         $meterNumber = $params['meterNumber'];
         $wskeyUserCredential = $params['wskeyUserCredential'];
         $wskeyPasswordCredential = $params['wskeyPasswordCredential'];



         if($params['environment'] == 'PRODUCTION'){

            $url = 'https://gtstnt.tntchile.cl/gtstnt/seam/resource/restv1/auth/documentarEnvio/json';

        }else{

            $url = 'https://gtstntpre.alertran.net/gts/seam/resource/restv1/auth/documentarEnvio/json';
        } 


       // Selecciono el tipo de etiqueta de la configuración

       if($collection['labelType']== 'PDF'){

            $labelType = 'PDF';

        }elseif($collection['labelType']== 'PDF2'){

            $labelType = 'PDF2';
        
        }elseif($collection['labelType']== 'PNG'){

            $labelType = 'PNG';

        }elseif($collection['labelType']== 'ZPL'){

            $labelType = 'ZPL';    

        }elseif($collection['labelType']== 'EPL'){             

            $labelType = 'EPL';
        }


        // Obtengo la comuna sin tildes/caracteres especiales ...
        $comunaOrCity = $this->clean($collection['cityRecipient']);

        // select like ciudad and code postal sql
        $cityAndCodeSql = $this->wpdb->get_results("SELECT * FROM ".$this->table_name_localidades." WHERE ciudad LIKE '%".$comunaOrCity."%'", ARRAY_A);


        foreach ($cityAndCodeSql as $key => $value) {

            $city = $value['ciudad'];
            $codePostal = $value['codigo'];

        }


     

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
            "DOCUMENTAR_ENVIOS": {
                "DOCUMENTAR_ENVIO": [
                    {
                        "REFERENCIA": "' . $collection['orderNumber'] . '",
                        "NUMERO_ENVIO": "",
                        "CODIGO_ADMISION": "345",
                        "NUMERO_BULTOS": "'.$collection['packages'].'",
                        "CLIENTE_REMITENTE": "' . $accountNumber . '",
                        "CENTRO_REMITENTE": "01",
                        "NIF_DESTINATARIO": "1-9",
                        "NOMBRE_DESTINATARIO": "' . $collection['personNameRecipient'] . '",
                        "DIRECCION_DESTINATARIO": "' . $collection['streetLine1Recipient'] . '",
                        "PAIS_DESTINATARIO": "CL",
                        "CODIGO_POSTAL_DESTINATARIO": "' . $codePostal . '",
                        "POBLACION_DESTINATARIO": "' . $city . '",
                        "PERSONA_CONTACTO_DESTINATARIO": "NONE",
                        "TELEFONO_CONTACTO_DESTINATARIO": "' . $collection['phoneNumberRecipient'] . '",
                        "EMAIL_DESTINATARIO": "' . $collection['emailRecipient'] . '",
                        "CODIGO_PRODUCTO_SERVICIO": "01",
                        "KILOS": "' . $collection['weight'] . '",
                        "VOLUMEN": "' . $collection['volume'] . '",
                        "CLIENTE_REFERENCIA": "' . $collection['orderNumber'] . '",
                        "IMPORTE_REEMBOLSO": 0,
                        "IMPORTE_VALOR_DECLARADO": "0",
                        "TIPO_PORTES": "P",
                        "OBSERVACIONES1": "' . $collection['notesRecipient'] . '",
                        "OBSERVACIONES2": "' . $collection['streetLine2Recipient'] . '",
                        "TIPO_MERCANCIA": "P",
                        "VALOR_MERCANCIA": "0",
                        "MERCANCIA_ESPECIAL": "N",
                        "GRANDES_SUPERFICIES": "N",
                        "PLAZO_GARANTIZADO": "N",
                        "LOCALIZADOR": "",
                        "NUM_PALETS": 0,
                        "FECHA_ENTREGA_APLAZADA": "",
                        "ENTREGA_APLAZADA": "N",
                        "TIPOS_DOCUMENTO": [
                            {
                                "TIPO": "FACT",
                                "REFERENCIA": "123"
                            }
                        ],
                        "GESTION_DEVOLUCION_CONFORME": "N",
                        "ENVIO_CON_RECOGIDA": "N",
                        "IMPRIMIR_ETIQUETA": "S",
                        "ENVIO_DEFINITIVO": "N",
                        "TIPO_FORMATO": "'.$labelType.'"
                    }
                ]
            }
        }',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Basic U1BFUkVaOkhvbWUuMjAyMA==',
            'Content-Type: application/json'
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

        }

    }

}

if($result['resultado'] == 'ERROR' || $result['resultado'] == ''){

    $result['error'] = $result['error'];

 //Retorno mensaje de error
 echo json_encode(
    array(
        "status" => "Error",
        'message' => 'Error en la solictud de envío',
        'comments' =>  $result['mensaje'] == '' ? "Ha sido bloqueada en la respuesta / No obtenida la OT" : $result['mensaje'],
    ), 
    true
);

 
}else{


    //inserto en la tabla de envios
    $this->wpdb->insert( 
        $this->table_name_ordersend, 
        $collection );


    //Edito la tabla orders y le asigno la masterTrackingNumber
    $this->wpdb->update($this->table_name_ordersend, array(
        'masterTrackingNumber' => $result['numero_envio'],
        
    ), array(
        'orderNumber' => $collection['orderNumber'],
    ));


//Insertar respuesta WS en tabla responseshipping

 $this->wpdb->insert($this->table_name_responseshipping, array(
            
    'orderNumber' => $collection['orderNumber'],
    'orderDate' => date('Y-m-d H:i:s'),
    'masterTrackingNumber' => $result['numero_envio'],
    'status' => $flat['status'],
    'labelType' => $collection['labelType'],
    'labelBase64IMG' => $labelType == 'PNG' ? $result['etiqueta'] : '',
    'labelBase64PDF' => $labelType == 'PDF' ? $result['etiqueta'] : '',
    'labelBase64PDF2' => $labelType == 'PDF2' ? $result['etiqueta'] : '',
    'labelBase64ZPL' => $labelType == 'ZPL' ? $result['etiqueta'] : '',
    'labelBase64EPL' => $labelType == 'EPL' ? $result['etiqueta'] : '',

)); 

 //Edito el estado de la orden a Procesado con FedEx
 $post_status = "wc-processes-fedex";
    $this->wpdb->update($this->table_name_posts, array(
        'post_status' => $post_status,

    ), array(
        'id' => $collection['orderNumber'],
    ));



    //Retorno mensaje de Autorizado
    echo json_encode(
        array(
            "status" => "Autorizado",
            'message' => 'Envio Generado y almacenado con exito',
            'masterTrackingNumber' =>  $result['numero_envio'],
            'comments' =>  $result['mensaje'],
        ), 
        true
    );


   
} 


die();

    }



    public function clean($characters_string) {

        $string = trim($characters_string);
     
        $string = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $string
        );
     
        $string = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $string
        );
     
        $string = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $string
        );
     
        $string = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $string
        );
     
        $string = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
            $string
        );
     
        $string = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'),
            array('n', 'N', 'c', 'C',),
            $string
        );
     
        //Esta parte se encarga de eliminar cualquier caracter extraño
    
        $string = str_replace(
            array("\\", "¨", "º", "-", "~",
                 "#", "@", "|", "!", "\"",
                 "·", "$", "%", "&", "/",
                 "(", ")", "?", "'", "¡",
                 "¿", "[", "^", "`", "]",
                 "+", "}", "{", "¨", "´",
                 ">", "< ", ";", ",", ":",
                 "."),
            '',
    
            $string
        );
    
     
     
        return $string;

        
        }



}