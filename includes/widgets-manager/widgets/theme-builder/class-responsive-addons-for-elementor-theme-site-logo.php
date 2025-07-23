<?php
/**
 * Site Logo widget.
 *
 * @package Responsive_Addons_For_Elementor
 *
 * @since 1.4.0
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets\ThemeBuilder;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Widget_Image;
use Elementor\Plugin;
use Elementor\Utils;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * RAEL Theme Site Logo widget class
 */
class Responsive_Addons_For_Elementor_Theme_Site_Logo extends Widget_Image {

	/**
	 * Retrieve the widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-theme-site-logo';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Site Logo', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-site-logo rael-badge';
	}

	/**
	 * Get Categories
	 *
	 * Returns an array of tag categories
	 *
	 * @access public
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve widget keywords.
	 *
	 * @access public
	 *
	 * @return string Widget keywords.
	 */
	public function get_keywords() {
		return array( 'rael', 'site', 'logo', 'branding' );
	}

	/**
	 * Register controls for Site logo widget
	 */
	protected function register_controls() {
		parent::register_controls();
		
		   // Get site logo ID
        $logo_id = get_theme_mod('custom_logo');
        $logo_url = $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : '';
		
		$this->update_control(
			'section_image',
			[
				'label' => esc_html__( 'Site Logo', 'responsive-addons-for-elementor' ),
			]
		);
		$this->update_control(
			'image',
			[
				'label' => esc_html__( 'Site Logo', 'responsive-addons-for-elementor' ),
				'default' => [
					'id'  => $logo_id,
					'url' => $logo_url,
				],
			]
		);


		$this->remove_control( 'image_size' );

		$this->update_control(
			'link_to',
			array(
				'default' => 'custom',
			)
		);

		$this->update_control(
			'link',
			array(
				'dynamic' => array(
					'default' => Plugin::instance()->dynamic_tags->tag_data_to_tag_text( null, 'rael-site-url' ),
				),
			),
			array(
				'recursive' => true,
			)
		);

		$this->update_control(
			'caption_source',
			array(
				'options' => $this->get_caption_source_options(),
			)
		);

		$this->remove_control( 'caption' );
	}

	/**
	 * Check if the current widget has caption
	 *
	 * @access private
	 * @since 1.6.0
	 *
	 * @param array $settings settings array.
	 *
	 * @return boolean
	 */
	private function has_caption( $settings ) {
		return ( ! empty( $settings['caption_source'] ) && 'none' !== $settings['caption_source'] );
	}

	/**
	 * Retrieve image widget link URL.
	 *
	 * @since 1.6.0
	 * @access private
	 *
	 * @param array $settings settings array.
	 *
	 * @return array|string|false An array/string containing the link URL, or false if no link.
	 */
	protected function get_link_url( $settings ) {
		if ( 'none' === $settings['link_to'] ) {
			return false;
		}

		if ( 'custom' === $settings['link_to'] ) {
			if ( empty( $settings['link']['url'] ) ) {
				return false;
			}

			return $settings['link'];
		}

		return array(
			'url' => $settings['image']['url'],
		);
	}

	/**
	 * Get the caption for current widget.
	 *
	 * @access private
	 * @since 1.6.0
	 * @param object|array $settings Widget settings.
	 *
	 * @return string
	 */
	private function get_caption( $settings ) {
		$caption = '';
		if ( ! empty( $settings['caption_source'] ) ) {
			switch ( $settings['caption_source'] ) {
				case 'attachment':
					$caption = wp_get_attachment_caption( $settings['image']['id'] );
					break;
				case 'custom':
					$caption = ! Utils::is_empty( $settings['caption'] ) ? $settings['caption'] : '';
			}
		}
		return $caption;
	}


	/**
	 * Render image widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 * This method overrides the parent render method.
	 *
	 * @since 1.6.0
	 * @access protected
	 */
	protected function render() {
    $settings = $this->get_settings_for_display();

	$logo_url = '';
if ( ! empty( $settings['image']['url'] ) ) {
    $logo_url = $settings['image']['url'];
} else {
    $logo_id = get_theme_mod('custom_logo');
    $logo_url = $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : Utils::get_placeholder_image_src();
}
if ( $logo_url ) {
    echo '<img src="' . esc_url( $logo_url ) . '" alt="Site Logo">';
}
    if ( ! Plugin::$instance->experiments->is_feature_active( 'e_dom_optimization' ) ) {
        $this->add_render_attribute( 'wrapper', 'class', 'elementor-image' );
    }

    $has_caption = $this->has_caption( $settings );
    $link        = $this->get_link_url( $settings );
    $image_class = ! empty( $settings['hover_animation'] ) ? 'elementor-animation-' . $settings['hover_animation'] : '';

    if ( $link ) {
        $this->add_link_attributes( 'link', $link );

        if ( Plugin::$instance->editor->is_edit_mode() ) {
            $this->add_render_attribute( 'link', [ 'class' => 'elementor-clickable' ] );
        }

        if ( 'custom' !== $settings['link_to'] ) {
            $this->add_lightbox_data_attributes( 'link', $settings['site_logo_image']['id'], $settings['open_lightbox'] );
        }
    }

    if ( ! Plugin::$instance->experiments->is_feature_active( 'e_dom_optimization' ) ) {
        echo '<div ' . $this->get_render_attribute_string( 'wrapper' ) . '>';
    }

    if ( $has_caption ) {
        echo '<figure class="wp-caption">';
    }

    if ( $link ) {
        echo '<a ' . $this->get_render_attribute_string( 'link' ) . '>';
    }

    echo '<img src="' . esc_url( $settings['site_logo_image']['url'] ) . '" class="' . esc_attr( $image_class ) . '" />';

    if ( $link ) {
        echo '</a>';
    }

    if ( $has_caption ) {
        echo '<figcaption class="widget-image-caption wp-caption-text">' . wp_kses_post( $this->get_caption( $settings ) ) . '</figcaption>';
        echo '</figure>';
    }

    if ( ! Plugin::$instance->experiments->is_feature_active( 'e_dom_optimization' ) ) {
        echo '</div>';
    }
}


	/**
	 * Render image widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.6.0
	 * @access protected
	 */
	protected function content_template() {
		?>
		<# if ( settings.image.url ) {
			var image = {
				id: settings.image.id,
				url: settings.image.url,
				size: settings.image_size,
				dimension: settings.image_custom_dimension,
				model: view.getEditModel()
			};

			var image_url = elementor.imagesManager.getImageUrl( image );

			if ( ! image_url ) {
				return;
			}

			var hasCaption = function() {
				if( ! settings.caption_source || 'none' === settings.caption_source ) {
					return false;
				}
				return true;
			}

			var ensureAttachmentData = function( id ) {
				if ( 'undefined' === typeof wp.media.attachment( id ).get( 'caption' ) ) {
					wp.media.attachment( id ).fetch().then( function( data ) {
						view.render();
					} );
				}
			}

			var getAttachmentCaption = function( id ) {
				if ( ! id ) {
					return '';
				}
				ensureAttachmentData( id );
				return wp.media.attachment( id ).get( 'caption' );
			}

			var getCaption = function() {
				if ( ! hasCaption() ) {
					return '';
				}
				return 'custom' === settings.caption_source ? settings.caption : getAttachmentCaption( settings.image.id );
			}

			var link_url;

			if ( 'custom' === settings.link_to ) {
				link_url = settings.link.url;
			}

			if ( 'file' === settings.link_to ) {
				link_url = settings.image.url;
			}

			<?php if ( ! Plugin::$instance->experiments->is_feature_active( 'e_dom_optimization' ) ) { ?>
				#><div class="elementor-image{{ settings.shape ? ' elementor-image-shape-' + settings.shape : '' }}"><#
			<?php } ?>

			var imgClass = '';

			if ( '' !== settings.hover_animation ) {
				imgClass = 'elementor-animation-' + settings.hover_animation;
			}

			if ( hasCaption() ) {
				#><figure class="wp-caption"><#
			}	

			if ( link_url ) {
					#><a class="elementor-clickable" data-elementor-open-lightbox="{{ settings.open_lightbox }}" href="{{ link_url }}"><#
			}
						#><img src="{{ image_url }}" class="{{ imgClass }}" /><#

			if ( link_url ) {
					#></a><#
			}

			if ( hasCaption() ) {
					#><figcaption class="widget-image-caption wp-caption-text">{{{ getCaption() }}}</figcaption><#
			}

			if ( hasCaption() ) {
				#></figure><#
			}

			<?php if ( ! Plugin::$instance->experiments->is_feature_active( 'e_dom_optimization' ) ) { ?>
				#></div><#
			<?php } ?>

		} #>
		<?php
	}

	/**
	 * Retrieves the HTML wrapper class for the post featured image elementor widget.
	 *
	 * @return string The HTML wrapper class.
	 */
	protected function get_html_wrapper_class() {
		return parent::get_html_wrapper_class() . ' elementor-widget-' . parent::get_name();
	}

	/**
	 * Retrieves the options available for the caption source control.
	 *
	 * This method retrieves the options available for the "caption_source" control
	 * and removes the "custom" option from the options array.
	 *
	 * @return array The modified options array for the caption source control.
	 */
	private function get_caption_source_options() {
		$caption_source_controls = $this->get_controls( 'caption_source' );
		$caption_source_options  = isset( $caption_source_controls['options'] ) ? $caption_source_controls['options'] : array();

		unset( $caption_source_options['custom'] );

		return $caption_source_options;
	}
}
