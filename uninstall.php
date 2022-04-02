<?php

if( ! defined('WP_UNINSTALL_PLUGIN') ){
    exit;
}

global $wpdb;
global $table_prefix;

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

$tableName = $table_prefix . 'fedex_shipping_intra_CL_configuration';
$sql = "DROP TABLE IF EXISTS $tableName";
$wpdb->query($sql);

$tableName = $table_prefix . 'fedex_shipping_intra_CL_originShipper';
$sql = "DROP TABLE IF EXISTS $tableName";
$wpdb->query($sql);

$tableName = $table_prefix . 'fedex_shipping_intra_CL_orderDetail';
$sql = "DROP TABLE IF EXISTS $tableName";
$wpdb->query($sql);

$tableName = $table_prefix . 'fedex_shipping_intra_CL_responseShipping';
$sql = "DROP TABLE IF EXISTS $tableName";
$wpdb->query($sql);

$tableName = $table_prefix . 'fedex_shipping_intra_CL_confirmationShipping';
$sql = "DROP TABLE IF EXISTS $tableName";
$wpdb->query($sql);

$tableName = $table_prefix . 'fedex_shipping_intra_CL_localidades';
$sql = "DROP TABLE IF EXISTS $tableName";
$wpdb->query($sql);





