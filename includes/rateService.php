<?php

class rateService 
{


    public function getRateService()
    {

      // Servicio de cotizador de tarifas
      $curl = curl_init();

      curl_setopt_array($curl, array(
      CURLOPT_URL => 'http://api.trinit.cl/fedex/v1/tarifario/',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>'{
          "servicio": 1,
          "origen": "' . $this->getCityShipper()  . '",
          "destino": "' . WC()->customer->get_shipping_city() . '",
          "peso": "' . $this->getTotalWeight() . '"
      }',
      CURLOPT_HTTPHEADER => array(
          'Content-Type: application/json'
      ),
      ));

      $response = curl_exec($curl);

      curl_close($curl);

      $response = json_decode($response, true);

      return $response['flete'];


    }

    // get data of order
    public function getTotalWeight()
    {
       
        $total_weight = 0;
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $total_weight += $cart_item['data']->get_weight() * $cart_item['quantity'];
        }

        return $total_weight;


    } 


    // get city origin client
    public function getCityShipper()
    {

        global $wpdb;
        global $table_prefix;

        $table_name = $table_prefix . 'fedex_shipping_intra_CL_originShipper';
        $sql = "SELECT cityShipper FROM $table_name WHERE id = 1";

        $cityShipper = $wpdb->get_var($sql);

        return $cityShipper;


    }







}