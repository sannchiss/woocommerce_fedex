<?php

class rateService 
{


    public function getRateService()
    {


        // get shipping state in cart woocommerce
        $shipping_city = WC()->customer->get_shipping_city();

         $request = '{
            "servicio": 1,
            "origen": "' . $this->getCityShipper()  . '",
            "destino": "' . $shipping_city . '",
            "peso": "' . $this->getTotalWeight() . '"
        }';


        $curl = curl_init( END_POINT_RATE );
        curl_setopt( $curl, CURLOPT_POST, true );
        curl_setopt( $curl, CURLOPT_POSTFIELDS, $request );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        $response = curl_exec( $curl );
        curl_close( $curl );

        $body = json_decode($response, true);


        return ($body['flete'] - (DISCOUNT/100) * $body['flete']);  



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