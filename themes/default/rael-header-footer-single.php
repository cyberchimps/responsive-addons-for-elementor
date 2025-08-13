<?php
/**
 * Single Post/Page File.
 *
 * @package Responsive_Addons_For_Elementor
 */

use Responsive_Addons_For_Elementor\ModulesManager\Theme_Builder\Theme_Builder;


@get_header();

Theme_Builder::get_single_content();

@get_footer();
