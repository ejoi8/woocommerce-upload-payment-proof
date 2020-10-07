<?php 

add_filter('woocommerce_gateway_description','upload_field',20,2);

function upload_field($description, $payment_id){

	if ('upp' != $payment_id) {
		return $description;
	}
	
	ob_start();

	echo "string";

	$description = ob_get_clean();

	return $description;
}