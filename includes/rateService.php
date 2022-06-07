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

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, END_POINT_RATE);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($server_output, true);

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