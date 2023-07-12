<?php


add_filter( 'woocommerce_admin_order_actions', 'add_custom_order_status_actions_button', 100, 2 );
function add_custom_order_status_actions_button( $actions, $order ) {

    // Display the button for all orders that have a 'processing' status
    if ( $order->has_status( array( 'processing' ) ) ) {

        // Set the action button
        $actions['invoice'] = array(
            'url'       => wp_nonce_url( admin_url( 'admin-ajax.php?action=woocommerce_mark_order_status&status=invoice&order_id=' . $order->get_id() ), 'woocommerce-mark-order-status' ),
            'name'      => __( 'Invoice', 'woocommerce' ),
            'action'    => "invoice",
            'icon'      => "invoice"
            
        );

    

        // custom action button pdf
        $actions['pdf'] = array(
            'url'       => wp_nonce_url( admin_url( 'admin-ajax.php?action=woocommerce_mark_order_status&status=pdf&order_id=' . $order->get_id() ), 'woocommerce-mark-order-status' ),
            'name'      => __( 'PDF', 'woocommerce' ),
            'action'    => "pdf",
            'icon'      => "pdf",
            'target'    => "_blank"
            
        );

    }


    return $actions;

}


add_action( 'admin_head', 'add_custom_order_status_actions_button_css' );
function add_custom_order_status_actions_button_css() {

    
    $action_slug = "invoice"; // The key slug defined for your action button
    $action_slug2 = "pdf"; // The key slug defined for your action button

    echo '<style>.wc-action-button-'.$action_slug.'::after { font-family: woocommerce !important; content: "\e029" !important; }</style>';

    // add icon pdf
    

    echo '<style>.wc-action-button-'.$action_slug2.'::after { font-family: woocommerce !important; content: "\e02d" !important; }</style>';








}

















?>