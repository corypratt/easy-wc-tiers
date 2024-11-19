<?php
/**
 * Product attribute table for reuse.
 *
 * @package WooCommerce\Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="options_group">
	<p class="form-field">
		<label><?php esc_html_e( 'Min Qty', 'woocommerce' ); ?>:</label>
		<input type="number" class="attribute_name" name="min_qty[<?php echo esc_attr( $i ); ?>]" value="<?php echo esc_attr( $tier->get_min() ); ?>" placeholder="<?php esc_attr_e( 'f.e. 1', 'woocommerce' ); ?>" />
		<input type="hidden" name="tier_position[<?php echo esc_attr( $i ); ?>]" class="attribute_position" value="<?php echo esc_attr( $tier->get_position() ); ?>" />
	</p>
</div>
<div class="options_group">
	<p class="form-field">
		<label><?php esc_html_e( 'Max Qty', 'woocommerce' ); ?>:</label>
		<input type="number" class="attribute_name" name="max_qty[<?php echo esc_attr( $i ); ?>]" value="<?php echo esc_attr( $tier->get_max() ); ?>" placeholder="<?php esc_attr_e( 'f.e. 5', 'woocommerce' ); ?>" />
	</p>
</div>
<div class="options_group">
	<p class="form-field">
		<label><?php esc_html_e( 'Discount Type', 'woocommerce' ); ?>:</label>
		<select id="ewt_discount_type" name="ewt_discount_type[<?php echo esc_attr( $i ); ?>]" class="select short">
			<option value="discount"<?php echo $tier->get_discount_type() === 'discount' ? ' selected="selected"' : ''; ?>>$ Discount</option>
			<option value="percentage"<?php echo $tier->get_discount_type() === 'percentage' ? ' selected="selected"' : ''; ?>>Percentage</option>
		</select>
	</p>
</div>
<div class="options_group">
	<p class="form-field">
		<label><?php esc_html_e( 'Discount', 'woocommerce' ); ?>:</label>
		<input type="number" class="attribute_name" name="discount[<?php echo esc_attr( $i ); ?>]" value="<?php echo esc_attr( $tier->get_discount() ); ?>" placeholder="<?php esc_attr_e( 'f.e. 5', 'woocommerce' ); ?>" />
	</p>
</div>
