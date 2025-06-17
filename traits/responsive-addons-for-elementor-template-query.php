<?php
/**
 * Trait for template queries
 *
 * @package responsive-addons-for-elementor
 * @since 1.0.0
 */

namespace Responsive_Addons_For_Elementor\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

trait RAEL_Template_Query {


	/**
	 * The current widget's name
	 *
	 * @var $current_widget_name
	 */
	public $current_widget_name = '';

	/**
	 * Set widget name
	 *
	 * @param string $name Widget name.
	 */
	public function set_widget_name( $name = '' ) {
		$this->current_widget_name = $name;
	}

	/**
	 * Get only filename
	 *
	 * @param   string $path Path of file.
	 * @return  string
	 */
	public function get_filename_only( $path ) {
		$filename = \explode( '/', $path );
		return \end( $filename );
	}

	/**
	 * Retrieves Template name from file header.
	 *
	 * @var $template_headers
	 */
	private $template_headers = array(
		'Template Name' => 'Template Name',
	);

	/**
	 * Prepare the directory name from the following widget name.
	 *
	 * @access private
	 *
	 * @return  string  $widget_name
	 */
	private function process_directory_name() {
		if ( empty( $this->current_widget_name ) && \method_exists( $this, 'get_name' ) ) {
			$this->current_widget_name = $this->get_name();
		}
		$widget_name = str_replace( 'rael-', '', $this->current_widget_name );
		$widget_name = str_replace( '-', ' ', $widget_name );
		$widget_name = ucwords( $widget_name );
		$widget_name = str_replace( ' ', '-', $widget_name );

		return $widget_name;
	}

	/**
	 * Retrieve `Theme Template Directory`
	 *
	 * @return string templates directory from the active theme.
	 */
	private function theme_templates_dir() {
		$current_theme = wp_get_theme();

		$dir = sprintf(
			'%s/%s/Template/%s',
			$current_theme->theme_root,
			$current_theme->stylesheet,
			$this->process_directory_name()
		);

		if ( is_dir( $dir ) ) {
			$file = scandir( $dir );
			$file = array_pop( $file );

			return pathinfo( $file, PATHINFO_EXTENSION ) === 'php' ? $dir : false;
		}

		return false;
	}

	/**
	 * Retrieves the plugin template directory path.
	 *
	 * @return  string  templates directory path
	 */
	private function get_template_dir() {
		return \sprintf(
			'%sincludes/Template/%s',
			RAEL_DIR,
			$this->process_directory_name()
		);
	}

	/**
	 * Collecting templates from different sources.
	 *
	 * @return array
	 */
	private function get_template_files() {
		$templates = array();

		if ( is_dir( $this->get_template_dir() ) ) {
			$templates['rael'] = scandir( $this->get_template_dir(), 1 );
		}
		if ( $this->theme_templates_dir() ) {
			$templates['theme'] = scandir( $this->theme_templates_dir(), 1 );
		}
		return $templates;
	}

	/**
	 * Retrieves template list from template directory.
	 *
	 * @return array template list.
	 */
	protected function get_template_list() {
		$files = array();

		if ( $this->get_template_files() ) {
			foreach ( $this->get_template_files() as $key => $handler ) {
				foreach ( $handler as $handle ) {
					if ( strpos( $handle, '.php' ) !== false ) {

						if ( 'rael' === $key ) {
							$path = sprintf( '%s/%s', $this->get_template_dir(), $handle );
						} elseif ( 'theme' === $key ) {
							$path = sprintf( '%s/%s', $this->theme_templates_dir(), $handle );
						}

						$template_info = get_file_data( $path, $this->template_headers );
						$template_name = $template_info['Template Name'];

						if ( $template_name ) {
							$files[ $template_name ] = $path;
						}
					}
				}
			}
		}

		return $files;
	}

	/**
	 *
	 * Retrieves template list from template directory.
	 *
	 * @param false $sort Sorting parameter.
	 * @return array
	 */
	public function get_template_list_for_dropdown( $sort = false ) {
		$files     = array();
		$templates = $this->get_template_files();
		if ( ! empty( $templates ) ) {
			foreach ( $templates as $key => $handler ) {
				foreach ( $handler as $handle ) {
					if ( strpos( $handle, '.php' ) !== false ) {

						$path          = $this->_get_path( $key, $handle );
						$template_info = get_file_data( $path, $this->template_headers );
						$template_name = $template_info['Template Name'];
						$template_key  = str_replace( ' ', '-', strtolower( $template_name ) );
						if ( 'Default' === $template_name ) {
							$files[ $template_key ] = sprintf( '%s (%s)', ucfirst( $template_name ), ucfirst( 'rae' ) );
						} else {
							$files[ $template_key ] = sprintf( '%s (%s)', ucfirst( $template_name ), ucfirst( $key ) );
						}
					}
				}
			}
		}
		if ( $sort ) {
			ksort( $files );
		}
		return $files;
	}

	/**
	 *
	 * Get the path.
	 *
	 * @param mixed $key Path key.
	 * @param mixed $handle Path handle.
	 */
	public function _get_path( $key, $handle ) {
		$path = '';
		if ( 'rael' == $key ) {
			$path = sprintf( '%s/%s', $this->get_template_dir(), $handle );
		} elseif ( 'theme' === $key ) {
			$path = sprintf( '%s/%s', $this->theme_templates_dir(), $handle );
		}
		return $path;
	}

	/**
	 * Preparing template options for frontend select
	 *
	 * @return array teplate select options.
	 */
	private function get_template_options() {
		$files = array();

		if ( $this->get_template_list() ) {
			foreach ( $this->get_template_list() as $filename => $path ) {
				$filename = \str_replace( ' ', '-', $filename );

				$files[ strtolower( $filename ) ] = $path;
			}
		}

		return $files;
	}

	/**
	 * Adding key value pairs in template options.
	 *
	 * @return array
	 */
	private function template_options() {
		$keys   = array_keys( $this->get_template_options() );
		$values = array_keys( $this->get_template_list() );

		return array_combine( $keys, $values );
	}

	/**
	 * Retrieve template
	 *
	 * @param string $filename Name of the file.
	 *
	 * @return string include-able full template path.
	 */
	public function get_template( $filename ) {
		$filename = sanitize_file_name( $filename );
		if ( in_array( $filename, array_keys( $this->get_template_options() ) ) ) {
			$file = $this->get_template_options()[ $filename ];
			return $file;
		}

		return false;
	}

	/**
	 * Set default option in frontend select control.
	 *
	 * @return string first option.
	 */
	public function get_default() {
		$dt = array_reverse( $this->template_options() );

		return strtolower( array_pop( $dt ) );
	}

	/**
	 * Get directory name based on given file name
	 *
	 * @param string $filename Name of the file.
	 * @return int|string
	 */
	protected function get_temp_dir_name( $filename ) {
		if ( empty( $filename ) ) {
			return 'free';
		}
		$template_files = array_reverse( $this->get_template_files() );
		foreach ( $template_files as $key => $handler ) {
			if ( in_array( $filename, $handler ) ) {
				return $key;
			}
		}
		return 'free';
	}
}
