<?php
namespace Responsive_Addons_For_Elementor\DynamicTagsManager\DynamicTags\WooCommerce\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Responsive_Addons_For_Elementor\WidgetsManager\Modules\QueryControl\Module as QueryControlModule;

trait Tag_Product_Id {
	public function add_product_id_control() {
		$this->add_control(
			'product_id',
			array(
				'label'        => esc_html__( 'Product', 'responsive-addons-for-elementor' ),
				'type'         => QueryControlModule::QUERY_CONTROL_ID,
				'options'      => array(),
				'label_block'  => true,
				'autocomplete' => array(
					'object' => QueryControlModule::QUERY_OBJECT_POST,
					'query'  => array(
						'post_type' => array( 'product' ),
					),
				),
				// Since we're using the `wc_get_product` method to retrieve products, when no product selected manually
				// by the dynamic tag - the default should be `false` so the method will use the product id given in the
				// http request instead.
				'default'      => false,
			)
		);
	}
}
