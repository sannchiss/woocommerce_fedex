<?php

class rateService 
{


    public function getRateService()
    {

        
 
        // get shipping state in cart woocommerce
        $shipping_state = WC()->customer->get_shipping_state();

        // get shipping state in cart woocommerce
        $shipping_country = WC()->customer->get_shipping_country();

        // get shipping state in cart woocommerce
        $shipping_postcode = WC()->customer->get_shipping_postcode();

        // get shipping state in cart woocommerce
        $shipping_city = WC()->customer->get_shipping_city();
        


      $request = '{
        "servicio": 1,
        "origen": "' . $this->getCityShipper()  . '",
        "destino": "' . $shipping_city . '",
        "peso": "' . $this->getTotalWeight() . '"
    }';

   // WC selected shipping state

    // Cabecera de la petición
    $headers = array(
        'Accept' => 'application/json', 
        'Content-Type' => 'application/json'
    );
   

    $ws_response = RestClient::post(END_POINT_RATE, $headers, $request, null);

    $response = json_decode($ws_response->body, true);

    return ($response['flete'] - (DISCOUNT/100) * $response['flete']);



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