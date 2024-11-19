<?php
/**
 * Displays the tiers tab in the product data meta box.
 *
 * @package Easy_Wc_Tiers/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// global $wc_product_attributes;
// Array of defined attribute taxonomies.
// $attribute_taxonomies = wc_get_attribute_taxonomies();
// Product attributes - taxonomies and custom, ordered, with visibility and variation attributes set.
// $product_attributes = $product_object->get_attributes( 'edit' );
$product_tiers = Easy_WC_Product::get_product_tiers();
?>
<div class="toolbar toolbar-top">
	<div id="message" class="inline notice woocommerce-message">
		<p class="help">
			<?php
			esc_html_e(
				'Add tiered pricing for a product',
				'woocommerce'
			);
			?>
		</p>
	</div>
	<span class="expand-close">
		<a href="#" class="expand_all"><?php esc_html_e( 'Expand', 'woocommerce' ); ?></a> / <a href="#" class="close_all"><?php esc_html_e( 'Close', 'woocommerce' ); ?></a>
	</span>
	<div class="actions">
		<button type="button" class="button add_custom_tier"><?php esc_html_e( 'Add new tier', 'woocommerce' ); ?></button>
	</div>
</div>
<div class="product_tiers wc-metaboxes ui-sortable">
	<?php
	// var_dump( $product_tiers );
	if ( $product_tiers ) {
		// $i = 0;
		// var_dump($product_tiers['tier_position']);
		foreach ( $product_tiers['tier_position'] as $tier_position ) {
			$i    = $tier_position;
			$tier = new WC_Product_Tier();
			$tier->set_position( $tier_position );
			$tier->set_min( $product_tiers['min_qty'][ $tier_position ] );
			$tier->set_max( $product_tiers['max_qty'][ $tier_position ] );
			$tier->set_discount( $product_tiers['discount'][ $tier_position ] );
			$tier->set_discount_type( $product_tiers['ewt_discount_type'][ $tier_position ] );

			// var_dump( $tier );
			include __DIR__ . '/html-product-tier.php';
		}
	}
	?>
</div>
<div class="toolbar toolbar-buttons">
	<span class="expand-close">
		<a href="#" class="expand_all"><?php esc_html_e( 'Expand', 'woocommerce' ); ?></a> / <a href="#" class="close_all"><?php esc_html_e( 'Close', 'woocommerce' ); ?></a>
	</span>
	<button type="button" aria-disabled="true" class="button save_tiers button-primary disabled"><?php esc_html_e( 'Save attributes', 'woocommerce' ); ?></button>
</div>
