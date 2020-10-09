<?php 

add_filter('woocommerce_gateway_description','upload_field',20,2);
add_filter('woocommerce_checkout_process','upload_payment_proof_validation');
add_filter('woocommerce_checkout_update_order_meta','upload_payment_proof_update_order_meta',10,1);
add_action('wp_enqueue_scripts', 'upload_script');
// process ajax post request after user upload file
add_action( "wp_ajax_proof", "proof_wp_ajax_function" );
add_action( "wp_ajax_nopriv_proof", "proof_wp_ajax_function" );

function upload_field($description, $payment_id){

	if ('upp' != $payment_id) {
		return $description;
	}
	
	ob_start();

	echo "<div style='display:block;'>";
	echo "<input type='file' name='_proof' id='_proof'>";
	echo '<div id="proof_field" class=""><br><br></div>';
	echo "</div>";

	$description = ob_get_clean();

	return $description;
}

function upload_payment_proof_validation(){
	
	// error_log($_FILES["_proof"]["name"]);
	error_log(serialize($_FILES));die;
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
}


function upload_script() {
	wp_enqueue_script('ajaxcomm', plugins_url('../asset/js/common.js', __FILE__), array('jquery'), '1.1', true );
	wp_localize_script( 'ajaxcomm', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
}


function proof_wp_ajax_function(){
  //DO whatever you want with data posted
  //To send back a response you have to echo the result!
  $_SESSION['token'] = uniqid();
  if(isset($_FILES['file'])){
	   $name = $_FILES['file']['name'] ;
	   $type = $_FILES['file']['type'] ;
	   $url = ($_FILES['file']['tmp_name']) ;
	   $error = $_FILES['file']['error'] ;
	   $size = ($_FILES['file']['size']) ;
	   $_FILES['file'] = array( 'name' => 'xxx_tempxxx_'.$_SESSION["token"].'xxx_'.$name,
							   	 'type' => $type,
							   	 'tmp_name' => $url,
							   	 'error' => $error,
							   	 'size' => $size
							   	);
							  
	   	$files_detail= $_FILES['file'] ;
	    if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		}
		
		$file_return = wp_handle_upload($files_detail,array('test_form' => false,'mimes' => get_allowed_mime_types()));
		$file_return['token'] = $_SESSION['token'] ; 
	
	  if(isset($file_return['error'])){
		echo ($file_return['error']); 
		echo('<input type="hidden" name="_proof" value="">');		
	  } else{
	  	echo ('<h1>DAH BVERJAYA</h1><a target="_blank" href="'.$file_return['url'].'">'.$name.'</a><input type="hidden" name="_proof" value="'.urlencode(serialize($file_return)).'">');
	  }
	  
	  wp_die(); // ajax call must die to avoid trailing 0 in your response
  }
	  
   wp_die();
  
  
}