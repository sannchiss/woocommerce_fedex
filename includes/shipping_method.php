<?php
 
 require_once PLUGIN_DIR_PATH . 'fedex_shipping_intra_Chile.php';


if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {


    add_action( 'woocommerce_shipping_init', 'fedex_shipping_method_init' );


    function fedex_shipping_method_init() {

        if ( ! class_exists( 'WC_METHOD_FEDEX_SHIPPING' ) ) {

            class WC_METHOD_FEDEX_SHIPPING extends WC_Shipping_Method {
  

                public function __construct() {
                    $this->id = 'method_fedex_shipping';
                    $this->method_title = __( 'FedEx Express', 'woocommerce' );
                    $this->method_description = __( 'Método costo de envío FedEx', 'woocommerce' );

                    // add image to shipping method
                    $this->enabled = isset( $this->settings['enabled'] ) ? $this->settings['enabled'] : 'yes';
                    $this->title = isset( $this->settings['title'] ) ? $this->settings['title'] : __( $this->method_title, 'woocommerce');
                   
                    $this->init();
       
                    }


            public function init() {

                
                // Load the settings.
                $this->init_form_fields();
                $this->init_settings();

                //save method shipping	
                add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
                


            }

        
           // calculate_shipping function api rest
            public function calculate_shipping( $package = array() ) {

                $rate = array(
                    'id' => $this->id,
                    'label' => $this->title,
                    'cost' => $this->getRateService(),
                    'calc_tax' => 'per_order'
                );

                
                // if cost is 0, then don't show the rate
                 if ( $rate['cost'] > 0 ) {
                    $this->add_rate( $rate );
                }else if($rate['cost'] == 0 && WC()->customer->get_shipping_city() != ''){
                    $this->add_notice();
                    return;
                } 

            }

                   // function dont repeat add_notice
            public function add_notice() {

                if($this->getRateService() == 0) {
                        wc_clear_notices();
                        $notice = wc_add_notice( __( '<b>Sin cobertura FedEx</b>.', 'woocommerce' ), 'error' );
                        return $notice;
                    }
                   
                }           


            //calculate_volume function
            public function get_cart_volumetric_weight(){
                global $woocommerce;
                $quantity = 0;
                $length = 0;
                $width = 0;
                $height = 0;
                $product_weight = 0;
                $volume_total = 0;
                foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $cart_item ) {
                    $_product = $cart_item['data'];

                    // count articles order
                    $quantity += $cart_item['quantity'];

                    // get volumen total order
                    $length = $_product->get_length();
                    $width = $_product->get_width();
                    $height = $_product->get_height();

                    $volume_total += ($length * $width * $height) * $cart_item['quantity'];

                    // get weight total order
                    $product_weight += $_product->get_weight() * $cart_item['quantity'];

                }

                $weight_volumetric = ($volume_total / 4000) / 250;
                
                if($weight_volumetric > $product_weight ){
                    return $weight_volumetric;
                }
                else{
                    
                    return $product_weight;          
                }
                
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


            // get data of order
            public function getRateService()
            {

                $shipping_city = WC()->customer->get_shipping_city();

                $request = '{
                   "servicio": 1,
                   "origen": "' . $this->getCityShipper()  . '",
                   "destino": "' . $shipping_city . '",
                   "peso": "' . $this->get_cart_volumetric_weight() . '"
               }';

               $ch = curl_init(END_POINT_RATE);
                curl_setopt($ch, CURLOPT_URL, END_POINT_RATE);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 10);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                ));
                $result = curl_exec($ch);
                curl_close($ch);

                $result = json_decode($result, true);

                return ($result['flete'] - (DISCOUNT/100) * $result['flete']); 



            }
            

        }

            
        }
    
    }

    add_filter( 'woocommerce_shipping_methods', 'fedex_shipping_method' );
    
    function fedex_shipping_method( $methods ) {
        $methods['method_fedex_shipping'] = 'WC_METHOD_FEDEX_SHIPPING';
        return $methods;
    }


    function filter_woocommerce_cart_shipping_method_full_label( $label, $method ) {
        if ( 'method_fedex_shipping' === $method->id ) {
            // add icon fasfa-truck
            $label = $label;
        }
        return $label;
    }
    add_filter( 'woocommerce_cart_shipping_method_full_label', 'filter_woocommerce_cart_shipping_method_full_label', 10, 2 );


    

}