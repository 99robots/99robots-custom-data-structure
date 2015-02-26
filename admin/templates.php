<div class="wrap gabfire-plugin-settings">

<?php require_once('header.php'); ?>

<div class="metabox-holder has-right-sidebar">

<?php require_once('sidebar.php'); ?>

<div id="post-body">
<div id="post-body-content">

<!-- Insert Content HERE -->

<script type="text/javascript">jQuery(document).ready(function($){<?php

if (!is_plugin_active('gabfire-custom-post-types/gabfire_custom_post.php')) {
	?>$('.<?php echo self::$prefix_dash; ?>title').after('<div class="error"><p><?php _e("Gabfire Custom Post Types plugin is not activated.", self::$text_domain); ?></p></div>');<?php
}

if (!is_plugin_active('gabfire-custom-taxonomies/gabfire_taxonomies.php')) {
	?>$('.<?php echo self::$prefix_dash; ?>title').after('<div class="error"><p><?php _e("Gabfire Custom Taxonomies plugin is not activated.", self::$text_domain); ?></p></div>');<?php
}

if (!is_plugin_active('gabfire-custom-fields/gabfire_custom_field.php')) {
	?>$('.<?php echo self::$prefix_dash; ?>title').after('<div class="error"><p><?php _e("Gabfire Custom Fields plugin is not activated.", self::$text_domain); ?></p></div>');<?php
}

if (!is_plugin_active('gabfire-custom-post-status/gabfire-custom-post-status.php')) {
	?>$('.<?php echo self::$prefix_dash; ?>title').after('<div class="error"><p><?php _e("Gabfire Custom Post Status plugin is not activated.", self::$text_domain); ?></p></div>');<?php
}

if (!is_plugin_active('gabfire-custom-content-query/gabfire-custom-content-query.php')) {
	?>$('.<?php echo self::$prefix_dash; ?>title').after('<div class="error"><p><?php _e("Gabfire Custom Content Query plugin is not activated.", self::$text_domain); ?></p></div>');<?php
}

?>});</script>

<h1 class="<?php echo self::$prefix_dash; ?>title"><?php _e('Templates', self::$text_domain); ?></h1>

<table class="wp-list-table widefat fixed posts">
	<thead>
		<tr>
			<th><?php _e('Name', self::$text_domain); ?></th>
			<th><?php _e('Post Types', self::$text_domain); ?></th>
			<th><?php _e('Taxonomies', self::$text_domain); ?></th>
			<th><?php _e('Custom Fields', self::$text_domain); ?></th>
			<th><?php _e('Post Status', self::$text_domain); ?></th>
			<th><?php _e('Queries', self::$text_domain); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th><?php _e('Name', self::$text_domain); ?></th>
			<th><?php _e('Post Types', self::$text_domain); ?></th>
			<th><?php _e('Taxonomies', self::$text_domain); ?></th>
			<th><?php _e('Custom Fields', self::$text_domain); ?></th>
			<th><?php _e('Post Status', self::$text_domain); ?></th>
			<th><?php _e('Queries', self::$text_domain); ?></th>
		</tr>
	</tfoot>

	<?php $count = 0; foreach (self::getTemplates() as $key => $template) {

		$custom_post_types = array();
		$custom_taxonomies = array();
		$custom_fields = array();
		$custom_post_status = array();
		$custom_content_queries = array();

		foreach ($template as $item) {

			// Post Types

			if (isset($item['type']) && $item['type'] == 'post_type') {
				$custom_post_types[] = $item['post_type'];
			}

			// Taxonomies

			if (isset($item['type']) && $item['type'] == 'taxonomy') {
				$custom_taxonomies[] = $item['taxonomy'];
			}

			// Custom Fields

			if (isset($item['type']) && $item['type'] == 'custom_field') {
				foreach($item['fields'] as $field) {
					$custom_fields[] = $field['id'];
				}
			}

			// Post Status

			if (isset($item['type']) && $item['type'] == 'post_status') {
				$custom_post_status[] = $item['status'];
			}

			// Queries

			if (isset($item['type']) && $item['type'] == 'query') {
				$custom_content_queries[] = $item['query'];
			}
		}
		?>

		<tr <?php if ($count%2) { echo 'class="alternate"'; } ?>>

			<!-- Name -->

			<td>
				<p><?php echo isset($key) ? $key : ''; ?></p>

				<a style="padding: 5px !important; top: 0px !important;" class="add-new-h2" href="<?php echo wp_nonce_url($_SERVER['PHP_SELF'] . '?page=' . self::$templates_page . '&template=' . $key . '&action=install', self::$prefix . 'install'); ?>"><?php echo __('Install', self::$text_domain); ?></a>

				<!--
<div class="row-actions">
					<span class="edit">
						<a href="<?php echo wp_nonce_url($_SERVER['PHP_SELF'] . '?page=' . self::$templates_page . '&template=' . $key . '&action=install', self::$prefix . 'install'); ?>"><?php echo __('Install', self::$text_domain); ?></a>
					</span>
				</div>
-->
			</td>

			<!-- Post Type -->

			<td>
				<span><?php
					foreach ($custom_post_types as $custom_post_type) {
						 echo __($custom_post_type, self::$text_domain) . '</br>';
					}
				?></span>
			</td>

			<!-- Taxonomy -->

			<td>
				<span><?php
					foreach ($custom_taxonomies as $custom_taxonomy) {
						 echo __($custom_taxonomy, self::$text_domain) . '</br>';
					}
				?></span>
			</td>

			<!-- Custom Field -->

			<td>
				<span><?php
					foreach ($custom_fields as $custom_field) {
						 echo __($custom_field, self::$text_domain) . '</br>';
					}
				?></span>
			</td>

			<!-- Post Status -->

			<td>
				<span><?php
					foreach ($custom_post_status as $custom_post_status_item) {
						 echo __($custom_post_status_item, self::$text_domain) . '</br>';
					}
				?></span>
			</td>

			<!-- Queries -->

			<td>
				<span><?php
					foreach ($custom_content_queries as $custom_content_query) {
						 echo __($custom_content_query, self::$text_domain) . '</br>';
					}
				?></span>
			</td>

		</tr>

	<?php
		$count++;
	} ?>

	</tbody>
</table>

<!-- END Content -->

<?php require_once('footer.php'); ?>