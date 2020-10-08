<?php 

add_filter('woocommerce_gateway_description','upload_field',20,2);
add_filter('woocommerce_checkout_process','upload_payment_proof_validation');
add_filter('woocommerce_checkout_update_order_meta','upload_payment_proof_update_order_meta',10,1);

function upload_field($description, $payment_id){

	if ('upp' != $payment_id) {
		return $description;
	}
	
	ob_start();

	echo "<div style='display:block;'>";
	// echo '<form name="checkout" method="post" class="checkout woocommerce-checkout" action="http://wordpress.test/checkout/" enctype="multipart/form-data" novalidate="novalidate">';
	echo "<input type='file' name='_proof' id='_proof'>";
  	// echo "<input type='submit' value='Upload proof' name='submit' id='upload_proof'>";


	// woocommerce_form_field(
	// 	'payment_number',
	// 	array(
	// 		'type' 	=> 'text',
	// 		'label'	=> __('Payment number','upp'),
	// 		'placeholder'	=> __('Enter you payment number'),
	// 		'class'	=> array('form-row','form-row-wide'),
	// 		'required'	=> true,
	// 	)
	// );

	echo "</div>";
	// echo "</form>";

	$description = ob_get_clean();

	return $description;
}

function upload_payment_proof_validation(){
	error_log($_POST['_proof'][0]);
	if ( 'upp' == $_POST['payment_method'] ) {
		if ( !isset($_POST['_proof']) || empty($_POST['_proof']) ) {
			wc_add_notice('Please upload your payment proof','error');
		}
	}
}

function upload_payment_proof_update_order_meta($order_id){
	if ( !empty($_POST['_proof']) ) {
		update_post_meta( $order_id, '_proof', sanitize_text_field($_POST['_proof']) );
	}


	// if(isset($_POST['_proof'])){
	// 	$custom_fields = $_POST ; 
	// 	foreach ( $custom_fields as $field_name => $val) {
	// 		if ( $field_name == '_proof' ) {
	// 			$meta_key = $field_name;
	// 			$field_value = $val; // WC will handle sanitation
	// 			update_post_meta( $order_id, $meta_key , $field_value ); 
	// 		}
	// 	}
		
		
	// 	$file = unserialize(urldecode($_POST['_proof']));
		
	// 	if ( ! function_exists( 'wp_handle_upload' ) ) {
	// 		require_once( ABSPATH . 'wp-admin/includes/file.php' );
	// 	}

	// 	$get_dir = dirname($file['file']) ; 
	// 	$get_filename = basename($file['file']) ; 
	// 	$strname = explode('xxx_', $file['file']);
	// 	//print_r($strname);
	// 	rename($file['file'], $get_dir.'/'.$order_id.'xxx_'.$strname['3']);
				


	// }


}