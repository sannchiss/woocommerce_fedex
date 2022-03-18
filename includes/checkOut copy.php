<?php

if (!defined('ABSPATH')) {
    die();
}






/**
* Función para remover el campo de "Nombre de la Empresa" del formulario de checkout
*/
function claserama_edit_checkout_fields($fields){
    unset($fields['billing']['billing_company']);
   // unset( $fields['billing']['billing_state'] );
    return $fields;
}
add_filter('woocommerce_checkout_fields','claserama_edit_checkout_fields');


// Función  para establecer ordem de los campós del formulario de checkout
function rpf_edit_default_address_fields($fields) {

    /* ------ reordering ------ */
    $fields['country']['priority'] = 10;
    $fields['first_name']['priority'] = 20;
    $fields['last_name']['priority'] = 30;
    $fields['address_1']['priority'] = 40;
    $fields['address_2']['priority'] = 50;
    $fields['city']['priority'] = 70;
    $fields['state']['priority'] = 60;
    $fields['postcode']['priority'] = 80;
  
    return $fields;
  }
  add_filter( 'woocommerce_default_address_fields', 'rpf_edit_default_address_fields', 100, 1 );



/****************************************************************************************************
 * 
 */

 /**
 * Add html
 *
 * @version 1.0.0
 * @since   1.0.0
 */
add_action( 'woocommerce_after_checkout_billing_form', 'add_box_option_to_checkout' );
function add_box_option_to_checkout( $checkout ) {

    echo '<div id="my-new-field"><h4><i class="fas fa-shipping-fast"></i> '.__('Envía con FedEx ').'</h4>';
    	woocommerce_form_field( 'add_gift_box', array(
		'type'          => 'checkbox',
		'class'         => array('add_gift_box form-row-wide'),      
		'label'         => esc_html__( 'Activar', '@@pkg.textdomain' ),
		'placeholder'   => '',
        
	), $checkout->get_value( 'add_gift_box' ));
	echo '</div>';
}

/**
 * Add Javascript
 *
 * @version 1.0.0
 * @since   1.0.0
 */
add_action( 'wp_footer', 'woocommerce_add_gift_box' );

function woocommerce_add_gift_box() {

	if (is_checkout()) {
		?>
		<script type="text/javascript">
			jQuery( document ).ready(function( $ ) {

				$('#add_gift_box').click(function(){
					jQuery('body').trigger('update_checkout');

				});

                $('#billing_city').change(function(){

                    jQuery('body').trigger('update_checkout');

                   
                });

                
            });

			
		</script>
		<?php


	}


}




/**
 * Add fee to cart
 *
 * @link    https://docs.woocommerce.com/document/add-a-surcharge-to-cart-and-checkout-uses-fees-api/
 * @version 1.0.0
 * @since   1.0.0
 */
add_action('woocommerce_cart_calculate_fees', 'woo_add_cart_fee');

function woo_add_cart_fee($cart)
{

    global $wpdb;
    global $table_prefix;

    // Obtengo la cuidad / comuna origen remitente

    $selectCity = $wpdb->get_results("SELECT cityShipper FROM ".$table_prefix."fedex_shipping_intra_CL_originShipper");

    foreach ($selectCity as $key => $value) {
        $cityShipper = $value->cityShipper;
    }

    /**************************************** */

    if (!$_POST || (is_admin() && !is_ajax())) {
        return;
    }


    if (isset($_POST['post_data'])) {

        parse_str($_POST['post_data'], $post_data);

        //	print_r(WC()->cart->cart_contents_weight);

        //	print_r($post_data);


    } else {
        $post_data = $_POST;
    }

    if (isset($post_data['add_gift_box'])) {

        $billingCity = $post_data['billing_city'] == NULL ? $post_data['billing_comuna'] : $post_data['billing_city'];

        $weigthTotal = WC()->cart->cart_contents_weight;


        if(get_option('woocommerce_weight_unit') == 'kg'){
            $weigthTotal = $weigthTotal;
        }
        elseif(get_option('woocommerce_weight_unit') == 'lbs'){
            $weigthTotal = $weigthTotal * 0.453592;            
        }
        elseif(get_option('woocommerce_weight_unit') == 'oz'){
            -$weigthTotal = $weigthTotal * 0.0283495;            
        }
        elseif(get_option('woocommerce_weight_unit') == 'g'){
            $weigthTotal = $weigthTotal * 0.001;            
        } 


        $weigthTotal = round($weigthTotal, 2);


        //var_dump(WC()->cart->cart_contents_weight);


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
            "origen": "'.$cityShipper.'",
            "destino": "'.$billingCity.'",
            "peso": "'. $weigthTotal .'"
        }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response = json_decode($response, true);

        $cart->add_fee( 'Envío con FedEx', $response['flete'], true, 'standard' );


        //WC()->cart->cart_contents_weight . ' ' . get_option('woocommerce_weight_unit') ->Peso Total del pedido

        //WC()->cart->add_fee(esc_html__('Envío con FedEx', '@@pkg.textdomain'), $tarifa);
    }
}


//Cambio el Total del Envio en Order details
add_action('woocommerce_checkout_create_order', 'change_total_on_checking', 20, 1);
function change_total_on_checking($order)
{
    // Get order total
    $total = $order->get_total();

    ## -- Make your checking and calculations -- ##
    $new_total = $total; // <== Fake calculation

    // Set the new calculated total
    $order->set_total($new_total);
}




add_action('woocommerce_checkout_order_processed', 'action_checkout_order_processed', 10, 1);


function action_checkout_order_processed($order_id)
{

    // Declaración global Base de datos
    global $wpdb;
    global $table_prefix;

    // get an instance of the order object
    $order = wc_get_order($order_id);

    $order_data = $order->get_data(); // The Order data

    /*********************************************************************** */

    $table = $table_prefix . "fedex_shipping_intra_CL_orderDetail";

      // Loop through cart items
      foreach (WC()->cart->get_cart() as $cart_item) {
        // Get an instance of the WC_Product object and cart quantity
        $product = $cart_item['data'];
        $qty     = $cart_item['quantity'];

        // Get product dimensions  
        $length += $product->get_length() * $qty; // Longitud
        $width  += $product->get_width() * $qty;  // Ancho
        $height += $product->get_height() * $qty; // Altura

        // Calculations a item level
        $volume += $length * $width * $height * $qty;

        
    }


 
    $data = array(

        'orderNumber' => $order->get_id(),
        'weight' => WC()->cart->cart_contents_weight,
        'weightUnits' => get_option('woocommerce_weight_unit'),
        'length' => $length,
        'width' => $width,
        'height' => $height,
        'dimensionUnits' => get_option('woocommerce_dimension_unit'),
        'productDescription' => '',
        'quantity' =>  $order->get_item_count(),
        'totalPrice' => $order->get_total(),
        'created_at' =>  $order_data['date_created']->date('Y-m-d H:i:s')

    );


    $wpdb->insert($table, $data, null);




}