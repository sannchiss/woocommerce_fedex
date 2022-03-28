<?php
 
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
                    $this->title = isset( $this->settings['title'] ) ? $this->settings['title'] : __( $this->method_title. " - imp. incl" , 'woocommerce');
                   
                    $this->init();
       
            }


            public function init() {
                // Load the settings.
                $this->init_form_fields();
                $this->init_settings();

                //save method shipping	
                add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );


            }

        
            public function calculate_shipping( $package = array() ) {

                $rate = array(
                    'id' => $this->id,
                    'label' => $this->title,
                    'cost' => $this->Rate(),                    
                    'calc_tax' => 'per_order'
                );

                $this->add_rate( $rate );

            }


            // update caculate shipping
            public function update_rates( $package = array() ) {

                $this->calculate_shipping( $package );

            }


            // update caculate shipping checkout
            public function change_total_on_checking($order) {

                // Get order total
                $total = $order->get_total();

                ## -- Make your checking and calculations -- ##
                $new_total = $total; // <== Fake calculation

                // Set the new calculated total
                $order->set_total($new_total);

            }



            public function Rate() {
                $rateService = new rateService();
                return $rateService->getRateService();
            }
            



        }



            
        }
    
    }

    add_filter( 'woocommerce_shipping_methods', 'add_your_shipping_method' );
    
    function add_your_shipping_method( $methods ) {
        $methods['method_fedex_shipping'] = 'WC_METHOD_FEDEX_SHIPPING';
        return $methods;
    }



    function filter_woocommerce_cart_shipping_method_full_label( $label, $method ) {
        
        // Use the condition here with $method to apply the image to a specific method.      
        if( $method->method_id === "method_fedex_shipping" ) {
            $label = " <i class='fas fa-shipping-fast'></i>". " ".  $label;
       
            
       
        } 
        
        return $label; 
    }
    add_filter( 'woocommerce_cart_shipping_method_full_label', 'filter_woocommerce_cart_shipping_method_full_label', 10, 2 ); 





// Auto Add Tax Depending On Room Per Night Price
/* add_action( 'woocommerce_cart_calculate_fees','auto_add_tax_for_room', 10, 1 );
function auto_add_tax_for_room( $cart ) {
    if ( is_admin() && ! defined('DOING_AJAX') ) return;
  
    $percent = 18;

    // Calculation
    $surcharge = ( $cart->cart_contents_total + $cart->shipping_total ) * $percent / 100;

    // Add the fee (tax third argument disabled: false)
    $cart->add_fee( __( 'TAX', 'woocommerce')." ($percent%)", $surcharge, false );
} */





    

}