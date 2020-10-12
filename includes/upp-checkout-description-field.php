<?php 

add_filter('woocommerce_gateway_description','upload_field',20,2);
add_filter('woocommerce_checkout_process','upload_payment_proof_validation');
add_filter('woocommerce_checkout_update_order_meta','upload_payment_proof_update_order_meta',10,1);
add_action('wp_enqueue_scripts', 'upload_script');

function upload_field($description, $payment_id){

	if ('upp' != $payment_id) {
		return $description;
	}
	
	ob_start();

	echo "<div style='display:block;'>";
	echo "<input type='file' name='_proof' id='_proof'>";
	echo '<div id="proof_field" class=""></div>';
	echo '<div id="loading" style="display:none"><center><img src="https://media3.giphy.com/media/x5JDTX5FJRGGS91e0y/200w_d.gif"></center></div>';
	echo "</div>";

	$description = ob_get_clean();

	return $description;
}

function upload_payment_proof_validation(){
	error_log(serialize($_POST));
	if ( 'upp' == $_POST['payment_method'] ) {
		if ( !isset($_POST['_proof']) || empty($_POST['_proof']) ) {
			wc_add_notice('Please upload your payment proof','error');
		}
	}
}

function upload_payment_proof_update_order_meta($order_id){
	// error_log($_POST["_proof"]);
	if ( !empty($_POST['_proof']) ) {
		update_post_meta( $order_id, '_proof', ($_POST['_proof']) );
	}
}


function upload_script() {
	wp_enqueue_script('ajaxcomm', plugins_url('../asset/js/common.js', __FILE__), array('jquery'), '1.1', true );
	wp_localize_script( 'ajaxcomm', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
}