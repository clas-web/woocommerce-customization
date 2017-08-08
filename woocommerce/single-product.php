<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<?php global $vtt_config, $vtt_mobile_support; ?>

<!DOCTYPE html>

<html class="no-js" <?php language_attributes(); ?>>

<head>

	<meta charset="<?php bloginfo('charset'); ?>" />
	<title><?php echo bloginfo('name').' | '.wp_strip_all_tags(vtt_get_page_title()); ?></title>
	
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<link rel="shortcut icon" href="<?php echo vtt_get_theme_file_url('images/favicon.ico', 'all', false); ?>" />
	
	<meta name="viewport" content="user-scalable=no, initial-scale=1, minimum-scale=1, maximum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi">
	
	<link rel="stylesheet" type="text/css" href="<?php echo vtt_get_theme_file_url('styles/normalize.css'); ?>">

	<?php wp_head(); ?>

	<script type="text/javascript">
		var htmlTag = document.getElementsByTagName('html');
		htmlTag[0].className = htmlTag[0].className.replace( 'no-js', '' );
		var is_mobile = <?php echo ($vtt_mobile_support->is_mobile) ? 'true' : 'false'; ?>;
		var use_mobile_site = <?php echo ($vtt_mobile_support->use_mobile_site) ? 'true' : 'false'; ?>;
	</script>

</head>

<?php
	$class = array();
	if( $vtt_mobile_support->use_mobile_site ) $class[] = 'mobile-site'; else $class[] = 'full-site';
?>
<body <?php body_class($class); ?> >

<div id="site-outside-wrapper" class="clearfix">
<div id="site-inside-wrapper" class="clearfix">

	<?php vtt_get_template_part( 'header', 'part', vtt_get_queried_object_type() );	?>
	
<div id="main-wrapper" clas="clearfix">
	<div id="main">

	<?php
		/**
		 * woocommerce_before_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );
	?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php wc_get_template_part( 'content', 'single-product' ); ?>

		<?php endwhile; // end of the loop. ?>

	<?php
		/**
		 * woocommerce_after_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	?>

	<?php
		/**
		 * woocommerce_sidebar hook.
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		//do_action( 'woocommerce_sidebar' );
	?>
	
	</div><!-- #main -->
</div><!-- #main-wrapper -->

<?php vtt_get_template_part( 'footer', 'part', vtt_get_queried_object_type() );	?>
	

</div> <!-- #site-inside-wrapper -->
</div> <!-- #site-outside-wrapper -->
<?php wp_footer(); ?>


</body>

</html>


/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
