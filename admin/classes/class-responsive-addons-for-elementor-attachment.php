<?php
if (! defined('ABSPATH')) {
	exit;
}
/**
 * Add Attachment Data Extra fields
 *
 * @package Responsive_Addons_For_Elementor
 */

if (! class_exists('Responsive_Addons_For_Elementor_Attachment')) {
	/**
	 * Class Responsive_Addons_For_Elementor_Attachment.
	 */
	class Responsive_Addons_For_Elementor_Attachment
	{

		/**
		 * Constructor function that initializes required actions and hooks
		 *
		 * @since 1.5.2
		 */
		public function __construct()
		{
			add_filter('attachment_fields_to_edit', array($this, 'rae_custom_field_attachment_link'), 11, 2);
			add_filter('attachment_fields_to_save', array($this, 'rae_custom_field_attachment_link_save'), 11, 2);


			add_action('import_end', array($this, 'rae_sync_attachment_categories'));

			add_action('add_attachment', 'rae_sync_duplicate_attachment_categories');
		}

		/**
		 * Add Custom Link field to media uploader and categories for the Image Gallery Widget
		 *
		 * @param array  $form_fields fields to include in attachment form.
		 * @param object $post attachment record in database.
		 * @return array $form_fields modified form fields
		 */
		public function rae_custom_field_attachment_link($form_fields, $post)
		{
			$form_fields['rael-custom-link'] = array(
				'label' => sprintf(__('RAE - Custom Link', 'responsive-addons-for-elementor')),
				'input' => 'text',
				'value' => get_post_meta($post->ID, 'rael-custom-link', true),
			);

			$form_fields['rael-categories'] = array(
				'label' => sprintf(__('RAE - Categories (Ex: Cat1, Cat2)', 'responsive-addons-for-elementor')),
				'input' => 'text',
				'value' => get_post_meta($post->ID, 'rael-categories', true),
			);

			return $form_fields;
		}


		/**
		 * Save values of Custom Link field in media uploader for the Image Gallery Widget
		 *
		 * @param array $post the post data for database.
		 * @param array $attachment attachment fields from $_POST form.
		 * @return array $post modified post data.
		 */
		public function rae_custom_field_attachment_link_save($post, $attachment)
		{

			if (isset($attachment['rael-custom-link'])) {
				update_post_meta($post['ID'], 'rael-custom-link', $attachment['rael-custom-link']);
			}

			if (isset($attachment['rael-categories'])) {
				update_post_meta($post['ID'], 'rael-categories', $attachment['rael-categories']);
			}

			return $post;
		}

		public function rae_sync_attachment_categories()
		{

			global $wpdb;

			$attachments = get_posts(array(
				'post_type' => 'attachment',
				'posts_per_page' => -1,
				'post_status' => 'inherit'
			));

			$map = array();

			foreach ($attachments as $att) {

				$file = get_post_meta($att->ID, '_wp_attached_file', true);
				$cat  = get_post_meta($att->ID, 'rael-categories', true);

				if (!$file) continue;

				if (!isset($map[$file]) && $cat) {
					$map[$file] = $cat;
				}
			}

			foreach ($attachments as $att) {

				$file = get_post_meta($att->ID, '_wp_attached_file', true);

				if (!empty($map[$file])) {

					update_post_meta(
						$att->ID,
						'rael-categories',
						$map[$file]
					);
				}
			}
		}
		public function rae_sync_duplicate_attachment_categories($post_id)
		{

			$file = get_post_meta($post_id, '_wp_attached_file', true);

			if (! $file) {
				return;
			}

			// Normalize filename (remove -1, -300x300 etc)
			$filename = preg_replace('/-\d+x\d+(?=\.)|-\d+(?=\.)/', '', basename($file));

			$attachments = get_posts(array(
				'post_type'      => 'attachment',
				'post_status'    => 'inherit',
				'posts_per_page' => -1,
			));

			$category = '';

			foreach ($attachments as $att) {

				$att_file = get_post_meta($att->ID, '_wp_attached_file', true);
				if (! $att_file) continue;

				$att_name = preg_replace('/-\d+x\d+(?=\.)|-\d+(?=\.)/', '', basename($att_file));

				if ($att_name === $filename) {

					$cat = get_post_meta($att->ID, 'rael-categories', true);

					if ($cat) {
						$category = $cat;
						break;
					}
				}
			}

			if ($category) {
				update_post_meta($post_id, 'rael-categories', $category);
			}
		}
	}

	new Responsive_Addons_For_Elementor_Attachment();
}
