<?php
function ferina_meta_boxes() {
    new mbRetailProduct();
    remove_meta_box( 'stylediv', 'retail-product', 'side' );
    remove_meta_box( 'tagsdiv-colour', 'retail-product', 'side' );
    remove_meta_box( 'tagsdiv-size', 'retail-product', 'side' );
}

class mbRetailProduct {
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save' ) );
	}

	public function add_meta_box( $post_type ) {
		$post_types = array('retail-product');
		if ( in_array( $post_type, $post_types )) {
			add_meta_box(
				'ferina_product_details', 
				__( 'Product Details', 'ferina' ), 
				array( $this, 'rmbc_ferina_product_details' ), 
				$post_type, 
				'advanced', 
				'high'
			);
			add_meta_box(
				'ferina_product_images', 
				__( 'Product Images', 'ferina' ), 
				array( $this, 'rmbc_ferina_product_images' ), 
				$post_type, 
				'advanced', 
				'high'
			);
			add_meta_box(
				'ferina_product_stocks', 
				__( 'Product Stocks', 'ferina' ), 
				array( $this, 'rmbc_ferina_product_stocks' ), 
				$post_type, 
				'advanced', 
				'high'
			);
			add_meta_box(
				'ferina_product_specs', 
				__( 'Product Specifications', 'ferina' ), 
				array( $this, 'rmbc_ferina_product_specs' ), 
				$post_type, 
				'advanced', 
				'high'
			);
		}
	}

	public function save( $post_id ) {
		if ( ! isset( $_POST['fpr_metabox_nonce_field'] ) )
			return $post_id;

		$nonce = $_POST['fpr_metabox_nonce_field'];

		if ( ! wp_verify_nonce( $nonce, 'ferina_product_retail_metabox_nonce' ) )
			return $post_id;

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return $post_id;

		if ( 'retail-product' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) )
				return $post_id;				
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) )
				return $post_id;
		}

		$ferina_sku = sanitize_text_field( $_POST['ferina_sku'] );
		$ferina_prod_desc = sanitize_text_field( $_POST['ferina_product_description'] );

		update_post_meta( $post_id, '_ferina_sku_product', $ferina_sku );
		update_post_meta( $post_id, '_ferina_description_product', $ferina_sku );
	}

	public function rmbc_ferina_product_details( $post ) {
		wp_nonce_field( 'ferina_product_retail_meta_box_nonce', 'ferina_product_retail_metabox_nonce' );

		$ferina_sku = get_post_meta( $post->ID, '_ferina_sku_product', true );
		$ferina_prod_desc = get_post_meta( $post->ID, '_ferina_description_product', true );

		echo '<p>';
		echo '<label style="display: inline-block; width: 250px;" for="ferina_sku">';
		_e( 'Product SKU:', 'ferina' );
		echo '</label> ';
		echo '<input type="text" id="ferina_sku" name="ferina_sku"';
		echo ' value="' . esc_attr( $ferina_sku ) . '" size="25" />';
		echo '</p>';

		echo '<p>';
		echo '<label style="display: inline-block; width: 250px;" for="ferina_product_prices">';
		_e( 'Product Prices:', 'ferina' );
		echo '</label> ';
		echo '<input type="text" id="ferina_product_prices" name="ferina_product_prices"';
		echo ' value="' . esc_attr( $ferina_sku ) . '" size="25" />';
		echo '</p>';

		echo '<p>';
		echo '<label style="display: inline-block; width: 250px;" for="ferina_product_style">';
		_e( 'Product Style:', 'ferina' );
		echo '</label> ';
		echo '<select id="ferina_product_style" name="ferina_product_style">';
		echo '<option> -- Select Product Style -- </option>';
		echo '</select>';
		echo '</p>';

		echo '<p>';
		echo '<label style="display: inline-block; width: 250px; vertical-align: top;" for="ferina_product_description">';
		_e( 'Product Details:', 'ferina' );
		echo '</label> ';
		echo '</p>';
		// echo '<textarea id="ferina_product_description" name="ferina_product_description">';
		// echo wpautop( esc_attr( $ferina_prod_desc ) ) . '</textarea>';
		wp_editor($ferina_prod_desc, 'ferina_product_description');
	}

	public function rmbc_ferina_product_specs( $post ) {}

	public function rmbc_ferina_product_stocks( $post ){
		?>
		<div style="text-align: right; padding-bottom: 15px;"><a href="#" id="add-more-stocks" class="button button-primary button-large"><?php _e('Add More Stock', 'ferina'); ?></a></div>
		<table style="border:0;" class="widefat fixed striped" width="100%">
			<thead>
				<tr>
					<th><?php _e('Status', 'ferina'); ?></th>
					<th><?php _e('Date', 'ferina'); ?></th>
					<th><?php _e('Colour', 'ferina'); ?></th>
					<th><?php _e('Size', 'ferina'); ?></th>
					<th><?php _e('Quantity', 'ferina'); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>New Stock</td>
					<td>2015-09-24 12:23:09</td>
					<td><span style="display:block; width:20px; height: 20px; background: #00f;"></span></td>
					<td>32</td>
					<td>12</td>
				</tr>
				<tr>
					<td>New Stock</td>
					<td>2015-09-24 12:23:09</td>
					<td><span style="display:block; width:20px; height: 20px; background: #0ff;"></span></td>
					<td>34</td>
					<td>10</td>
				</tr>
				<tr>
					<td>Stock Sold</td>
					<td>2015-10-24 12:23:09</td>
					<td><span style="display:block; width:20px; height: 20px; background: #00f;"></span></td>
					<td>32</td>
					<td>1</td>
				</tr>
			</tbody>
			<tfoot>
				<tr align="left">
					<th colspan="2"></th>
					<th colspan="2"><?php _e('Total Stocks', 'ferina'); ?></th>
					<th>22</th>
				</tr>
				<tr align="left">
					<th style="border:0;" colspan="2"></th>
					<th colspan="2"><?php _e('Stock Sold', 'ferina'); ?></th>
					<th>1</th>
				</tr>
				<tr align="left">
					<th style="border:0;" colspan="2"></th>
					<th colspan="2"><?php _e('Current Stocks', 'ferina'); ?></th>
					<th>21</th>
				</tr>
			</tfoot>
		</table>
		<?php
	}

	public function rmbc_ferina_product_images($post){
		global $post;
		$upload_link = esc_url( get_upload_iframe_src( 'image', $post->ID ) );
		$fipmeta = get_post_meta( $post->ID, '_ferina_product_images', true );
		$fipattachment = wp_get_attachment_image_src( $fipmeta, 'thumbnail' );
		$html  = '<div style="text-align: right; padding-bottom: 15px;"><a href="'.$upload_link.'" id="add-more-imgs" class="button button-primary button-large">' . __('Add More Images', 'ferina') . '</a></div>';
		$html .= '<ul id="ferina-product-images-ul"></ul>';

		echo $html;
	}
}

function ferina_metaboxes_js($hook) {
	wp_enqueue_style( 'ferina_admin_css', get_template_directory_uri() . '/asset/admin/css/_ferina_style.css' );
    
    if ( 'post-new.php' != $hook ) {
    	return;
    }
    
    wp_enqueue_script( 'ferina_jquery_ui', get_template_directory_uri() . '/asset/admin/js/jquery-ui.min.js' );
    wp_enqueue_script( 'ferina_metaboxes_js', get_template_directory_uri() . '/asset/admin/js/_ferina_main.js' );
}