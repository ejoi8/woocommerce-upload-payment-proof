<?php
/**
 * Plugin Name: Upload Payment Proof
 * Plugin URI: https://izoolz.com
 * Author Name: Fadzli Zulkefli
 * Author URI: https://www.facebook.com/fadzli.z
 * Description: This plugin allows customer to upload payment proof.
 * Version: 0.1.0
 * License: 0.1.0
 * License URL: http://www.gnu.org/licenses/gpl-2.0.txt
 * text-domain: upload-payment-proof
 *
 * Class WC_Gateway_UPP file.
 *
 * @package WooCommerce\UPP
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) return;

add_action( 'plugins_loaded', 'upp_init', 11 );

function upp_init() {

	if( class_exists( 'WC_Payment_Gateway' ) ) {
		require_once plugin_dir_path( __FILE__ ) . '/includes/class-wc-gateway-upp.php';
		require_once plugin_dir_path( __FILE__ ) . '/includes/upp-checkout-description-field.php';
	}
}

add_filter( 'woocommerce_payment_gateways', 'add_to_woo_upload_proof_payment_gateway');

function add_to_woo_upload_proof_payment_gateway( $gateways ) {
    $gateways[] = 'WC_Gateway_UPP';
    return $gateways;
}
