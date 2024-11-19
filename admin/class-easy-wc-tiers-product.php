<?php

/**
 * The WooCommerce product functionality of the plugin.
 *
 * @link       https://lonemill.com
 * @since      1.0.0
 *
 * @package    Easy_Wc_Tiers
 * @subpackage Easy_Wc_Tiers/admin
 */

/**
 * The WooCommerce product functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the WooCommerce product stylesheet and JavaScript.
 *
 * @package    Easy_Wc_Tiers
 * @subpackage Easy_Wc_Tiers/admin
 * @author     Cory Pratt <cory@lonemill.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Product extension to integrate with Groups.
 */
class Easy_WC_Product {

	/**
	 * Register own Groups tab and handle group association with products.
	 * Register price display modifier.
	 */
	public static function init() {
		if ( is_admin() ) {
			add_action( 'woocommerce_product_data_tabs', array( __CLASS__, 'product_write_panel_tabs' ) );
			add_action( 'woocommerce_product_data_panels', array( __CLASS__, 'product_data_panels' ) );

			// add_action( 'woocommerce_process_product_meta', array( __CLASS__, 'process_product_meta' ), 10, 2 );
			// add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_enqueue_scripts' ) );
			// add_action( 'woocommerce_product_after_variable_attributes', array( __CLASS__, 'woocommerce_product_after_variable_attributes'), 10, 3 );
			// add_action( 'woocommerce_save_product_variation', array( __CLASS__, 'woocommerce_save_product_variation' ), 10, 2 );
		}
		// add_filter( 'woocommerce_get_price_html', array( __CLASS__, 'woocommerce_get_price_html' ), 10, 2 );
	}


	/**
	 * Groups tab title.
	 */
	public static function product_write_panel_tabs( $tabs ) {

		$tabs['ewt-product-tiers'] = array(
			'label'    => 'Pricing Tiers',
			'target'   => 'ewt_product_tiers',
			'class'    => array(),
			'priority' => 65,
		);
		return $tabs;
	}

	/**
	 * Groups tab content.
	 */
	public static function product_data_panels() {
		global $woocommerce, $post;
		$product = wc_get_product( $post->ID );

		?>
		<div id="ewt_product_tiers" class="panel woocommerce_options_panel hidden wc-metaboxes-wrapper">
			<?php if ( ! $product->is_type( 'simple' ) ) : ?>
			<div class="toolbar toolbar-top">
				<div id="message" class="inline notice notice-error woocommerce-message">
					<p class="help">Tiered pricing only works on simple products right now!</p>
				</div>
			</div>
				<?php
			else :
				require_once plugin_dir_path( __DIR__ ) . '/admin/partials/html-easy-wc-tiers-product-tiers.php';
			endif;
			?>
		</div>
		<?php
	}

	/**
	 * Get existing product tiers.
	 */
	public static function get_product_tiers() {
		global $woocommerce, $post;
		$tiers = get_post_meta( $post->ID, 'ewt_product_tiers', true );
		// var_dump( $tiers );
		return $tiers;
	}
}

Easy_WC_Product::init();
