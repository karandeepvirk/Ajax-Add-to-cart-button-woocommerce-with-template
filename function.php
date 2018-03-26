<?php add_action('wp_ajax_add_to_cart_from_front', 'woo_add_to_cart');
add_action('wp_ajax_nopriv_add_to_cart_from_front', 'woo_add_to_cart');
function woo_add_to_cart(){
	 $arrReturn = array(
        'error' => false,
        'message' => '',
        'output' => '',
    );
	parse_str($_REQUEST['data'], $arrData);
    $intProduct = !empty($arrData['product_id_front']) ? $arrData['product_id_front'] : 0;
    $quantity = !empty($arrData['number_of_items']) ? $arrData['number_of_items'] : 0;
    if(!empty($intProduct)){
     $output ='';
     $output = WC()->cart->add_to_cart( $intProduct, $quantity);
     if(!empty($output)){
     	$arrReturn['output'] = $output;
     	echo json_encode($arrReturn);
     }
     die;
    }
}
?>