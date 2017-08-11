<?php
/**
 * Plugin Name: WooCommerce Customization
 * Description: Customizations to default WooCommerce settings and verbiage.
 * Version: 0.1.4
 * Author: Aaron Forsyth
 * Author URI: http://clas-pages.uncc.edu/forsyth
 * GitHub Plugin URI: https://github.com/clas-web/woocommerce-customization
 */

/* Fix Variable Item request button - Only load in frontend */
if ( ! is_admin() ) {
	// deregister script
	wp_deregister_script( 'jquery-cookie' ); 
	add_action( 'wp_enqueue_scripts', 'woocommerce_jquery_cookie_script' );
	
	function woocommerce_jquery_cookie_script() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_register_script( 'jquery-cookie', plugins_url( 'jquery_cookie' . $suffix . '.js', __FILE__ ), array( 'jquery' ), '1.3.1', true );
	}
}

 /* Add CSS files */
add_action( 'wp_enqueue_scripts', 'prefix_add_my_stylesheet' );
add_action('admin_enqueue_scripts', 'prefix_add_my_stylesheet');
add_action('login_enqueue_scripts', 'prefix_add_my_stylesheet');

function prefix_add_my_stylesheet() {
    wp_register_style( 'prefix-style', plugins_url('style.css', __FILE__) );
    wp_enqueue_style( 'prefix-style' );
}

/* Add 4 related products to each product page (default is 2) */
add_filter( 'woocommerce_product_related_posts', 'uncc_4related' );
	function uncc_4related() {
		woocommerce_related_products(4,4); 
	}

 /* Hides the 'Free!' price notice */
add_filter( 'woocommerce_variable_free_price_html',  'hide_free_price_notice' );
add_filter( 'woocommerce_free_price_html',           'hide_free_price_notice' );
add_filter( 'woocommerce_variation_free_price_html', 'hide_free_price_notice' );
function hide_free_price_notice( $price ) {
  return '';
} 

/* Adds the add to cart button on variable products like iPad */
function mv_my_theme_scripts(){
wp_enqueue_script('add-to-cart-variation', plugins_url() . '/woocommerce/assets/js/frontend/add-to-cart-variation.js',array('jquery'),'1.0',true);
}
add_action('wp_enqueue_scripts','mv_my_theme_scripts');


/* Change add to cart text */
add_filter('variable_add_to_cart_text', 'woo_custom_cart_button_text');
add_filter('single_add_to_cart_text', 'woo_custom_cart_button_text'); 
add_filter( 'add_to_cart_text', 'woo_custom_cart_button_text' ); // < 2.1
add_filter( 'woocommerce_product_add_to_cart_text', 'woo_custom_cart_button_text' ); // 2.1 +
add_filter( 'add_to_cart_text', 'woo_custom_cart_button_text' ); // < 2.1
add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_cart_button_text' ); // 2.1 +
function woo_custom_cart_button_text() {
return __( 'Request', 'woocommerce' );
}

/* Change place order text */
add_filter( 'woocommerce_order_button_text', 'woo_custom_place_order_text' );
function woo_custom_place_order_text() {
return __( 'Send Request', 'woocommerce' );
}

/* Change backorder text */
add_filter('woocommerce_get_availability', 'backorder_text');
function backorder_text($availability) {
foreach($availability as $i) {
$availability = str_replace('In stock (backorders allowed)', '', $availability);
}
return $availability;
}

/***************** AVOID THESE WHEN POSSIBLE **********************/
/* Change shopping text */
/* No Woocommerce filters available */
 add_filter('gettext',  'translate_text');
 add_filter('ngettext',  'translate_text');
 function translate_text($translated) {
     $translated = str_ireplace('shopping',  'viewing available items',  $translated);
     return $translated;
 }

/* Change Proceed to Checkout text */
/* No Woocommerce filters available */
add_filter('gettext',  'translate_text2');
add_filter('ngettext',  'translate_text2');
function translate_text2($translated) {
     $translated = str_ireplace('Proceed to Checkout',  'Proceed to Send Request &rarr;',  $translated);
     return $translated;
}

/* Change Available on backorder text */
/* Required for text on product in cart.  The below filter works on product pages */
/* add_filter('woocommerce_get_availability', 'backorder_text'); */
add_filter('gettext',  'translate_text3');
add_filter('ngettext',  'translate_text3');
function translate_text3($translated) {
     $translated = str_ireplace('Available on backorder',  'Currently checked out. However, you may still request this item.',  $translated);
     return $translated;
}

/* Change Order status text */
add_filter('gettext',  'translate_text4');
add_filter('ngettext',  'translate_text4');
function translate_text4($translated) {
     $translated = str_ireplace('Refunded',  'Returned',  $translated);
     return $translated;
}

/* Change cart text */
add_filter('gettext',  'translate_text5');
add_filter('ngettext',  'translate_text5');
function translate_text5($translated) {
     $translated = str_ireplace('cart',  'request',  $translated);
     return $translated;
}

/* Change shop text */
add_filter('gettext',  'translate_text6');
add_filter('ngettext',  'translate_text6');
function translate_text6($translated) {
     $translated = str_ireplace('shop',  'items',  $translated);
     return $translated;
}

/***************** END AVOID THESE WHEN POSSIBLE **********************/

add_filter( 'woocommerce_add_to_cart_message', 'woocommrece_custom_add_to_cart_message' );
function woocommrece_custom_add_to_cart_message() {
     global $woocommerce;
     if ( get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes' ) {
         $return_to = get_permalink( woocommerce_get_page_id( 'shop' ) ); // Give the url, you want to redirect
        $message   = sprintf( '<a href="%s" class="button">%s</a> %s', $return_to, __( 'More Donation Options &rarr;', 'woocommerce' ), __( 'Donation successfully added to your cart.', 'woocommerce' ) );
    } else {
         $message = sprintf( '<a href="%s">%s</a> %s', get_permalink( woocommerce_get_page_id( 'cart' ) ), __( 'View Cart &rarr;', 'woocommerce' ), __( 'Donation successfully added to your cart.', 'woocommerce' ) );
     }
     return $message;
}

/* Removes fields from checkout */
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
function custom_override_checkout_fields( $fields ) {
		unset($fields['billing']['billing_company']);
		unset($fields['billing']['billing_address_2']);
		unset($fields['billing']['billing_city']);
		unset($fields['billing']['billing_postcode']);
		//unset($fields['billing']['billing_country']); Required for checkout.  Hiding with CSS
		unset($fields['billing']['billing_state']);
		$fields['billing']['billing_address_1']['label'] = 'Office Location';
		$fields['billing']['billing_address_1']['placeholder'] = 'Building and Room #';
		$fields['order']['order_comments']['placeholder'] = 'Notes about your request, e.g. How long you need the item.';
		$fields['order']['order_comments']['label'] = 'Notes';
     return $fields;
}

/* Remove product count from category */
add_filter( 'woocommerce_subcategory_count_html', 'woo_remove_category_products_count' );
function woo_remove_category_products_count() {
	 return;
}

/* Remove returning login from checkout */
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );

/* Datepicker on checkout */
add_action('woocommerce_after_checkout_billing_form', 'my_custom_checkout_field2'); 
function my_custom_checkout_field2( $checkout ) {	
    wp_enqueue_script( 'jquery-ui-datepicker' );
    wp_enqueue_style( 'jquery-ui', "http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/smoothness/jquery-ui.css" , '', '', false);
    wp_enqueue_style( 'datepicker', plugins_url('/css/datepicker.css', __FILE__) , '', '', false);
	echo '<script language="javascript">jQuery(document).ready(function(){
	jQuery("#e_deliverydate").width("150px");
	var formats = ["MM d, yy","MM d, yy"];
	jQuery("#e_deliverydate").val("").datepicker({dateFormat: formats[1], minDate:1});
	jQuery("#e_returndate").width("150px");
	var formats = ["MM d, yy","MM d, yy"];
	jQuery("#e_returndate").val("").datepicker({dateFormat: formats[1], minDate:1});
});</script>';
	echo '<div id="my_custom_checkout_field" style="float:left;';     
	woocommerce_form_field( 'e_deliverydate', array(        
				'type'          => 'text',        
				'label'         => __('Delivery/Pickup Date'),		
				'required'  	=> true,		       
				), 
				$checkout->get_value( 'e_deliverydate' ));     
				echo '</div>'; 
	echo '<div id="my_custom_checkout_field2" style="float:right;';     
	woocommerce_form_field( 'e_returndate', array(        
				'type'          => 'text',        
				'label'         => __('Estimated Return Date'),		
				'required'  	=> true,		       
				), 
				$checkout->get_value( 'e_returndate' ));     
				echo '</div>'; 
}
add_action('woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta2'); 
function my_custom_checkout_field_update_order_meta2( $order_id ) {    
	if ($_POST['e_deliverydate']) {
		update_post_meta( $order_id, 'Delivery Date', esc_attr($_POST['e_deliverydate']));
	}
	if ($_POST['e_returndate']) {
		update_post_meta( $order_id, 'Return Date', esc_attr($_POST['e_returndate']));
	}
}


//Email templates in plugin
add_filter( 'woocommerce_locate_template', 'myplugin_woocommerce_locate_template', 10, 3 );
 
function myplugin_woocommerce_locate_template( $template, $template_name, $template_path ) {
   global $woocommerce;
   $_template = $template;
   if ( ! $template_path ) $template_path = $woocommerce->template_url;
   $plugin_path  = plugin_dir_path( __FILE__ );
  
  // Look within passed path within the theme - this is priority
   $template = locate_template(
     array(
       $template_path . $template_name,
       $template_name
     )
   );
 
  // Modification: Get the template from this plugin, if it exists
   if ( ! $template && file_exists( $plugin_path . $template_name ) )
     $template = $plugin_path . $template_name;
   // Use default template
   if ( ! $template )
     $template = $_template;
   // Return what we found
   return $template;
 }

 
//Use custom templates from the plugin
add_filter( 'template_include', 'rc_tc_template_chooser',99 );

function rc_tc_template_chooser( $template ) {
 echo($template);
    $template_file = basename($template);
	if(file_exists(plugin_dir_path( __FILE__ ). 'woocommerce/'.$template_file)){
        $template = plugin_dir_path( __FILE__ ). 'woocommerce/'.$template_file;
    }
    return $template;
}

//Dismisses WooCommerce warning about theme support
add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}
?>
