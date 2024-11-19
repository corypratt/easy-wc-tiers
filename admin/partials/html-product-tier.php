<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="woocommerce_tier wc-metabox postbox closed <?php //echo esc_attr( implode( ' ', $metabox_class ) ); ?>" rel="<?php echo esc_attr( $tier->get_position() ); ?>">
	<h3>
		<div class="handlediv" title="<?php esc_attr_e( 'Click to toggle', 'woocommerce' ); ?>"></div>
		<div class="tips sort" data-tip="<?php esc_attr_e( 'Drag and drop to set admin attribute order', 'woocommerce' ); ?>"></div>
		<a href="#" class="remove_row delete"><?php esc_html_e( 'Remove', 'woocommerce' ); ?></a>
		<strong class="attribute_name"><?php echo esc_html( __( 'Price Tier', 'woocommerce' ) ); ?></strong>
	</h3>
	<div class="woocommerce_attribute_data wc-metabox-content hidden">
		<?php require __DIR__ . '/html-product-tier-inner.php'; ?>
	</div>
</div>
