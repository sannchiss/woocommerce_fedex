<?php

/**
 * Plugin Name: FedEx envíos nacionales Chile
 * Plugin URI: https://fedex.com
 * Description: FedEx envíos nacionales Chile
 * Version: 2.0
 * Author: Sannchiss Pérez
 * Author URI: https://fedex.com
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
    $this->table_name_orderSend = $table_prefix . 'fedex_shipping_intra_CL_orderSend';
    $this->table_name_orderDetail = $table_prefix . 'fedex_shipping_intra_CL_orderDetail';
    $this->table_name_posts = $table_prefix . 'posts';
    $this->table_name_confirmationshipping = $table_prefix . 'fedex_shipping_intra_CL_confirmationShipping';
    $this->table_name_locations = $table_prefix . 'fedex_shipping_intra_CL_localidades';



    add_action('admin_menu', array($this, 'fedex_shipping_intra_Chile_menu'));
    add_action('init', array($this, 'post_status_sent')); 
    add_filter('wc_order_statuses', array($this, 'anadir_posventa_lista_sent'));

    add_action('init', array($this, 'add_status_shipping_fedex'));
    add_filter('wc_order_statuses', array($this, 'add_order_status_shipping_fedex'));

    add_filter('bulk_actions-edit-shop_order', array($this, 'add_bulk_actions_edit_shop_order'));
    add_action('admin_action_mark_wc-procesado-fedex', array($this, 'add_action_mark_wc_procesado_fedex'));
    add_action('admin_notices', array($this, 'add_action_admin_notices'));

    add_action( 'woocommerce_order_status_changed', array( $this, 'action_woocommerce_order_status_changed' ) );

    add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    add_action('admin_enqueue_scripts', array($this, 'enqueue_styles'));
    add_action('wp_ajax_load_configuration', array($this, 'load_configuration'));
    add_action('wp_ajax_save_configuration', array($this, 'save_configuration'));
    add_action('wp_ajax_save_originShipper', array($this, 'save_originShipper'));

    add_action('wp_ajax_fedex_shipping_intra_Chile_create_OrderShipper', array($this, 'fedex_shipping_intra_Chile_create_OrderShipper'));
    add_action('wp_ajax_fedex_shipping_intra_Chile_print_label', array($this, 'fedex_shipping_intra_Chile_print_label'));
    add_action('wp_ajax_fedex_shipping_intra_Chile_confirm_send', array($this, 'fedex_shipping_intra_Chile_confirm_send'));
    add_action('wp_ajax_fedex_shipping_intra_Chile_print_manifest', array($this, 'fedex_shipping_intra_Chile_print_manifest'));
    add_action('wp_ajax_fedex_shipping_intra_Chile_get_locations', array($this, 'fedex_shipping_intra_Chile_get_locations'));
    add_action('wp_ajax_fedex_shipping_intra_Chile_track_shipment', array($this, 'fedex_shipping_intra_Chile_track_shipment'));
    add_action('wp_ajax_fedex_shipping_intra_Chile_delete_order', array($this, 'fedex_shipping_intra_Chile_delete_order'));
    add_action('wp_ajax_fedex_get_select_options', array($this, 'fedex_get_select_options'));

    $this->required();
    $this->init();
    
}

public function required() {


    require_once PLUGIN_DIR_PATH . 'traits/configurationTrait.php';
    require_once PLUGIN_DIR_PATH . 'traits/clearStringTrait.php';

    require_once PLUGIN_DIR_PATH . 'lib/RestClient.php';

    require_once PLUGIN_DIR_PATH . 'includes/rateService.php';
    require_once PLUGIN_DIR_PATH . 'includes/helpers-createTables.php';
    require_once PLUGIN_DIR_PATH . 'includes/checkOut.php';
    require_once PLUGIN_DIR_PATH . 'includes/shipping_method.php';
    require_once PLUGIN_DIR_PATH . 'controllers/createShippingController.php';
    require_once PLUGIN_DIR_PATH . 'controllers/printLabelShippingController.php';
    require_once PLUGIN_DIR_PATH . 'controllers/confirmShippingController.php';

}

public function init(){

    $init = new createTables();

    register_activation_hook(__FILE__, array($init, 'configuration'));
    register_activation_hook(__FILE__, array($init, 'originShipper'));
    register_activation_hook(__FILE__, array($init, 'orderSend'));
    register_activation_hook(__FILE__, array($init, 'orderDetail'));
    register_activation_hook(__FILE__, array($init, 'responseShipping'));
    register_activation_hook(__FILE__, array($init, 'confirmationShipping'));
    register_activation_hook(__FILE__, array($init, 'cityOrComune'));        

}

public function fedex_shipping_intra_Chile_menu() {


    $menus = [];

    $menus[] = [
        'pageTitle' => 'Fedex Shipping Chile',
        'menuTitle' => 'Fedex Shipping Chile',
        'capability' => 'manage_options',
        'menuSlug' => 'fedex_shipping_intra_Chile',
        'menu_slug'  =>  '',  //Ruta absoluta
        'functionName' => '',
        'iconUrl' => plugin_dir_url(__FILE__) . 'resources/img/Fedex-GroundIconWP.png',
        'position' => 19
    ];

    $this->addMenusPanel($menus);

    $submenu = [];

    $submenu[] = [
        'parent_slug' => 'fedex_shipping_intra_Chile',
        'page_title' => 'Gestor de envios',
        'menu_title' => 'Gestor de envios',
        'capabality' => 'manage_options',
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
    
    ;

    $this->addSubmenusPanel($submenu);


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


public function enqueue_scripts()
{

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

 public function enqueue_styles()
 {

    /**Libreria para iconos Fontawesome */
    wp_register_style( 'Font_Awesome', '//use.fontawesome.com/releases/v5.15.4/css/all.css' );
    wp_enqueue_style('Font_Awesome');

    /**Libreria para Bootstrap Css*/
    wp_register_style( 'Bootstrap', '//cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' );
    wp_enqueue_style('Bootstrap');

    /**Libreria para DataTable */
    wp_register_style( 'DataTable', '//cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css' );
    wp_enqueue_style('DataTable');

    /**Libreria para DataTable */
    wp_register_style( 'DataTableBootstrap', '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css' );
    wp_enqueue_style('DataTableBootstrap');

    wp_register_style( 'stackpath', '//stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap-theme.min.css');
    wp_enqueue_style('stackpath');

    
  }


 /******************************************************************************************************** */

 public function add_status_shipping_fedex(){

    $slug = 'wc-procesado-fedex';
    $label = 'Procesado con FedEx';
        register_post_status( $slug, [
            'label'                     => $label,
            'public'                    => true,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop( $label . ' <span class="count">(%s)</span>', $label . ' <span class="count">(%s)</span>' )
        ]);


 }



 public function add_order_status_shipping_fedex( $order_statuses ) {

    $slug = 'wc-procesado-fedex';
    $label = 'Procesado con FedEx';
 
        $new_order_statuses = [
            $slug => $label
        ];
 
        return array_merge( $new_order_statuses, $order_statuses );

 }



public function add_bulk_actions_edit_shop_order( $bulk_actions ) {

    $slug = 'wc-procesado-fedex';
    $action = "mark_" . $slug;
        $label = 'Procesar con FedEx';
     
            $bulk_actions[ $action  ] = $label;
     
            return $bulk_actions;

}


public function add_action_mark_wc_procesado_fedex(){

    $slug = 'wc-procesado-fedex';
 
        // if an array with order IDs is not present, abort
        if( !isset( $_REQUEST['post'] ) && !is_array( $_REQUEST['post'] ) )  return;
 
        // Loop through the Post Ids and update each of them to the new status.
        foreach( $_REQUEST['post'] as $order_id ) {
 
            $order = new WC_Order( $order_id );
            $order_note = 'This orders status was changed by bulk edit:';
            $order->update_status( $slug, $order_note, true );
 
        }
 
        // And it's usually best to redirect the user back to the main order page, with some confirmation variables we can use in our notice:
          $location = add_query_arg( array(
            'post_type' => 'shop_order',
            $slug => 1, // We'll use this as confirmation
            'changed' => count( $post_ids ), // number of changed orders
            'ids' => join( $post_ids, ',' ), // list of ids
            'post_status' => 'all'
        ), 'edit.php' ); 
 
        wp_redirect( admin_url( $location ) ); 
        exit; 
    }
 


public function add_action_admin_notices(){

    global $pagenow, $typenow;
        $status = false;
 
        $listeningStatuses = [
            'wc-procesado-fedex'
        ];
 
        foreach( $listeningStatuses as $listeningStatus )
        {
            if( isset($_REQUEST[ $listeningStatus ]) && $_REQUEST[ $listeningStatus ] == 1 )
            {
                $status = $listeningStatus;
            }
        }
 
 
        if( $typenow == 'shop_order'
            && $pagenow == 'edit.php'
            && $status
            && isset( $_REQUEST['changed'] ) ) {
 
            $message = sprintf( _n( 'Estatus de la orden cambiando.', '%s estatus orden cambiado.', $_REQUEST['changed'] ), number_format_i18n( $_REQUEST['changed'] ) );
            echo "<div class=\"notice notice-success updated\"><p>{$message}</p></div>";
 
        }


}

/**************************************************************************************************** */

// Añado status Enviado con Fedex a la lista de estados de orden

      //Registro del nuevo estado Enviado FedEx
      public function post_status_sent()
      {
          register_post_status('wc-fedex', array(
              'label'                     => 'Enviado con Fedex', //Nombre público
              'public'                    => true,
              'exclude_from_search'       => false,
              'show_in_admin_all_list'    => true,
              'show_in_admin_status_list' => true,
              'label_count'               => _n_noop('Enviado con Fedex (%s)', 'Enviado con Fedex (%s)')
          ));
      }
  
      //Añade estado 'Fedex' al lisatdo disponible de Woocomerce wc_order_statuses
     public function anadir_posventa_lista_sent($order_statuses) 
      {
          $new_order_statuses = array();
          // lo ponemos despues de Completado
          foreach ($order_statuses as $key => $status) {
              $new_order_statuses[$key] = $status;
              if ('wc-completed' === $key) {
                  $new_order_statuses['wc-fedex'] = 'Enviado con Fedex';
              }
          }
          return $new_order_statuses;
      }


/********************************************************************************************************** */


// define woocommerce_order_status_completed callback function
public function action_woocommerce_order_status_changed( $order_id ) {

        //  obtains the status of the order according to the order ID
        $order = wc_get_order( $order_id );
        $order_status = $order->get_status();

        $params  = configurationTrait::account();
        $wskeyUserCredential = $params['wskeyUserCredential'];
        $wskeyPasswordCredential = $params['wskeyPasswordCredential'];
        $endPointShip = $params['endPointShip'];


     if ( $order_status == 'procesado-fedex' ) {

        // get deatils of the order
        $order_details = $order->get_data();

        // get the order id
        $order = $order->get_id();


        // Clear string of special characters  

        $characters_string = $order_details['billing']['city'] != null ? $order_details['billing']['city'] : get_post_meta( $order, '_billing_comuna', true );

        $comunaClear = clearTrait::clearString( $characters_string );

        
            // select like ciudad and code postal sql
            $cityAndCodeSql = $this->wpdb->get_results( "
            SELECT * FROM ".$this->table_name_locations." 
                WHERE ciudad LIKE '%".$comunaClear."%'", ARRAY_A 
            );


            $city = $cityAndCodeSql[0]['ciudad'];
            $codePostal = $cityAndCodeSql[0]['codigo'];




        // get order details
        $order_features = $this->fedex_shipping_intra_Chile_get_order_detail($order);

       
        $volume = $order_features['width'] * $order_features['height'] * $order_features['length'];

        //Peso Volumetrico
        $weightVolumetric = $order_features['weight'] / 250;   
        


        $request = '{
            "credential": {
                "accountNumber": "' . $params['accountNumber'] . '",
                "wskeyUserCredential": "' . $params['wskeyUserCredential'] . '",
                "wspasswordUserCredential": "' . $params['wskeyPasswordCredential'] . '"
            },
            "shipper": {
                "contact": {
                    "vatNumber": "",
                    "personName": "",
                    "phoneNumber": "",
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
                    "personName": "'. $order_details['billing']['first_name'] . ' ' . $order_details['billing']['last_name']. '",
                    "phoneNumber": "' . $order_details['billing']['phone'] . '",
                    "email": "' . $order_details['billing']['email'] . '"
                },
                "address": {
                    "city": "' . $city . '",
                    "postalCode": "' . $codePostal . '",
                    "countryCode": "CL",
                    "streetLine1": "' . $order_details['billing']['address_1'] . '"
                }
            },
            "serviceType": "01",
            "shippingChargesPayment": {
                "paymentType": "P",
                "accountNumber": "' . $params['accountNumber'] . '"
            },
            "servicesLabelPrint": {
                "labelType": "' . $params['labelType'] . '",
                "printLabel": "N"
            },
            "requestedPackage": {
                "numberOfPieces": "1",
                "weight": "' . $order_features['weight'] . '",
                "volume": "'. $weightVolumetric .'"
            },
            "referencesShip": "' . $order . '",
            "insuranceShipValue": "0",
            "additionalReferences": "' . $order_details['customer_note'] . '"
        }';



        // Cabecera de la petición
        $headers = array(
            'Accept' => 'application/json', 
            'Content-Type' => 'application/json'
        );
        $options = array(
            'auth' => array(
                $wskeyUserCredential,
                $wskeyPasswordCredential
            ),
        );


        $ws_response = RestClient::post($endPointShip, $headers, $request, $options);


        // tour array $ws_response->body
        $response = json_decode($ws_response->body, true);



        if( $response['comments'] == "OK" ) {


            //inserto en la tabla de envios
            $insertsql = $this->wpdb->insert( 
            $this->table_name_orderSend, 
            array(        

            'orderNumber' =>  $order,
            'masterTrackingNumber' => $response['masterTrackingNumber'],
            'orderDate' =>  $order_details['date_created']->date('Y-m-d H:i:s'),
            'totalOrderAmount' =>  $order_details['total'],
            'personNameRecipient' =>  $order_details['billing']['first_name'] . ' ' . $order_details['billing']['last_name'], 
            'phoneNumberRecipient' =>  $order_details['billing']['phone'], 
            'companyNameRecipient' =>  $order_details['billing']['company'],
            'vatNumberRecipient' =>  '1-9',
            'emailRecipient' =>  $order_details['billing']['email'],
            'notesRecipient' =>  $order_details['customer_note'],
            'cityRecipient' =>  $order_details['billing']['city'],
            'stateOrProvinceCodeRecipient' =>  $order_details['billing']['state'],
            'postalCodeRecipient' =>  $order_details['billing']['postcode'],
            'countryCodeRecipient' =>  $order_details['billing']['country'], 
            'streetLine1Recipient' =>  $order_details['billing']['address_1'], 
            'streetLine2Recipient' =>  $order_details['billing']['address_2'], 
            'serviceType' =>  $params['serviceType'],
            'packagingType' =>  $params['packagingType'],
            'paymentType' =>  $params['paymentType'],
            'measurementUnits' =>  $params['measurementUnits'],
            'numberOfPieces' =>  $order_details['line_items_count'],
            'packages' =>  '1',
            'weight' =>  $order_features['weight'],
            'weightUnits' =>  $order_details['weight_unit'],
            'length' =>  $order_features['length'],
            'width' =>  $order_features['width'],
            'height' =>  $order_features['height'],
            'volume' =>  $weightVolumetric,
            'dimensionUnits' =>  $order_details['dimension_unit'],
            'labelType' =>  $params['labelType'], 
            'personNameShipper' =>  $order_details['shipping']['first_name'] . ' ' . $order_details['shipping']['last_name'], 
            'phoneShipper' =>  $order_details['shipping']['phone'], 
            'companyNameShipper' =>  $order_details['shipping']['company'],
            'emailShipper' =>  $order_details['shipping']['email'],
            'vatNumberShipper' =>  '1-9',
            'cityShipper' =>  $order_details['shipping']['city'],   
            'stateOrProvinceCodeShipper' =>  $order_details['shipping']['state'],
            'postalCodeShipper' =>  $order_details['shipping']['postcode'],
            'countryCodeShipper' =>  $order_details['shipping']['country'],
            'addressLine1Shipper' =>  $order_details['shipping']['address_1'], 
            'addressLine2Shipper' =>  $order_details['shipping']['address_2'], 
            'taxIdShipper' => '123456789',
            'ieShipper' =>  '123456789',
            'status' => $response['status'],
            'error' =>  '', 

        ) );
        

      // error in the insert
        if ( false === $insertsql ) {
            $error = $this->wpdb->last_error;
            $this->wpdb->print_error();
            print_r($error);
        }



         // Selecciono el tipo de etiqueta de la configuración

       if($params['labelType']== 'PDF'){

        $labelType = 'PDF';

        }elseif($params['labelType']== 'PDF2'){

            $labelType = 'PDF2';
        
        }elseif($params['labelType']== 'PNG'){

            $labelType = 'PNG';

        }elseif($params['labelType']== 'ZPL'){

            $labelType = 'ZPL';    

        }elseif($params['labelType']== 'EPL'){             

            $labelType = 'EPL';
        }


        //Insertar respuesta WS en tabla responseshipping

        $this->wpdb->insert($this->table_name_responseShipping, array(
                    
            'orderNumber' => $order,
            'orderDate' => $order_details['date_created']->date('Y-m-d H:i:s'),
            'masterTrackingNumber' => $response['masterTrackingNumber'],
            'status' => $response['status'],
            'labelType' => $params['labelType'],
            'labelBase64IMG' => $labelType == 'PNG' ? $response['labelResp'] : '',
            'labelBase64PDF' => $labelType == 'PDF' ? $response['labelResp'] : '',
            'labelBase64PDF2' => $labelType =='PDF2'? $response['labelResp'] : '',
            'labelBase64ZPL' => $labelType == 'ZPL' ? $response['labelResp'] : '',
            'labelBase64EPL' => $labelType == 'EPL' ? $response['labelResp'] : '',

        )); 



        }
        elseif( $response['comments'] == "ERROR" ){

           // $message = sprintf( _n( 'Estatus de la orden cambiando.', '%s estatus orden cambiado.', $_REQUEST['changed'] ), number_format_i18n( $_REQUEST['changed'] ) );
            echo "<div class=\"notice notice-success updated\"><p>Error en la transacción</p></div>";


            ?>

        <script>
        alert("Error en la solcitud: ".<?php $response['comments'] ?>);
        </script>

        <?php



        }
        


      }
      


}



 //Obtiene campos de la orden
  public function fedex_shipping_intra_Chile_get_order_detail($order_id)  {

    //$order_id = sanitize_text_field($order_id);


    //Bandera de consulta bulto estandar
    $flagInsurance = $params['flagInsurance'];


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




public function load_configuration(){

    $nonce = sanitize_text_field($_POST['nonce']);


    /* if(!wp_verify_nonce($nonce, 'load_configuration')){
        wp_send_json_error('No tienes permisos para realizar esta acción');
    } */

    

     $resultConfig = $this->wpdb->get_results("SELECT * FROM $this->table_name_configuration", ARRAY_A);
     $resultShipper = $this->wpdb->get_results("SELECT * FROM $this->table_name_originShipper", ARRAY_A);

     if(count($resultConfig) > 0){


        foreach($resultConfig as $row){

            $configuration['configuration'] = $row;

        }

     }else{

        $configuration['configuration'] = [];

     }


     if(count($resultShipper) > 0){

            
            foreach($resultShipper as $row){

                $shipper['shipper'] = $row;
    
            }

        }else{

                
                $shipper['shipper'] = [];

        }


     $merged = array_merge( $configuration, $shipper );

    echo json_encode($merged, true);
    die();

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
        'width' => $data['width'],
        'height' => $data['height'],
        'length' => $data['length'],
        'environment' => $data['environment'],
        'endPointRate' => $data['endPointRate'],
        'endPointShip' => $data['endPointShip'],
        'endPointConfirmation' => $data['endPointConfirmation'],
        'endPointPrintLabel' => $data['endPointPrintLabel'],
        'endPointCancel' => $data['endPointCancel'],
        'endPointPrintManifestPdf' => $data['endPointPrintManifestPdf'],


    );
 

    $sql  = $this->wpdb->get_results("SELECT * FROM ".$this->table_name_configuration." WHERE id = 1");
   

     if(count($sql) > 0){

        $this->wpdb->update($this->table_name_configuration, $collection, array('id' => 1));

        print "Se actualizo la configuración";
        die();

    }else{

        $this->wpdb->insert($this->table_name_configuration, $collection);

        print "Se guardo la configuración";
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
    $object->index($orderIds);

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


// Eliminar Orden de transporte

public function fedex_shipping_intra_Chile_delete_order(){

    $orderId = $_POST['orderId'];
    $params  = configurationTrait::account();
    $wskeyUserCredential = $params['wskeyUserCredential'];
    $wskeyPasswordCredential = $params['wskeyPasswordCredential'];
    $endPointCancel = $params['endPointCancel'];


    $select = $this->wpdb->get_results("
    SELECT masterTrackingNumber 
        FROM ". $this->table_name_responseShipping." 
            WHERE orderNumber = '".$orderId."'", ARRAY_A
        );


    foreach($select as $row){

        $masterTrackingNumber = $row['masterTrackingNumber'];

    }

    $request = '{
        "webExpediciones": {
            "numeroWebExpedicion": "' . $masterTrackingNumber . '",
            "clienteOrigen": "' . $params['accountNumber'] . '",
        }
    }';



 
        // Cabecera de la petición
        $headers = array(
            'Accept' => 'application/json', 
            'Content-Type' => 'application/json'
        );
        $options = array(
            'auth' => array(
                $wskeyUserCredential,
                $wskeyPasswordCredential
            ),
        );

        

        $ws_response = RestClient::post($endPointCancel, $headers, $request, $options);



        // tour array $ws_response->body
       //$response = json_decode($ws_response-, true);

        echo $ws_response->body;



     
        $this->wpdb->delete( $this->table_name_orderSend, array( 'orderNumber' => $orderId ) );

        $this->wpdb->delete( $this->table_name_responseShipping, array( 'orderNumber' => $orderId ) );


     //Edito el estado de la orden a Procesado con FedEx
    $post_status = "wc-on-hold";
    $this->wpdb->update($this->table_name_posts, array(
        'post_status' => $post_status,
        
    ), array(
        'id' => $orderId,
    )); 


    

    die();

}

public function fedex_get_select_options(){

    $curl = curl_init();
    $select_value = $_POST['select_value'];

    
    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.trinit.cl/fedex/v1/geogra/comuna/?pais=CL&region='.$select_value,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    echo $response;

    die();

}



// Deseralizar formulario
  public function unserializeForm($form)
  {

      foreach ($form as $key => $value) {

          $fill[$value['name']] = $value['value'];

      } 

       return $fill;


  }




}

/*Instantiate class*/
$GLOBALS['fedex_shipping_intra_Chile'] = new fedex_shipping_intra_Chile();