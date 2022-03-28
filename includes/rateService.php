<?php

class rateService 
{


    public function getRateService()
    {


      $request = '{
        "servicio": 1,
        "origen": "' . $this->getCityShipper()  . '",
        "destino": "' . WC()->customer->get_shipping_city() . '",
        "peso": "' . $this->getTotalWeight() . '"
    }';



    // Cabecera de la petición
    $headers = array(
        'Accept' => 'application/json', 
        'Content-Type' => 'application/json'
    );
   

    $ws_response = RestClient::post(END_POINT_RATE, $headers, $request, null);

    $response = json_decode($ws_response->body, true);

    return $response['flete'];



    }

    // get data of order
    public function getTotalWeight()
    {
       
        $total_weight = 0;
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $total_weight += $cart_item['data']->get_weight() * $cart_item['quantity'];
        }

        return $total_weight == 0 ? 1 : $total_weight;


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