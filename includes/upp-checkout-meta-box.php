<?php

// Add your custom action buttons
add_action( 'woocommerce_admin_order_actions_end', 'admin_order_actions_custom_btn' );
// Adding Meta container admin shop_order pages
add_action( 'add_meta_boxes', 'proof_meta_boxes' );

add_action( 'admin_enqueue_scripts', 'upload_script_meta' );


function admin_order_actions_custom_btn( $order ) {

    // create some tooltip text to show on hover
    $tooltip = __('Proof', 'proof');

    // create a button label
    $label = __('Proof', 'proof');

    // get order line items
    $order_items = $order->get_items();

    // get the first item
    $first_item  = reset( $order_items );
	$metaitem = get_post_meta($order->get_id());
    // get 'street-name' order item custom meta data
 
	if(isset($metaitem['_proof'])){
		if ($metaitem['_proof'][0] != '') {
			$proof_item = unserialize(urldecode($metaitem['_proof'][0]));
		echo '<a class="button tips custom-class wc-action-button-proof" href="'.$proof_item["url"].'" data-tip="'.$tooltip.'" target="_blank">'.$label.'</a>';
		}
	}
}

if ( ! function_exists( 'proof_meta_boxes' ) )
{
    function proof_meta_boxes()
    {	
    	global $post;
    	$order = new WC_Order($post->ID);
    	//show meta box only if payment == upp
    	if ($order->get_payment_method() == 'upp') {
        	add_meta_box( 'proof_meta_boxes', __('Proof Upload','woocommerce'), 'add_proof_field_upload_btn', 'shop_order', 'side', 'core' );
    	}
    }
}

// Adding Meta field in the meta container admin shop_order pages
if ( ! function_exists( 'add_proof_field_upload_btn' ) )
{
    function add_proof_field_upload_btn($order_id)
    {
        global $post;
		$tooltip = __('Proof', 'proof');
		// create a button label
		$label = __('Click to view Proof', 'proof');
		$meta_field_data = get_post_meta( $post->ID, '_proof', true) ? get_post_meta( $post->ID, '_proof', true ) : '';
		$proof_item = unserialize(urldecode($meta_field_data));

		if ($meta_field_data) {
			echo '<ul class="order_actions submitbox">';
			echo '<li class="wide">';
			echo '<a class="button tips custom-class wc-action-button-proof" href="'.$proof_item['url'].'" data-tip="'.$tooltip.'"target="_blank">'.$label.'</a>';
			echo '</li>';
			echo '<li class="wide">';
			echo '<div id="delete-action">';
			echo '<form>';
			//click delete > common.js > upp-checkout-process.php
			echo '<a target="_blank" class="submitdelete deletion" id="delete_proof_admin" style="cursor:pointer;">Delete proof</a>';
			echo '<input type="hidden" name="proof_src" id="proof_src" value="'.($proof_item['url']).'">';
			echo '<input type="hidden" name="order_id" id="order_id" value="'.($order_id->ID).'">';
			echo '</form>';
			echo '</div>';
			// echo '<button type="submit" class="button save_order button-primary" name="save" value="Update">Update</button>';
			echo '</li>';
			echo '</ul>';
		} else {
			//only show when _proof is blank = no proof
			echo '<ul class="order_actions submitbox">';
			echo '<form id="submit_proof">';
			echo '<li class="wide" id="upload_proof_section">';
			echo '<input type="file" name="_proof" id="_proof">';
			echo '<div id="proof_field" class=""></div>';
			echo '<div id="loading" style="display:none"><center><img src="https://media3.giphy.com/media/x5JDTX5FJRGGS91e0y/200w_d.gif"></center></div>';
			echo '<li class="wide">';
			echo '<button type="submit" class="button save_order button-primary" style="float:left;">Update proof</button>';
			echo '</li>';
			echo '</li>';
			echo '</form>';
			echo '</ul>';
		}
    }
}

function upload_script_meta() {
	wp_enqueue_script('ajax_meta', plugins_url('../asset/js/common.js', __FILE__), array('jquery'), '1.1', true );
	wp_localize_script( 'ajax_meta', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
}
