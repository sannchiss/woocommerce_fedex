<?php

/**
 * Plugin Name: FedEx envíos nacionales Chile
 * Plugin URI: https://fedex.com
 * Description: FedEx envíos nacionales Chile / Conectados con el mañana
 * Version: 2.0
 * Author: Fedex Chile
 * Author URI: https://www.fedex.com/es-cl/home.html
 * Text Domain: fedex_shipping_intra_Chile
 * Domain Path: /lenguages
 * Licence: GPL2
 * 
 * Plugin de Software libre
 *
 * @package fedex_shipping_intra_Chile
 */
/**
 * Returns the main instance of WC.
 *
 * @since  2.0
 * @return fedex_shipping_intra_Chile
 */

defined('ABSPATH') || exit;

//Constante de la ruta del plugin de nombre: PLUGIN_DIR_PATH
define('PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));



class fedex_shipping_intra_Chile  {

public function __construct() {

    global $wpdb;
    global $table_prefix;

    $this->wpdb = $wpdb;
    $this->table_prefix = $table_prefix;

    $this->table_name_configuration = $table_prefix . 'fedex_shipping_intra_CL_configuration';
    $this->table_name_originShipper = $table_prefix . 'fedex_shipping_intra_CL_originShipper';
    $this->table_name_responseShipping = $table_prefix . 'fedex_shipping_intra_CL_responseShipping';
    $this->table_name_orderDetail = $table_prefix . 'fedex_shipping_intra_CL_orderDetail';
    $this->table_name_posts = $table_prefix . 'posts';
    $this->table_name_confirmationshipping = $table_prefix . 'fedex_shipping_intra_CL_confirmationShipping';
    $this->table_name_locations = $table_prefix . 'fedex_shipping_intra_CL_localidades';


    add_action( 'admin_menu', array( $this, 'fedex_shipping_intra_Chile_menu' ));

    add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ));

    // add filter  masive the name: Procesar con FedEx to order page
    add_filter( 'bulk_actions-edit-shop_order', array( $this, 'add_bulk_actions_process_order_fedex' ));
    /********************************************************************************** */
    // add status (Procesar con FedEx) to order page admin (details order page)
    add_action( 'init', array( $this, 'register_process_order_status_fedex_admin' ));
    add_filter( 'wc_order_statuses', array ($this, 'add_process_order_status_fedex_to_list' ));
    /*********************************************************************************** */

    // mark order status (Procesar con FedEx) to order page order
    add_action( 'admin_action_mark_wc-procesado-fedex', array( $this, 'add_action_mark_wc_procesado_fedex' ));

    /*********************************************************************************** */
    /*********************************************************************************** */

    // add filter masive the name: Confirmar con FedEx to order page
    add_filter( 'bulk_actions-edit-shop_order', array( $this, 'add_bulk_actions_confirm_order_fedex' ));
    /*********************************************************************************** */
    // add status (Confirmar con FedEx) to order page admin (details order page)
    add_action( 'init', array( $this, 'register_confirm_order_status_fedex_admin' )); 
    add_filter( 'wc_order_statuses', array( $this, 'add_confirm_order_status_fedex_to_list' ));

    /*********************************************************************************** */
    // mark order status (Confirmar con FedEx) to order page order
    add_action( 'admin_action_mark_wc-confirmado-fedex', array( $this, 'add_action_mark_wc_confirm_fedex' ));
    
    /*********************************************************************************** */
    /*********************************************************************************** */

    //add_action( 'admin_notices', array($this, 'add_action_admin_notices'));
    add_action( 'admin_head', array( $this, 'add_action_admin_head' ));

    add_action( 'woocommerce_order_status_changed', array( $this, 'action_woocommerce_order_status_changed' ) );

    add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ));
    add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ));
    add_action( 'wp_ajax_save_configuration', array( $this, 'save_configuration' ));
    add_action( 'wp_ajax_save_originShipper', array( $this, 'save_originShipper' ));

    add_action( 'wp_ajax_get_order_detail', array( $this, 'get_order_detail' ));
    add_action( 'wp_ajax_fedex_shipping_intra_Chile_create_OrderShipper', array( $this, 'fedex_shipping_intra_Chile_create_OrderShipper' ));
    add_action( 'wp_ajax_fedex_shipping_intra_Chile_print_label', array($this, 'fedex_shipping_intra_Chile_print_label' ));
    add_action( 'wp_ajax_fedex_shipping_intra_Chile_confirm_send', array($this, 'fedex_shipping_intra_Chile_confirm_send' ));
    add_action( 'wp_ajax_fedex_shipping_intra_Chile_print_manifest', array($this, 'fedex_shipping_intra_Chile_print_manifest' ));
    add_action( 'wp_ajax_fedex_shipping_intra_Chile_get_locations', array($this, 'fedex_shipping_intra_Chile_get_locations' ));
    add_action( 'wp_ajax_fedex_shipping_intra_Chile_track_shipment', array($this, 'fedex_shipping_intra_Chile_track_shipment' ));
    add_action( 'wp_ajax_fedex_shipping_intra_Chile_delete_order', array($this, 'fedex_shipping_intra_Chile_delete_order' ));

    add_action( 'wp_ajax_delete_logs', array($this, 'delete_logs' ));

    $this->required();
    $this->init();
    $this->constants();
    
}

public function required() {

    require_once PLUGIN_DIR_PATH . 'includes/helpers-createTables.php';
    require_once PLUGIN_DIR_PATH . 'lib/RestClient.php';
    require_once PLUGIN_DIR_PATH . 'required/credentialsAccount.php';
    require_once PLUGIN_DIR_PATH . 'includes/clearString.php';
    require_once PLUGIN_DIR_PATH . 'includes/admin_order.php';
    //require_once PLUGIN_DIR_PATH . 'includes/custom_actions_button.php';
    require_once PLUGIN_DIR_PATH . 'includes/checkOut.php';
    require_once PLUGIN_DIR_PATH . 'includes/shipping_method.php';
    require_once PLUGIN_DIR_PATH . 'controllers/printLabelShippingController.php';
    require_once PLUGIN_DIR_PATH . 'controllers/confirmShippingController.php';

}

// define constantes
public function constants() {

    $credentialsAccount = new credentialsAccount;

    define('ACCOUNT_NUMBER', $credentialsAccount->getDataAccount()['accountNumber']);
    define('METER_NUMBER', $credentialsAccount->getDataAccount()['meterNumber']);
    define('WS_KEY_USER_CREDENTIAL', $credentialsAccount->getDataAccount()['wskeyUserCredential']);
    define('WS_KEY_PASSWORD_CREDENTIAL', $credentialsAccount->getDataAccount()['wskeyPasswordCredential']);
    define('SERVICE_TYPE', $credentialsAccount->getDataAccount()['serviceType']);
    define('PACKAGING_TYPE', $credentialsAccount->getDataAccount()['packagingType']);
    define('PAYMENT_TYPE', $credentialsAccount->getDataAccount()['paymentType']);
    define('LABEL_TYPE', $credentialsAccount->getDataAccount()['labelType']);
    define('MEASUREMENT_UNITS', $credentialsAccount->getDataAccount()['measurementUnits']);
    define('FLAG_INSURANCE', $credentialsAccount->getDataAccount()['flagInsurance']);
    define('DISCOUNT', $credentialsAccount->getDataAccount()['discount']);
    define('ENVIRONMENT', $credentialsAccount->getDataAccount()['environment']);
    define('STATUS_CREATE_ORDER', $credentialsAccount->getDataAccount()['statusCreateOrder']);
    define('STATUS_CONFIRM_ORDER', $credentialsAccount->getDataAccount()['statusConfirmOrder']);
    define('END_POINT_RATE', $credentialsAccount->getDataAccount()['endPointRate']);
    define('END_POINT_SHIP', $credentialsAccount->getDataAccount()['endPointShip']);
    define('END_POINT_CONFIRMATION', $credentialsAccount->getDataAccount()['endPointConfirmation']);
    define('END_POINT_PRINT_LABEL', $credentialsAccount->getDataAccount()['endPointPrintLabel']);
    define('END_POINT_CANCEL', $credentialsAccount->getDataAccount()['endPointCancel']);
    define('END_POINT_PRINT_MANIFEST_PDF', $credentialsAccount->getDataAccount()['endPointPrintManifestPdf']);
    define('PERSON_NAME_SHIPPER', $credentialsAccount->getDataAccount()['personNameShipper']);
    define('PHONE_SHIPPER', $credentialsAccount->getDataAccount()['phoneShipper']);
    define('COMPANY_NAME_SHIPPER', $credentialsAccount->getDataAccount()['companyNameShipper']);
    define('EMAIL_SHIPPER', $credentialsAccount->getDataAccount()['emailShipper']);
    define('VAT_NUMBER_SHIPPER', $credentialsAccount->getDataAccount()['vatNumberShipper']);
    define('CITY_SHIPPER', $credentialsAccount->getDataAccount()['cityShipper']);
    define('STATE_OR_PROVINCE_CODE_SHIPPER', $credentialsAccount->getDataAccount()['stateOrProvinceCodeShipper']);
    define('POSTAL_CODE_SHIPPER', $credentialsAccount->getDataAccount()['postalCodeShipper']);
    define('COUNTRY_CODE_SHIPPER', $credentialsAccount->getDataAccount()['countryCodeShipper']);
    define('ADDRESS_LINE1_SHIPPER', $credentialsAccount->getDataAccount()['addressLine1Shipper']);
    define('ADDRESS_LINE2_SHIPPER', $credentialsAccount->getDataAccount()['addressLine2Shipper']);
    define('TAX_ID_SHIPPER', $credentialsAccount->getDataAccount()['taxIdShipper']);
    define('IE_SHIPPER', $credentialsAccount->getDataAccount()['ieShipper']);
   
}


public function init(){

    // require woocommerce
    if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
        throw new Exception('Fedex Shipping Intra Chile needs the WooCommerce plugin to be installed and active.');
    }
    // require curl
    if (!function_exists('curl_init')) {
        throw new Exception('Fedex Shipping Intra Chile needs the CURL PHP extension.');
    }



    $init = new createTables();

    register_activation_hook(__FILE__, array($init, 'configuration'));
    register_activation_hook(__FILE__, array($init, 'originShipper'));
    register_activation_hook(__FILE__, array($init, 'orderDetail'));
    register_activation_hook(__FILE__, array($init, 'responseShipping'));
    register_activation_hook(__FILE__, array($init, 'confirmationShipping'));
    register_activation_hook(__FILE__, array($init, 'cityOrComune'));    
    

}

public function fedex_shipping_intra_Chile_menu() {

    $menus = [];

    $menus[] = [
        'pageTitle' => 'Fedex Shipping Intra Chile',
        'menuTitle' => 'FedEx Express',
        'capability' => 'manage_options',
        'menuSlug' => 'fedex_shipping_intra_Chile',
        'callback' => array($this, 'fedex_shipping_intra_Chile_page'),
        'functionName' => array($this, 'fedex_shipping_intra_Chile_menu_page'),
        'iconUrl' => 'dashicons-cart',
        'position' => 36
    ];


    $this->addMenusPanel($menus);

    $submenu = [];

    $submenu[] = [
        'parent_slug' => 'fedex_shipping_intra_Chile',
        'page_title' => 'Ordenes de envío',
        'menu_title' => 'Ordenes de envío',
        'capabality' => 'manage_options',
        'icon_url' => 'dashicons-admin-site',
        'menu_slug' => plugin_dir_path(__FILE__) . 'views/orders.php',  //Ruta absoluta,
        'functionName' => ''
        
    ];

    $submenu[] = [
        'parent_slug' => 'fedex_shipping_intra_Chile',
        'page_title' => 'Retiros confirmados',
        'menu_title' => 'Retiros confirmados',
        'capabality' => 'manage_options',
        'menu_slug' => plugin_dir_path(__FILE__) . 'views/confirmationShipping.php',  //Ruta absoluta,
        'functionName' => ''
        
    ];
    
    $submenu[] = [
        'parent_slug' => 'fedex_shipping_intra_Chile',
        'page_title' => 'Configuración',
        'menu_title' => 'Configuración',
        'capabality' => 'manage_options',
        'menu_slug' => plugin_dir_path(__FILE__) . 'views/configuration_fdx.php',  //Ruta absoluta,
        'functionName' => ''
        
    ];

    $submenu[] = [
        'parent_slug' => 'fedex_shipping_intra_Chile',
        'page_title' => 'Logs',
        'menu_title' => 'Logs',
        'capabality' => 'manage_options',
        'menu_slug' => plugin_dir_path(__FILE__) . 'views/log.php',  //Ruta absoluta,
        'functionName' => ''
        
    ];
    
    

    $this->addSubmenusPanel($submenu);


}

// function open external page in new tab
public function fedex_shipping_intra_Chile_page() {

    // open page in new tab
    echo '<script>window.open("' . admin_url('https://www.fedex.com/es-cl/home.html') . '", "_blank");</script>';

    // redirect panel wordpress
    wp_redirect(admin_url('admin.php?page=fedex_shipping_intra_Chile'));
}

public function fedex_shipping_intra_Chile_menu_page() {
    // open page in new tab
    echo '<script>window.open("https://www.fedex.com/es-cl/home.html", "_blank");</script>';

}

public function addmenusPanel($menus) {

    foreach ($menus as $menu) {

        add_menu_page(
            $menu['pageTitle'], 
            $menu['menuTitle'], 
            $menu['capability'], 
            $menu['menuSlug'], 
            $menu['functionName'], 
            $menu['iconUrl'], 
            $menu['position']);
    }

}

public function addSubmenusPanel($submenus) {

    foreach ($submenus as $submenu) {

        add_submenu_page(
            $submenu['parent_slug'], 
            $submenu['page_title'], 
            $submenu['menu_title'], 
            $submenu['capabality'], 
            $submenu['menu_slug'], 
            $submenu['functionName']);
    }

}


public function enqueue_scripts(){

    /**Libreria para mensajes */
    wp_register_script( 'jquery', '//code.jquery.com/jquery-3.6.0.js', null, null, true );
    wp_enqueue_script('jquery');

    /**Libreria para mensajes */
    wp_register_script( 'sweetalert2', '//cdn.jsdelivr.net/npm/sweetalert2@11', null, null, true );
    wp_enqueue_script('sweetalert2');


    /**Libreria Bootstrap Js*/
    wp_register_script( 'Bootstrap', '//cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js', null, null, true );
    wp_enqueue_script('Bootstrap');

    /**Libreria para DataTable */
    wp_register_script( 'DataTable', '//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js', null, null, true );
    wp_enqueue_script('DataTable');

    /**Libreria para DataTable */
    wp_register_script( 'DataTableBootstrap', '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js', null, null, true );
    wp_enqueue_script('DataTableBootstrap');

    /**Libreria para DataTable */
    wp_register_script( 'DataTableButtons', '//cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js', null, null, true );
    wp_enqueue_script('DataTableButtons');

    /**Libreria para TypeaHead */  
    wp_register_script( 'typeahead', '//cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js', null, null, true );
    wp_enqueue_script('typeahead');


    wp_enqueue_script(
        'loadConfiguration',
        plugins_url('resources/js/Load-injection.js', __FILE__),
        array('jquery'),
        null,
        '2.0',
        true
    );

    // script validador formulario
    wp_enqueue_script(
        'validatorForm',
        plugins_url('resources/js/validatorForm.js', __FILE__),
        array('jquery'),
        null,
        '2.0',
        true
    );


    // script validador formulario
    wp_enqueue_script(
        'initiation',
        plugins_url('fedex_shipping_intra_Chile.php', __FILE__),
        array('jquery'),
        null,
        '2.0',
        true
    );



  }

public function enqueue_styles() {

    /**Libreria para iconos Fontawesome */
    wp_register_style( 'Font_Awesome', '//use.fontawesome.com/releases/v5.15.4/css/all.css' );
    wp_enqueue_style('Font_Awesome');
    /**Libreria para Bootstrap Css*/
    wp_register_style( 'Bootstrap', '//cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css' );
    wp_enqueue_style('Bootstrap');
    /**Libreria para DataTable */
    wp_register_style( 'DataTableBootstrap', '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css' );
    wp_enqueue_style('DataTableBootstrap');
    
  }


 /******************************************************************************************************** */

// Agrego accion masiva de nombre: Procesar con FedEx en el panel de pedidos
 public function add_bulk_actions_process_order_fedex( $bulk_actions ) {

    $slug = 'wc-procesado-fedex';
    $action = "mark_" . $slug;
        $label = 'Procesar con FedEx';
     
            $bulk_actions[ $action  ] = $label;
     
            return $bulk_actions;

}

 /******************************************************************************************************** */
// Agrego accion masiva de nombre: Procesar con FedEx en detalle de pedido y su vez se lista en el panel de pedidos
 public function register_process_order_status_fedex_admin(){

    $slug = 'wc-procesado-fedex';
    $label = 'Procesar con FedEx';

    register_post_status(
		$slug,
		array(
			'label'		=> 'Procesar con FedEx',
			'public'	=> true,
			'show_in_admin_status_list' => true,
			'label_count'	=> _n_noop( 'Procesar con FedEx (%s)', 'Procesar con FedEx (%s)' )
		)
	);
 }


  public function add_process_order_status_fedex_to_list( $order_statuses ) {

    $slug = 'wc-procesado-fedex';
    $label = 'Procesando con FedEx';

    $order_statuses[ $slug ] = $label;
	return $order_statuses;

   } 


  /******************************************************************************************************** */
 // Marcamos el pedido como Procesado con FedEx
  public function add_action_mark_wc_procesado_fedex(){

    $slug = 'wc-procesado-fedex';
 
        // if an array with order IDs is not present, abort
        if( !isset( $_REQUEST['post'] ) && !is_array( $_REQUEST['post'] ) )  return;
 
        // Loop through the Post Ids and update each of them to the new status.
        foreach( $_REQUEST['post'] as $order_id ) {
 
            $order = new WC_Order( $order_id );
            $order_note = 'El estado de este pedido fue cambiado por una edición masiva:';
            $order->update_status( $slug, $order_note, true );
 
        }

        // Redirect back to the page
        wp_redirect( $_SERVER['HTTP_REFERER'] );
        exit;

    } 


  /******************************************************************************************************** */
  /******************************************************************************************************** */

    // Agrego accion masiva de nombre: Confirma con FedEx en el panel de pedidos   
     public function add_bulk_actions_confirm_order_fedex( $bulk_actions ) {

        $slug = 'wc-confirmado-fedex';
        $action = "mark_" . $slug;
            $label = 'Confirmar con FedEx';
         
                $bulk_actions[ $action  ] = $label;
         
                return $bulk_actions;
    
    } 
    
   /******************************************************************************************************** */


    // Agrego accion masiva de nombre: Confirma con FedEx en detalle de pedido y su vez se lista en el panel de pedidos
     public function register_confirm_order_status_fedex_admin(){

        $slug = 'wc-confirmado-fedex';
        $label = 'Confirmar con FedEx';

        register_post_status(
            $slug,
            array(
                'label'		=> 'Confirmar con FedEx',
                'public'	=> true,
                'show_in_admin_status_list' => true,
                'label_count'	=> _n_noop( 'Confirmar con FedEx (%s)', 'Confirmar con FedEx (%s)' )
            )
        );

        
        
        }


    public function add_confirm_order_status_fedex_to_list( $order_statuses ) {
        
            $slug = 'wc-confirmado-fedex';
            $label = 'Confirmando con FedEx';
        
            $order_statuses[ $slug ] = $label;
            return $order_statuses;
        
    } 

   /******************************************************************************************************** */

    // Marcamos el pedido como Confirmado con FedEx
     public function add_action_mark_wc_confirm_fedex(){
        
            $slug = 'wc-confirmado-fedex';
         
            // if an array with order IDs is not present, abort
            if( !isset( $_REQUEST['post'] ) && !is_array( $_REQUEST['post'] ) )  return;
         
            // Loop through the Post Ids and update each of them to the new status.
            foreach( $_REQUEST['post'] as $order_id ) {
         
                $order = new WC_Order( $order_id );
                $order_note = 'El estado de este pedido fue cambiado por una edición masiva:';
                $order->update_status( $slug, $order_note, true );
         
            }
        
            // Redirect back to the page
            wp_redirect( $_SERVER['HTTP_REFERER'] );
            exit;
        
        } 



/********************************************************************************************************** */
// function to add the icon/background to the status
public function add_action_admin_head(){
    global $pagenow, $post;

    if( $pagenow != 'edit.php') return; // Exit
    if( get_post_type($post->ID) != 'shop_order' ) return; // Exit

    // HERE we set your custom status
    $order_status_process = 'procesado-fedex'; 
    $order_status_sent = 'confirmado-fedex'; 
    ?>
<style>
.order-status.status-<?php echo sanitize_title($order_status_process);

?> {
    background: rgb(134, 47, 222) !important;
    font-weight: bold;
    color: #FFFFFF;
    border: 2px solid #000;
}

.order-status.status-<?php echo sanitize_title($order_status_sent);

?> {
    background: rgb(134, 47, 222) !important;
    font-weight: bold;
    color: #FFFFFF;
    border: 2px solid #000;
}
</style>
<?php
}
// Define woocommerce_order_status_completed callback function
public function action_woocommerce_order_status_changed( $order_id ) {

        //  obtains the status of the order according to the order ID
        $orderId = wc_get_order( $order_id );

        $order_status = $orderId->get_status();

        $this->register_log(date('Y-m-d H:i:s').'__Order: '.$order_id.'  | Estatus de la orden: '.$order_status);

        // get name rate shipping
        $shipping_method = $orderId->get_shipping_method();


        $this->register_log("Registro de Orden: ". $orderId);

         // get deatils of the order
         $order_details = $orderId->get_data();


     if ( $order_status == STATUS_CREATE_ORDER && $shipping_method == "FedEx Express") {


        if(($order_details['billing']['address_1'] != $order_details['shipping']['address_1']) && $order_details['shipping']['city'] != null){
            $this->register_log(date('Y-m-d H:i:s').'__Order: '.$order_id.'  | La dirección de facturación es diferente a la dirección de envío');

            $person_name = substr($order_details['shipping']['first_name'].' '.$order_details['shipping']['last_name'], 0, 40);

            $clearCity  =  $order_details['shipping']['city'];
            $address = substr($order_details['shipping']['address_1'].' #'.$order_details['shipping']['address_2'], 0, 40 );
            
        }
        else {
            $this->register_log(date('Y-m-d H:i:s').'__Order: '.$order_id.'  | La dirección de facturación es igual a la dirección de envío');

            $person_name = substr($order_details['billing']['first_name'].' '.$order_details['billing']['last_name'], 0, 40);

            $clearCity = $order_details['billing']['city'] != null ? $order_details['billing']['city'] : get_post_meta( $order_id, '_billing_comuna', true );
            $address = substr( $order_details['billing']['address_1'].' #'. $order_details['billing']['address_2'] , 0, 40 );

            }

         // Clear string of special characters  
        $clearString = new clearString;
        $comunaClear = $clearString->setString($clearCity);
            // select like ciudad and code postal sql
            $cityAndCodeSql = $this->wpdb->get_results( "
            SELECT * FROM ".$this->table_name_locations." 
                WHERE ciudad LIKE '%".$comunaClear."%'", ARRAY_A 
            );


            $city = $cityAndCodeSql[0]['ciudad'];
            $codePostal = $cityAndCodeSql[0]['codigo'];

        // get order_id details
        $order_features = $this->fedex_shipping_intra_Chile_get_order_detail( $order_id );
       
        //Peso total de la orden
        $weightOrder = $this->get_total_weight_order( $order_id );

        // peso opcional
        $weightOptional = $order_features['weight'] == 0 ? 1 : $order_features['weight'];

        //Peso de la orden real
        $weightTotal = $weightOrder == 0 ? $weightOptional : $weightOrder;     
    
        //Peso volumetrico real
        $weightVolumetricTotal = ( $weightOrder == 0 ? $weightOptional : $weightOrder ) / 250;



        $request = '{
            "credential": {
                "accountNumber": "' . ACCOUNT_NUMBER . '",
                "wskeyUserCredential": "' . WS_KEY_USER_CREDENTIAL . '",
                "wspasswordUserCredential": "' . WS_KEY_PASSWORD_CREDENTIAL . '"
            },
            "shipper": {
                "contact": {
                    "vatNumber": "",
                    "personName": "",
                    "phoneNumber": "999999999",
                    "email": ""
                },
                "address": {
                    "city": "",
                    "postalCode": "",
                    "countryCode": "CL",
                    "streetLine1": ""
                } 
            },
            "recipient": {
                "contact": {
                    "vatNumber": "1-9",
                    "personName": "'. $person_name. '",
                    "phoneNumber": "'.str_replace("+", "", substr( $order_details['billing']['phone'] , 0, 15 ) ).'",
                    "email": "' . substr( str_replace(' ', '', $order_details['billing']['email'] ), 0, 40) . '"
                },
                "address": {
                    "city": "' . $city . '",
                    "postalCode": "' . $codePostal . '",
                    "countryCode": "CL",
                    "streetLine1": "' . $address . '"
                }
            },
            "serviceType": "01",
            "shippingChargesPayment": {
                "paymentType": "P",
                "accountNumber": "' . ACCOUNT_NUMBER . '"
            },
            "servicesLabelPrint": {
                "labelType": "' . LABEL_TYPE . '",
                "printLabel": "N"
            },
            "requestedPackage": {
                "numberOfPieces": "1",
                "weight": "' . $weightTotal . '",
                "volume": "'. $weightVolumetricTotal .'"
            },
            "referencesShip": "' . substr( $order_id, 0 , 20) . '",
            "insuranceShipValue": "0",
            "additionalReferences": "' . substr(  preg_replace( "[\n|\r|\n\r]", "", $order_details['customer_note'] ), 0, 100  ) .'"
        }';


        // try in response web service
        try {          


            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => END_POINT_SHIP,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => $request,
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: fdx_cbid=10880496071634758313009000438201; siteDC=wtc'
               ),
            ));
            
            $ws_response = curl_exec($curl);
            
            curl_close($curl);


        } catch (Requests_Exception $e) {

                // open page in new tab
    echo '<script>window.open("' . admin_url('https://www.fedex.com/es-cl/home.html') . '", "_blank");</script>';


            // if error, show error message
            echo '<div class="error">
                    <p>' . $e->getMessage() . '</p>
                </div>';

                echo "<div class=\"notice notice-success updated\"><p>{$e->getMessage()}</p></div>";

          

        }


        // tour array $ws_response->body
        $response = json_decode($ws_response, true);



        if( $response['comments'] == "OK" ) {


         // Selecciono el tipo de etiqueta de la configuración

       if( LABEL_TYPE == 'PDF' ){

        $labelType = 'PDF';

        }elseif( LABEL_TYPE == 'PDF2'){

            $labelType = 'PDF2';
        
        }elseif( LABEL_TYPE == 'PNG'){

            $labelType = 'PNG';

        }elseif( LABEL_TYPE == 'ZPL'){

            $labelType = 'ZPL';    

        }elseif( LABEL_TYPE == 'EPL'){             

            $labelType = 'EPL';
        }


        // delete order exist in table responseShipping
        $this->wpdb->delete($this->table_name_responseShipping, array('orderNumber' => $order_id));


        //Insertar respuesta WS en tabla responseshipping

        $this->wpdb->insert($this->table_name_responseShipping, array(
                    
            'orderNumber' => $order_id,
            'orderDate' => $order_details['date_created']->date('Y-m-d H:i:s'),
            'masterTrackingNumber' => $response['masterTrackingNumber'],
            'status' => $response['status'],
            'labelType' => LABEL_TYPE,
            'labelBase64IMG' => $labelType == 'PNG' ? $response['labelResp'] : '',
            'labelBase64PDF' => $labelType == 'PDF' ? $response['labelResp'] : '',
            'labelBase64PDF2' => $labelType =='PDF2'? $response['labelResp'] : '',
            'labelBase64ZPL' => $labelType == 'ZPL' ? $response['labelResp'] : '',
            'labelBase64EPL' => $labelType == 'EPL' ? $response['labelResp'] : '',

        )); 

        // write log with date and time
        $this->register_log( array_merge(array('Date' => date('Y-m-d H:i:s')), $response) );


        }
        else {

            // change status order_id table post
            $this->wpdb->update( $this->table_name_posts, array( 'post_status' => 'wc-on-hold' ), array( 'ID' => $order_id ) );


           // $message = sprintf( _n( 'Estatus de la orden cambiando.', '%s estatus orden cambiado.', $_REQUEST['changed'] ), number_format_i18n( $_REQUEST['changed'] ) );
            echo "<div class=\"notice notice-success updated\"><p>Error en la transacción</p></div>";

            // write log with date and time
            $this->register_log( array_merge(array('Date' => date('Y-m-d H:i:s')), $response) );

            ?>

<script>
alert("Error en la solcitud: ".<?php $response['comments'] ?>);
</script>
<?php
        } 
        


      }


      else if($order_status == STATUS_CONFIRM_ORDER && $shipping_method == "FedEx Express") {

        // convert order_id to array
        $order_id_ = array($order_id);

        // add log
        //$this->register_log( array('Date' => date('Y-m-d H:i}:s'), 'Search Order' => $order_id_) );

        $object = new confirmShippingController();
        $object->index($order_id_, $flt = false); 

      }
      
}


 //Obtiene campos de la orden
public function fedex_shipping_intra_Chile_get_order_detail($order_id)  {

    $order_id = sanitize_text_field($order_id);

    $select = $this->wpdb->get_results("SELECT * FROM $this->table_name_orderDetail WHERE orderNumber = $order_id", ARRAY_A);

     if(count($select) > 0){
            
        foreach($select as $row){

            $orderDetail['orderDetail'] = $row;

        }

        }else{
                
            $orderDetail['orderDetail'] = array(

                'dimensionUnits' => 'cm',
                'height' => '0.1',
                'length' => '0.1',
                'quantity' => '1',
                'weight'=> '0.5',
                'weightUnits' => 'kg',
                'width' => '0.5',

            );

    }


        $dataReturn = array();

        // print content of $order_details by cicle for
         foreach ($orderDetail as $key => $value) {

            $dataReturn[$key] = $value;
         }

        return $dataReturn['orderDetail'];


        die();  


  } 


public function get_total_weight_order( $order_id ) {

    $order        = wc_get_order( $order_id );
	$order_items  = $order->get_items();
	$total_qty    = 0;
        $total_weight = 0;
    
	foreach ( $order_items as $item_id => $product_item ) {
		$product         = $product_item->get_product();
		if ( ! $product ) continue;
                $product_weight  = $product->get_weight();        
		$quantity        = $product_item->get_quantity();
                $total_qty      += $quantity;
                $total_weight   += floatval( $product_weight * $quantity );
	}
	
    return $total_weight;


}  



public function save_configuration(){

    $data = $this->unserializeForm($_POST['inputs']);

    $collection = array(

        'accountNumber' => $data['accountNumber'],
        'meterNumber' => $data['meterNumber'],
        'wskeyUserCredential' => $data['wskeyUserCredential'],
        'wskeyPasswordCredential' => $data['wskeyPasswordCredential'],
        'serviceType' => $data['serviceType'],
        'packagingType' => $data['packagingType'],
        'paymentType' => $data['paymentType'],
        'labelType' => $data['labelType'],
        'measurementUnits' => $data['measurementUnits'],
        'flagInsurance' => $data['flagInsurance'],
        'discount' => $data['discount'],
        'environment' => $data['environment'],
        'statusCreateOrder' => $data['statusCreateOrder'],
        'statusConfirmOrder' => $data['statusConfirmOrder'],
        'endPointRate' => END_POINT_RATE,
        'endPointShip' => END_POINT_SHIP,
        'endPointConfirmation' => END_POINT_CONFIRMATION,
        'endPointPrintLabel' => END_POINT_PRINT_LABEL,
        'endPointCancel' => END_POINT_CANCEL,
        'endPointPrintManifestPdf' => END_POINT_PRINT_MANIFEST_PDF,

    );
 

    $sql  = $this->wpdb->get_results("SELECT * FROM ".$this->table_name_configuration." WHERE id = 1");
   

     if(count($sql) > 0){

        $this->wpdb->update($this->table_name_configuration, $collection, array('id' => 1));

        print "Se actualizo la configuración";
        

        $this->register_log( array_merge(array(
            'Date' => date('Y-m-d H:i:s'),
            'Action' => 'Configuración actualizada',        
        ), $collection) );

        die();

    }else{

        $this->wpdb->insert($this->table_name_configuration, $collection);

        print "Se guardo la configuración";
        

        $this->register_log( array_merge(array(
            'Date' => date('Y-m-d H:i:s'),
            'Action' => 'Configuración guardada',        
        ), $collection) );

        die();

     } 
 
 }

public function save_originShipper(){

    $data = $this->unserializeForm($_POST['inputs']);
        
       $collection = array(
        'personNameShipper' => $data['personNameShipper'],
        'phoneShipper' => $data['phoneShipper'],
        'companyNameShipper' => $data['companyNameShipper'],
        'emailShipper' => $data['emailShipper'],
        'vatNumberShipper' => $data['vatNumberShipper'],
        'cityShipper' => $data['cityShipper'],
        'stateOrProvinceCodeShipper' => $data['stateOrProvinceCodeShipper'],
        'postalCodeShipper' => $data['postalCodeShipper'],
        'countryCodeShipper' => $data['countryCodeShipper'],
        'addressLine1Shipper' => $data['addressLine1Shipper'],
        'addressLine2Shipper' => $data['addressLine2Shipper'],
        'taxIdShipper' => $data['taxIdShipper'],
        'ieShipper' => $data['ieShipper'],
    );

    $sql  = $this->wpdb->get_results("SELECT * FROM ".$this->table_name_originShipper." WHERE id = 1");

    if(count($sql) > 0){

        $this->wpdb->update($this->table_name_originShipper, $collection, array('id' => 1));

        print "Se actualizaron los datos";
        die();

    }else{

        $this->wpdb->insert($this->table_name_originShipper, $collection);

        print "Datos guardados";
        die();
    }  

}

// get order detail
public function get_order_detail(){

    $order_id = sanitize_text_field($_POST['orderId']);

    // get list item order
    $order = wc_get_order( $order_id );
    $order_items = $order->get_items();

    foreach($order_items as $item){

        $orderItemsData[] = $item->get_data();

    }

    echo json_encode($orderItemsData, true);
    die();

}


  public function fedex_shipping_intra_Chile_create_OrderShipper(){

    $data = $this->unserializeForm($_POST['inputs']);

    $collection = array(

        'orderNumber' =>  $data['orderNumber'],
        'masterTrackingNumber' => '',
        'orderDate' =>  date('Y-m-d H:i:s'),
        'totalOrderAmount' =>  '',
        'personNameRecipient' =>  $data['personNameRecipient'], 
        'phoneNumberRecipient' =>  $data['phoneNumberRecipient'], 
        'companyNameRecipient' =>  $data['companyNameRecipient'],
        'vatNumberRecipient' =>  $data['vatNumberRecipient'],
        'emailRecipient' =>  $data['emailRecipient'],
        'notesRecipient' =>  $data['notesRecipient'],
        'cityRecipient' =>  $data['cityRecipient'],
        'stateOrProvinceCodeRecipient' =>  $data['stateOrProvinceCodeRecipient'],
        'postalCodeRecipient' =>  $data['postalCodeRecipient'],
        'countryCodeRecipient' =>  $data['countryCodeRecipient'], 
        'streetLine1Recipient' =>  $data['streetLine1Recipient'], 
        'streetLine2Recipient' =>  $data['streetLine2Recipient'],  
        'serviceType' =>  $data['serviceType'],
        'packagingType' =>  $data['packagingType'],
        'paymentType' =>  $data['paymentType'],
        'measurementUnits' =>  $data['measurementUnits'],
        'numberOfPieces' =>  $data['numberOfPieces'],
        'packages' =>  $data['packages'],
        'weight' =>  $data['weight'],
        'weightUnits' =>  $data['weightUnits'],
        'length' =>  $data['length'],
        'width' =>  $data['width'],
        'height' =>  $data['height'],
        'volume' =>  $data['volume'],
        'dimensionUnits' =>  $data['dimensionUnits'],
        'labelType' =>  $data['labelType'], 
        'personNameShipper' =>  $data['personNameShipper'], 
        'phoneShipper' =>  $data['phoneShipper'], 
        'companyNameShipper' =>  $data['companyNameShipper'],
        'emailShipper' =>  $data['emailShipper'],
        'vatNumberShipper' =>  $data['vatNumberShipper'],
        'cityShipper' =>  $data['cityShipper'],
        'stateOrProvinceCodeShipper' =>  $data['stateOrProvinceCodeShipper'],
        'postalCodeShipper' =>  $data['postalCodeShipper'],
        'countryCodeShipper' =>  $data['countryCodeShipper'],
        'addressLine1Shipper' =>  $data['addressLine1Shipper'], 
        'addressLine2Shipper' =>  $data['addressLine2Shipper'], 
        'taxIdShipper' =>  $data['taxIdShipper'], 
        'ieShipper' =>  $data['ieShipper'], 
        'status' =>  $data['status'],
        'error' =>  'error', 

);


        $object = new createShippingController();
        $object->index($collection);



  }


  //Impresión de etiquetas

public function fedex_shipping_intra_Chile_print_label(){    

    $orderId = sanitize_text_field($_POST['orderId']);

    $object = new printLabelShippingController();
    $object->index($orderId);

  }


public function fedex_shipping_intra_Chile_confirm_send(){

    $orderIds = $_POST['orderIds'];

    $object = new confirmShippingController();
    $object->index($orderIds, $flt = true);

    die();

}

public function fedex_shipping_intra_Chile_print_manifest(){

    $manifest = $_POST['manifest'];

    $select = $this->wpdb->get_results("
    SELECT manifestBase64PDF 
        FROM ". $this->table_name_confirmationshipping." 
            WHERE manifestNumber = '".$manifest."'"
        );

    foreach($select as $row){

        $manifestBase64PDF = $row->manifestBase64PDF;

    }

    echo json_encode($manifestBase64PDF, true);

    die();

}

public function fedex_shipping_intra_Chile_get_locations(){

    $city = $_GET['query'];

    $select = $this->wpdb->get_results("SELECT * FROM ". $this->table_name_locations."");


    echo json_encode($select, true);



die();


}


public function fedex_shipping_intra_Chile_track_shipment(){

    $orderId = $_POST['orderId'];


    $select = $this->wpdb->get_results("
    SELECT masterTrackingNumber FROM ". $this->table_name_responseShipping."
         WHERE orderNumber = '".$orderId."'", ARRAY_A
        );

    $resultConfig = $this->wpdb->get_results("SELECT accountNumber FROM $this->table_name_configuration", ARRAY_A);


    $response = [];
    foreach($select as $row){

        $response['masterTrackingNumber'] = $row;

    }

    $config = [];
    foreach($resultConfig as $row){

        $config['accountNumber'] = $row;

    }


    $merged = array_merge($response, $config);

    echo json_encode($merged, true);

    die();


}


public function fedex_shipping_intra_Chile_get_order(){

    $orderId = $_POST['orderId'];

    $select = $this->wpdb->get_results("
    SELECT * FROM ". $this->table_name_responseShipping."
         WHERE orderNumber = '".$orderId."'", ARRAY_A
        );

    echo json_encode($select, true);

    die();


}


// Eliminar Orden de transporte

public function fedex_shipping_intra_Chile_delete_order(){

    $orderId = $_POST['orderId'];

    $select = $this->wpdb->get_results("
    SELECT masterTrackingNumber FROM ". $this->table_name_responseShipping."
         WHERE orderNumber = '".$orderId."'", ARRAY_A
        );


    foreach($select as $row){

        $masterTrackingNumber = $row['masterTrackingNumber'];

    }

    $request = '{
        "webExpediciones": {
            "numeroWebExpedicion": "' . $masterTrackingNumber . '",
            "clienteOrigen": "' . ACCOUNT_NUMBER . '",
        }
    }';


        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => END_POINT_CANCEL,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $request,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Basic U1BFUkVaOkhvbWUuMjAyMA==',
            'Content-Type: application/json'
        ),
        ));

        $ws_response = curl_exec($curl);

        curl_close($curl);

        echo $ws_response;

               
        $response = json_decode($ws_response, true);

        foreach($response as $key => $value){

            $contenido = $value;

        }



        try {

            if($contenido['respuestaAnularWebExpediciones']['resultado'] == "OK"){

                $this->wpdb->delete( $this->table_name_orderSend, array( 'orderNumber' => $orderId ) );

                $this->wpdb->delete( $this->table_name_responseShipping, array( 'orderNumber' => $orderId ) );
        
        
             //Edito el estado de la orden a Procesado con FedEx
            $post_status = "wc-on-hold";
            $this->wpdb->update($this->table_name_posts, array(
                'post_status' => $post_status,
                
            ), array(
                'id' => $orderId,
            )); 
        
    
    
            }else
            {
                throw new Exception("Error al eliminar la orden");
            }

        }
        catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }


    die();

}


// Deseralizar formulario
public function unserializeForm($form){

      foreach ($form as $key => $value) {

          $fill[$value['name']] = $value['value'];

      } 

       return $fill;


  }



  // Registro de logs
  public function register_log($message) {

    // if message is array, convert to string
    if ( is_array( $message  ) || is_object( $message ) ) {
        $message = print_r( $message, true );
    }



    // create folder in woocommerce upload folder
     $upload_dir = wp_upload_dir();
     $upload_dir = $upload_dir['basedir'] . '/woocommerce_logs_register_fedex/';
        if ( ! is_dir( $upload_dir ) ) {

            mkdir( $upload_dir, 0777, true );       
   
               // create file in folder
            $file = fopen( $upload_dir . 'Log_.txt', 'w' );
            //write in file
            fwrite( $file, $message . PHP_EOL );
            fclose( $file );
   
           }else {            
               
                //write in file add jump line file_put_contents
                file_put_contents( $upload_dir . 'Log_.txt', $message . PHP_EOL, FILE_APPEND);
               // fclose( $file );
    
           }

          // die();

    
}


    public function delete_logs(){

        $upload_dir = wp_upload_dir();
        $upload_dir = $upload_dir['basedir'] . '/woocommerce_logs_register_fedex/';

        // vaciar log.txt
        $file = fopen( $upload_dir . 'Log_.txt', 'w' );
        fwrite( $file, '' );
        fclose( $file );

        die();

    }


}

/*Instantiate class*/
$GLOBALS['fedex_shipping_intra_Chile'] = new fedex_shipping_intra_Chile();