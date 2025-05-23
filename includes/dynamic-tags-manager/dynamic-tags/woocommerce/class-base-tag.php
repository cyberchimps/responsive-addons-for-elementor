<?php
namespace Responsive_Addons_For_Elementor\DynamicTagsManager\DynamicTags\Woocommerce;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module;
use Responsive_Addons_For_Elementor\DynamicTagsManager\DynamicTags\Woocommerce\Traits\Tag_Product_Id;

abstract class Base_Tag extends Tag {

	use Tag_Product_Id;

	public function get_group() {
		return 'rael-woocommerce-group';
	}

	public function get_categories() {
		return array( Module::TEXT_CATEGORY );
	}
}
