<?php

// process ajax post request after user upload file
add_action( "wp_ajax_proof", "proof_wp_ajax_function" );
add_action( "wp_ajax_nopriv_proof", "proof_wp_ajax_function" );
//update metabox
add_action( "save_post", "update_proof_meta" );

function proof_wp_ajax_function(){

	// if ($_POST['transaction'] == 'upload_admin') {
	// 	var_dump("UPLAOD ADMIN");die();
	// }

	if ($_POST['transaction'] == 'delete') {
		$upload_path = wp_upload_dir();
		$proof_src = $upload_path['basedir']."/proof/".basename($_POST['proof_src']);
		
		if( file_exists($proof_src) ){
			//update order id to blank. from meta box
			if (isset($_POST['order_id'])) {
				update_post_meta( $_POST['order_id'], '_proof', '' );
			}
			unlink($proof_src);
			wp_die();
		}
		wp_die();
	}

	if ($_POST['transaction'] == 'upload') {
		// Register our path override.
		add_filter( 'upload_dir', 'proof_upload_dir' );

		  $_SESSION['token'] = uniqid();
		  if(isset($_FILES['file'])){
			   $name 			= $_FILES['file']['name'] ;
			   $ext 			= pathinfo($name, PATHINFO_EXTENSION);
			   $type 			= $_FILES['file']['type'] ;
			   $url 			= ($_FILES['file']['tmp_name']) ;
			   $error 			= $_FILES['file']['error'] ;
			   $size 			= ($_FILES['file']['size']) ;
			   $name_prefix 	= "proof";
			   $_FILES['file'] 	= array( 'name' 	=> $name_prefix.'_'.$_SESSION["token"].'_'.time().'.'.$ext,
									   	 'type' 	=> $type,
									   	 'tmp_name' => $url,
									   	 'error' 	=> $error,
									   	 'size' 	=> $size
									   	);
									  
			   	$files_detail= $_FILES['file'] ;
			    if ( ! function_exists( 'wp_handle_upload' ) ) {
					require_once( ABSPATH . 'wp-admin/includes/file.php' );
				}
				
				$file_return = wp_handle_upload($files_detail, array('test_form' => false,'mimes' => get_allowed_mime_types()));
				$file_return['token'] = $_SESSION['token'] ; 
			
			  if(isset($file_return['error'])) {
				echo ($file_return['error']); 
				echo('<input type="hidden" name="_proof" value="">');
			  } else {
			  	echo ('<div id="proof_area">');
			  	echo ('<a target="_blank" href="'.$file_return['url'].'" id="proof_src">'.$name.'</a>');
			  	echo ('<input type="hidden" name="_proof" value="'.urlencode(serialize($file_return)).'">');
			  	echo (' <img src="https://www.flaticon.com/svg/static/icons/svg/3389/3389152.svg" width="20px" id="delete_proof" style="cursor:pointer;">');
			  	echo ('</div>');
			  }		  
			  wp_die(); // this is required to terminate immediately and return a proper response
		  }
		   wp_die(); // this is required to terminate immediately and return a proper response

	    // Set everything back to normal.
		remove_filter( 'upload_dir', 'proof_upload_dir' );
	}
}

function proof_upload_dir( $dirs ) {
	$dir_path = "/proof";
    $dirs['subdir'] = $dir_path;
    $dirs['path'] = $dirs['basedir'] . $dir_path;
    $dirs['url'] = $dirs['baseurl'] . $dir_path;

    return $dirs;
}

function update_proof_meta() {
	if ( !empty($_POST['_proof']) ) {
		update_post_meta( $_POST['post_ID'], '_proof', ($_POST['_proof']) );
	}
}