<?php
/*
Plugin Name: Gabfire Custom Data Structure BETA
plugin URI:
Description:
version: 0.5.0
Author: Gabfire Custom Data Structure BETA
Author URI: http://kylebenkapps.com
License: GPL2
*/

/**
 * Global Definitions
 */

/* Plugin Name */

if (!defined('GABFIRE_CUSTOM_DATA_STRUCTURE_PLUGIN_NAME'))
    define('GABFIRE_CUSTOM_DATA_STRUCTURE_PLUGIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));

/* Plugin directory */

if (!defined('GABFIRE_CUSTOM_DATA_STRUCTURE_PLUGIN_DIR'))
    define('GABFIRE_CUSTOM_DATA_STRUCTURE_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . GABFIRE_CUSTOM_DATA_STRUCTURE_PLUGIN_NAME);

/* Plugin url */

if (!defined('GABFIRE_CUSTOM_DATA_STRUCTURE_PLUGIN_URL'))
    define('GABFIRE_CUSTOM_DATA_STRUCTURE_PLUGIN_URL', WP_PLUGIN_URL . '/' . GABFIRE_CUSTOM_DATA_STRUCTURE_PLUGIN_NAME);

/* Plugin verison */

if (!defined('GABFIRE_CUSTOM_DATA_STRUCTURE_VERSION_NUM'))
    define('GABFIRE_CUSTOM_DATA_STRUCTURE_VERSION_NUM', '1.0.0');


/**
 * Activatation / Deactivation
 */

register_activation_hook( __FILE__, array('GabfireCustomDataStructure', 'register_activation'));

/**
 * Hooks / Filter
 */

add_action('init', array('GabfireCustomDataStructure', 'load_textdomain'));
add_action('admin_menu', array('GabfireCustomDataStructure', 'menu_page'));

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", array('GabfireCustomDataStructure', 'plugin_links'));

/**
 *  GabfireCustomDataStructure main class
 *
 * @since 1.0.0
 * @using Wordpress 3.8
 */

class GabfireCustomDataStructure {

	/**
	 * text_domain
	 *
	 * (default value: 'gabfire-custom-data-structure')
	 *
	 * @var string
	 * @access private
	 * @static
	 */
	private static $text_domain = 'gabfire-custom-data-structure';

	/**
	 * prefix
	 *
	 * (default value: 'gab_cus_data_struct_')
	 *
	 * @var string
	 * @access private
	 * @static
	 */
	private static $prefix = 'gab_cus_data_struct_';

	/**
	 * prefix
	 *
	 * (default value: 'gab_cus_data_struct_')
	 *
	 * @var string
	 * @access private
	 * @static
	 */
	private static $prefix_dash = 'gab-cus-data-struct-';

	/**
	 * settings_page
	 *
	 * (default value: 'gabfire-custom-data-structure-admin-menu-settings')
	 *
	 * @var string
	 * @access private
	 * @static
	 */
	private static $templates_page = 'gabfire-custom-data-structure-templates';

	/**
	 * tabs_settings_page
	 *
	 * (default value: 'gabfire-custom-data-structure-admin-menu-tab-settings')
	 *
	 * @var string
	 * @access private
	 * @static
	 */
	private static $tabs_settings_page = 'gabfire-custom-data-structure-admin-menu-tab-settings';

	/**
	 * usage_page
	 *
	 * (default value: 'gabfire-custom-data-structure-admin-menu-usage')
	 *
	 * @var string
	 * @access private
	 * @static
	 */
	private static $usage_page = 'gabfire-custom-data-structure-admin-menu-usage';

	/**
	 * transient_time
	 *
	 * (default value: 2)
	 *
	 * @var int
	 * @access private
	 * @static
	 */
	private static $transient_time = 1;

	/**
	 * The list of all predefined templates.  Please add all additional templates
	 *  to this list and the plugin will automatically add them.
	 *
	 * @access public
	 * @static
	 * @return void
	 */
	static function getTemplates() {
		return array(
			'Portfolio' => array(
				array(
					'type'				=> 'post_type',
					'description' 		=> __('Portfolio Items', self::$text_domain),
					'post_type'			=> 'portfolio',
					'args'				=> array(
						'label'               => __( 'portfolio', self::$text_domain ),
						'description'         => __( 'This is a portfolio', self::$text_domain ),
						'labels'              => array(
							'name'                => _x( 'Porfolios', 'Post Type General Name', self::$text_domain ),
							'singular_name'       => _x( 'Portfolio', 'Post Type Singular Name', self::$text_domain ),
							'menu_name'           => __( 'Portfolio', self::$text_domain ),
							'parent_item_colon'   => __( 'Parent Portfolio:', self::$text_domain ),
							'all_items'           => __( 'All Portfolios', self::$text_domain ),
							'view_item'           => __( 'View Portfolio', self::$text_domain ),
							'add_new_item'        => __( 'Add New Portfolio', self::$text_domain ),
							'add_new'             => __( 'Add New', self::$text_domain ),
							'edit_item'           => __( 'Edit Portfolio', self::$text_domain ),
							'update_item'         => __( 'Update Portfolio', self::$text_domain ),
							'search_items'        => __( 'Search Portfolio', self::$text_domain ),
							'not_found'           => __( 'Not found', self::$text_domain ),
							'not_found_in_trash'  => __( 'Not found in Trash', self::$text_domain ),
						),
						'supports' => array(
							'title',
							'editor',
							'thumbnail',
							'post-formats',
							'publicize',
							'wpcom-markdown',
						),
						'rewrite' => array(
							'slug'       => 'portfolio',
							'with_front' => false,
							'feeds'      => true,
							'pages'      => true,
						),
						'public'          	=> true,
						'show_ui'         	=> true,
						'show_in_nav_menus' => true,
						'show_in_menu' 		=> true,
						'show_in_admin_bar' => true,
						'menu_position'   	=> 5,
						'menu_icon'       	=> 'dashicons-portfolio',
						'capability_type' 	=> 'page',
						'map_meta_cap'    	=> true,
						'taxonomies'      	=> array('project-type', 'project-tag' ),
						'has_archive'     	=> true,
						'query_var'       	=> 'portfolio',
					)
				),
				array(
					'type'				=> 'taxonomy',
					'post_type'			=> 'portfolio',
					'taxonomy'			=> 'portfolio-type',
					'args'				=> array(
						'hierarchical'      => true,
						'labels'            => array(
							'name'              => __( 'Project Types', self::$text_domain),
							'singular_name'     => __( 'Project Type', self::$text_domain),
							'menu_name'         => __( 'Project Types', self::$text_domain),
							'all_items'         => __( 'All Project Types', self::$text_domain),
							'edit_item'         => __( 'Edit Project Type', self::$text_domain),
							'view_item'         => __( 'View Project Type', self::$text_domain),
							'update_item'       => __( 'Update Project Type', self::$text_domain),
							'add_new_item'      => __( 'Add New Project Type', self::$text_domain),
							'new_item_name'     => __( 'New Project Type Name', self::$text_domain),
							'parent_item'       => __( 'Parent Project Type', self::$text_domain),
							'parent_item_colon' => __( 'Parent Project Type:', self::$text_domain),
							'search_items'      => __( 'Search Project Types', self::$text_domain),
						),
						'public'            => true,
						'show_ui'           => true,
						'show_in_nav_menus' => true,
						'show_admin_column' => true,
						'query_var'         => true,
						'rewrite'           => array( 'slug' => 'project-type' ),
					),
				),
				array(
					'type'				=> 'taxonomy',
					'post_type'			=> 'portfolio',
					'taxonomy'			=> 'portfolio-tag',
					'args'				=> array(
						'hierarchical'      => false,
						'labels'            => array(
							'name'                       => __( 'Project Tags', self::$text_domain),
							'singular_name'              => __( 'Project Tag', self::$text_domain),
							'menu_name'                  => __( 'Project Tags', self::$text_domain),
							'all_items'                  => __( 'All Project Tags', self::$text_domain),
							'edit_item'                  => __( 'Edit Project Tag', self::$text_domain),
							'view_item'                  => __( 'View Project Tag', self::$text_domain),
							'update_item'                => __( 'Update Project Tag', self::$text_domain),
							'add_new_item'               => __( 'Add New Project Tag', self::$text_domain),
							'new_item_name'              => __( 'New Project Tag Name', self::$text_domain),
							'search_items'               => __( 'Search Project Tags', self::$text_domain),
							'popular_items'              => __( 'Popular Project Tags', self::$text_domain),
							'separate_items_with_commas' => __( 'Separate tags with commas', self::$text_domain),
							'add_or_remove_items'        => __( 'Add or remove tags', self::$text_domain),
							'choose_from_most_used'      => __( 'Choose from the most used tags', self::$text_domain),
							'not_found'                  => __( 'No tags found.', self::$text_domain),
						),
						'public'            => true,
						'show_ui'           => true,
						'show_in_nav_menus' => true,
						'show_admin_column' => true,
						'query_var'         => true,
						'rewrite'           => array( 'slug' => 'project-tag' ),
					)
				),
			),
			'Business Directory' => array(
				array(
					'type'				=> 'post_type',
					'description' 		=> __('Business Directory', self::$text_domain),
					'post_type'			=> 'business',
					'args'				=> array(
						'label'               => __( 'business', self::$text_domain ),
						'description'         => __( 'This is a business', self::$text_domain ),
						'labels'              => array(
							'name'                => _x( 'Businesses', 'Post Type General Name', self::$text_domain ),
							'singular_name'       => _x( 'Business', 'Post Type Singular Name', self::$text_domain ),
							'menu_name'           => __( 'Business', self::$text_domain ),
							'parent_item_colon'   => __( 'Parent Business:', self::$text_domain ),
							'all_items'           => __( 'All Businesses', self::$text_domain ),
							'view_item'           => __( 'View Business', self::$text_domain ),
							'add_new_item'        => __( 'Add New Business', self::$text_domain ),
							'add_new'             => __( 'Add New', self::$text_domain ),
							'edit_item'           => __( 'Edit Business', self::$text_domain ),
							'update_item'         => __( 'Update Business', self::$text_domain ),
							'search_items'        => __( 'Search Business', self::$text_domain ),
							'not_found'           => __( 'Not found', self::$text_domain ),
							'not_found_in_trash'  => __( 'Not found in Trash', self::$text_domain ),
						),
						'supports' => array(
							'title',
							'editor',
							'thumbnail',
							'post-formats',
							'publicize',
							'wpcom-markdown',
						),
						'rewrite' => array(
							'slug'       => 'business',
							'with_front' => false,
							'feeds'      => true,
							'pages'      => true,
						),
						'public'          	=> true,
						'show_ui'         	=> true,
						'show_in_nav_menus' => true,
						'show_in_menu' 		=> true,
						'show_in_admin_bar' => true,
						'menu_position'   	=> 5,
						'menu_icon'       	=> 'dashicons-businessman',
						'capability_type' 	=> 'post',
						'map_meta_cap'    	=> true,
						'taxonomies'      	=> array('businees-type', 'business-location' ),
						'has_archive'     	=> true,
						'query_var'       	=> 'business',
					)
				),
				array(
					'type'				=> 'taxonomy',
					'post_type'			=> 'business',
					'taxonomy'			=> 'businees-type',
					'args'				=> array(
						'hierarchical'      => true,
						'labels'            => array(
							'name'              => __( 'Business Types', self::$text_domain),
							'singular_name'     => __( 'Business Type', self::$text_domain),
							'menu_name'         => __( 'Business Types', self::$text_domain),
							'all_items'         => __( 'All Business Types', self::$text_domain),
							'edit_item'         => __( 'Edit Business Type', self::$text_domain),
							'view_item'         => __( 'View Business Type', self::$text_domain),
							'update_item'       => __( 'Update Business Type', self::$text_domain),
							'add_new_item'      => __( 'Add New Business Type', self::$text_domain),
							'new_item_name'     => __( 'New Business Type Name', self::$text_domain),
							'parent_item'       => __( 'Parent Business Type', self::$text_domain),
							'parent_item_colon' => __( 'Parent Business Type:', self::$text_domain),
							'search_items'      => __( 'Search Business Types', self::$text_domain),
						),
						'public'            => true,
						'show_ui'           => true,
						'show_in_nav_menus' => true,
						'show_admin_column' => true,
						'query_var'         => true,
						'rewrite'           => array( 'slug' => 'business-type' ),
					),
				),
				array(
					'type'				=> 'taxonomy',
					'post_type'			=> 'business',
					'taxonomy'			=> 'businees-location',
					'args'				=> array(
						'hierarchical'      => true,
						'labels'            => array(
							'name'              => __( 'Business Locations', self::$text_domain),
							'singular_name'     => __( 'Business Location', self::$text_domain),
							'menu_name'         => __( 'Business Locations', self::$text_domain),
							'all_items'         => __( 'All Business Locations', self::$text_domain),
							'edit_item'         => __( 'Edit Business Location', self::$text_domain),
							'view_item'         => __( 'View Business Location', self::$text_domain),
							'update_item'       => __( 'Update Business Location', self::$text_domain),
							'add_new_item'      => __( 'Add New Business Location', self::$text_domain),
							'new_item_name'     => __( 'New Business Location Name', self::$text_domain),
							'parent_item'       => __( 'Parent Business Location', self::$text_domain),
							'parent_item_colon' => __( 'Parent Business Location:', self::$text_domain),
							'search_items'      => __( 'Search Business Locations', self::$text_domain),
						),
						'public'            => true,
						'show_ui'           => true,
						'show_in_nav_menus' => true,
						'show_admin_column' => true,
						'query_var'         => true,
						'rewrite'           => array( 'slug' => 'business-location' ),
					),
				),
				array(
					'type'				=> 'custom_field',
					'group'				=> array(
						'id'            => 'business-details',
						'label'         => 'Business Details',
						'context'		=> 'advanced',
						'priority'		=> 'default',
						'capabilities'	=> array(
							'super_admin'		=> true,
							'admin'				=> true,
							'editor'			=> true,
							'author'			=> true,
							'contributor'		=> true,
						),
						'post_type'	 	=> array('business')
					),
					'fields'			=> array(
						array(
							'id'            => 'business-address',
							'label'         => 'Address',
							'type'          => 'text',
							'description'  	=> 'The full address of this business (i.e. street, city, state and zip).',
							'group'	 		=> array('business-details'),
							'args'				=> array(
								'default'		=> '',
							),
						),
						array(
							'id'            => 'business-phone',
							'label'         => 'Phone Number',
							'type'          => 'text',
							'description'  	=> 'The phone number of this business.',
							'group'	 		=> array('business-details'),
							'args'				=> array(
								'default'		=> '',
							),
						),
						array(
							'id'            => 'business-website',
							'label'         => 'Website',
							'type'          => 'text',
							'description'  	=> 'The website of this business.',
							'group'	 		=> array('business-details'),
							'args'				=> array(
								'default'		=> '',
							),
						),
					)
				),
			),
			'Jobs Directory' => array(
				array(
					'type'				=> 'post_type',
					'description' 		=> __('Jobs Directory', self::$text_domain),
					'post_type'			=> 'job',
					'args'				=> array(
						'label'               => __( 'job', self::$text_domain ),
						'description'         => __( 'This is a job', self::$text_domain ),
						'labels'              => array(
							'name'                => _x( 'Jobs', 'Post Type General Name', self::$text_domain ),
							'singular_name'       => _x( 'Job', 'Post Type Singular Name', self::$text_domain ),
							'menu_name'           => __( 'Job', self::$text_domain ),
							'parent_item_colon'   => __( 'Parent Job:', self::$text_domain ),
							'all_items'           => __( 'All Jobs', self::$text_domain ),
							'view_item'           => __( 'View Job', self::$text_domain ),
							'add_new_item'        => __( 'Add New Job', self::$text_domain ),
							'add_new'             => __( 'Add New', self::$text_domain ),
							'edit_item'           => __( 'Edit Job', self::$text_domain ),
							'update_item'         => __( 'Update Job', self::$text_domain ),
							'search_items'        => __( 'Search Job', self::$text_domain ),
							'not_found'           => __( 'Not found', self::$text_domain ),
							'not_found_in_trash'  => __( 'Not found in Trash', self::$text_domain ),
						),
						'supports' => array(
							'title',
							'editor',
							'thumbnail',
							'post-formats',
							'publicize',
							'wpcom-markdown',
						),
						'rewrite' => array(
							'slug'       => 'job',
							'with_front' => false,
							'feeds'      => true,
							'pages'      => true,
						),
						'public'          	=> true,
						'show_ui'         	=> true,
						'show_in_nav_menus' => true,
						'show_in_menu' 		=> true,
						'show_in_admin_bar' => true,
						'menu_position'   	=> 5,
						'menu_icon'       	=> 'dashicons-id',
						'capability_type' 	=> 'post',
						'map_meta_cap'    	=> true,
						'taxonomies'      	=> array('job-type', 'job-location' ),
						'has_archive'     	=> true,
						'query_var'       	=> 'job',
					)
				),
				array(
					'type'				=> 'taxonomy',
					'post_type'			=> 'job',
					'taxonomy'			=> 'job-type',
					'args'				=> array(
						'hierarchical'      => true,
						'labels'            => array(
							'name'              => __( 'Job Types', self::$text_domain),
							'singular_name'     => __( 'Job Type', self::$text_domain),
							'menu_name'         => __( 'Job Types', self::$text_domain),
							'all_items'         => __( 'All Job Types', self::$text_domain),
							'edit_item'         => __( 'Edit Job Type', self::$text_domain),
							'view_item'         => __( 'View Job Type', self::$text_domain),
							'update_item'       => __( 'Update Job Type', self::$text_domain),
							'add_new_item'      => __( 'Add New Job Type', self::$text_domain),
							'new_item_name'     => __( 'New Job Type Name', self::$text_domain),
							'parent_item'       => __( 'Parent Job Type', self::$text_domain),
							'parent_item_colon' => __( 'Parent Job Type:', self::$text_domain),
							'search_items'      => __( 'Search Job Types', self::$text_domain),
						),
						'public'            => true,
						'show_ui'           => true,
						'show_in_nav_menus' => true,
						'show_admin_column' => true,
						'query_var'         => true,
						'rewrite'           => array( 'slug' => 'job-type' ),
					),
				),
				array(
					'type'				=> 'taxonomy',
					'post_type'			=> 'job',
					'taxonomy'			=> 'job-location',
					'args'				=> array(
						'hierarchical'      => true,
						'labels'            => array(
							'name'              => __( 'Job Locations', self::$text_domain),
							'singular_name'     => __( 'Job Location', self::$text_domain),
							'menu_name'         => __( 'Job Locations', self::$text_domain),
							'all_items'         => __( 'All Job Locations', self::$text_domain),
							'edit_item'         => __( 'Edit Job Location', self::$text_domain),
							'view_item'         => __( 'View Job Location', self::$text_domain),
							'update_item'       => __( 'Update Job Location', self::$text_domain),
							'add_new_item'      => __( 'Add New Job Location', self::$text_domain),
							'new_item_name'     => __( 'New Job Location Name', self::$text_domain),
							'parent_item'       => __( 'Parent Job Location', self::$text_domain),
							'parent_item_colon' => __( 'Parent Job Location:', self::$text_domain),
							'search_items'      => __( 'Search Job Locations', self::$text_domain),
						),
						'public'            => true,
						'show_ui'           => true,
						'show_in_nav_menus' => true,
						'show_admin_column' => true,
						'query_var'         => true,
						'rewrite'           => array( 'slug' => 'job-location' ),
					),
				),
				array(
					'type'				=> 'custom_field',
					'group'				=> array(
						'id'            => 'job-details',
						'label'         => 'Job Details',
						'context'		=> 'advanced',
						'priority'		=> 'default',
						'capabilities'	=> array(
							'super_admin'		=> true,
							'admin'				=> true,
							'editor'			=> true,
							'author'			=> true,
							'contributor'		=> true,
						),
						'post_type'	 	=> array('job')
					),
					'fields'			=> array(
						array(
							'id'            => 'job-employer',
							'label'         => 'Employer',
							'type'          => 'text',
							'description'  	=> 'The employer of this job.',
							'group'	 		=> array('job-details'),
							'args'				=> array(
								'default'		=> '',
							),
						),
						array(
							'id'            => 'job-address',
							'label'         => 'Address',
							'type'          => 'text',
							'description'  	=> 'The full address of this job (i.e. street, city, state and zip).',
							'group'	 		=> array('job-details'),
							'args'				=> array(
								'default'		=> '',
							),
						),
						array(
							'id'            => 'job-salary',
							'label'         => 'Salary',
							'type'          => 'text',
							'description'  	=> 'The salary of this job.',
							'group'	 		=> array('job-details'),
							'args'				=> array(
								'default'		=> '',
							),
						),
						array(
							'id'            	=> 'job-expiry-date',
							'label'         	=> 'Expiry Date',
							'type'          	=> 'datepicker',
							'description'  		=> 'The expiry date of this job.',
							'group'	 			=> array('job-details'),
							'args'				=> array(
								'default'			=> '',
								'datepicker_type' 	=> 'date',
							),
						),
					)
				),
			),
		);


		/*
array(
					'type'				=> 'custom_field',
					'group'				=> array(
						'id'            => 'portfolio',
						'label'         => 'Portfolio',
						'context'		=> 'advanced',
						'priority'		=> 'default',
						'capabilities'	=> array(
							'super_admin'		=> true,
							'admin'				=> true,
							'editor'			=> true,
							'author'			=> true,
							'contributor'		=> true,
						),
						'post_type'	 	=> array('portfolio')
					),
					'fields'			=> array(
						array(
							'id'            => 'portfolio-name',
							'label'         => 'Name',
							'default'		=> 'Portfolio',
							'type'          => 'text',
							'description'  	=> 'The name of your portfolio',
							'group'	 		=> array('portfolio')
						)
					)
				),
				array(
					'type'				=> 'post_status',
					'status'			=> 'completed',
					'args'				=> array(
						'label'                     => __('Completed', self::$text_domain),
						'public'                    => false,
						'exclude_from_search'       => false,
						'show_in_admin_all_list'    => true,
						'show_in_admin_status_list' => true,
						'label_count'               => 'Completed',
					),
				),
				array(
					'type'				=> 'query',
					'query'				=> 'lastest-portfolios',
					'args'				=> array(
						'content' 						=> 'post_type',
						'content_custom_post_type' 		=> array('portfolio'),
						'content_custom_post_type_args' => array(
							'filter' 					=> 'all'
						),
						'custom_fields'					=> array(),
						'relation'						=> 'AND',
						'num_of_posts'					=> '5',
						'layout'						=> 'list',
						'order'							=> 'desc',
						'orderby'						=> 'date',
						'excerpt_limit'					=> '35',
						'meta_data'						=> true,
						'duplicate'						=> false,
					),
				),
*/
	}

	/**
	 * Load the text domain
	 *
	 * @since 1.0.0
	 */
	static function load_textdomain() {
		load_plugin_textdomain(self::$text_domain, false, GABFIRE_CUSTOM_DATA_STRUCTURE_PLUGIN_DIR . '/languages');
	}

	/**
	 * Hooks to 'register_activation_hook'
	 *
	 * @since 1.0.0
	 */
	static function register_activation() {

		/* Check if multisite, if so then save as site option */

		if (function_exists('is_multisite') && is_multisite()) {
			add_site_option(self::$prefix . 'version', GABFIRE_CUSTOM_DATA_STRUCTURE_VERSION_NUM);
		} else {
			add_option(self::$prefix . 'version', GABFIRE_CUSTOM_DATA_STRUCTURE_VERSION_NUM);
		}
	}

	/**
	 * Hooks to 'plugin_action_links_' filter
	 *
	 * @since 1.0.0
	 */
	static function plugin_links($links) {
		$templates_link = '<a href="tools.php?page=' . self::$templates_page . '">Templates</a>';
		array_unshift($links, $templates_link);
		return $links;
	}

	/**
	 * Hooks to 'admin_menu'
	 *
	 * @since 1.0.0
	 */
	static function menu_page() {

	    /* Another sub menu */

	    $templates_page_load = add_submenu_page(
	    	'tools.php', 													// parent slug
	    	__('Gabfire Custom Data Structure', self::$text_domain),  		// Page title
	    	__('Gabfire Custom Data Structure', self::$text_domain),  		// Menu name
	    	'manage_options', 												// Capabilities
	    	self::$templates_page, 											// slug
	    	array('GabfireCustomDataStructure', 'load_templates_page')		// Callback function
	    );
	    add_action("admin_print_scripts-$templates_page_load", array('GabfireCustomDataStructure', 'include_admin_scripts'));
	}

	/**
	 * Hooks to 'admin_print_scripts-$page'
	 *
	 * @since 1.0.0
	 */
	static function include_admin_scripts() {

		/* CSS */

		wp_register_style(self::$prefix . 'dashboard-css', GABFIRE_CUSTOM_DATA_STRUCTURE_PLUGIN_URL . '/css/dashboard.css');
		wp_enqueue_style(self::$prefix . 'dashboard-css');
	}

	/**
	 * Displays the HTML for the templates page
	 *
	 * @since 1.0.0
	 */
	static function load_templates_page() {

		// Install Template

		if (isset($_GET['action']) && $_GET['action'] == 'install' && check_admin_referer(self::$prefix . 'install')) {

			$alerts = array();

			// Get templates

			$templates = self::getTemplates();

			// Check if template is there

			if (isset($_GET['template']) && $_GET['template'] != '') {
				$template = $templates[$_GET['template']];

				// Determine if al dependencies are met

				$custom_post_types = array();
				$custom_taxonomies = array();
				$custom_fields = array();
				$custom_post_status = array();
				$custom_content_queries = array();

				foreach ($template as $item) {

					// Post Types

					if (isset($item['type']) && $item['type'] == 'post_type') {
						$custom_post_types[] = $item;
					}

					// Taxonomies

					if (isset($item['type']) && $item['type'] == 'taxonomy') {
						$custom_taxonomies[] = $item;
					}

					// Custom Fields

					if (isset($item['type']) && $item['type'] == 'custom_field') {
						$custom_fields[] = $item;
					}

					// Post Status

					if (isset($item['type']) && $item['type'] == 'post_status') {
						$custom_post_status[] = $item;
					}

					// Queries

					if (isset($item['type']) && $item['type'] == 'query') {
						$custom_content_queries[] = $item;
					}
				}

				// Add settings

				if (isset($custom_post_types) && is_array($custom_post_types) && count($custom_post_types) > 0) {

					foreach ($custom_post_types as $custom_post_type) {

						if (is_plugin_active('gabfire-custom-post-types/gabfire_custom_post.php')) {

							if (function_exists("is_multisite") && is_multisite()) {
								$gcpt_settings = get_site_option('gabfire_custom_post_settings');
							}else {
								$gcpt_settings = get_option('gabfire_custom_post_settings');
							}

							if ($gcpt_settings === false) {
								$gcpt_settings = array();
							}

							$custom_post_type_id = substr(strtolower(str_replace(' ','',sanitize_text_field($custom_post_type['post_type']))), 0, 20);

							if (!array_key_exists($custom_post_type_id, $gcpt_settings)) {
								$gcpt_settings[$custom_post_type_id] = $custom_post_type['args'];

								if (function_exists("is_multisite") && is_multisite()) {
									update_site_option('gabfire_custom_post_settings', $gcpt_settings);
								}else {
									update_option('gabfire_custom_post_settings', $gcpt_settings);
								}

								$alerts[] = array(
									'status'	=> 'updated',
									'message'	=> __('The custom post type <strong>', self::$text_domain) . $custom_post_type['post_type'] . __('</strong> has been installed correctly!', self::$text_domain),
								);
							} else {
								$alerts[] = array(
									'status'	=> 'error',
									'message'	=> __('The custom post type <strong>', self::$text_domain) . $custom_post_type['post_type'] . __('</strong> could not be installed because it is already installed.', self::$text_domain),
								);
							}

						} else {
							$alerts[] = array(
								'status'	=> 'error',
								'message'	=> __('The custom post type <strong>', self::$text_domain) . $custom_post_type['post_type'] . __('</strong> could not be installed because Gabfire Custom Post Type plugin is not activated.', self::$text_domain),
							);
						}
					}
				}

				if (isset($custom_taxonomies) && is_array($custom_taxonomies) && count($custom_taxonomies) > 0) {

					foreach ($custom_taxonomies as $custom_taxonomy) {

						if (is_plugin_active('gabfire-custom-taxonomies/gabfire_taxonomies.php')) {

							if (function_exists('is_multisite') && is_multisite()) {
								$gct_settings = get_site_option('gabfire_taxonomies_settings');
							} else {
								$gct_settings = get_option('gabfire_taxonomies_settings');
							}

							if ($gct_settings === false) {
								$gct_settings = array();
							}

							$custom_taxonomy_id = substr(strtolower(str_replace(' ','',sanitize_text_field($custom_taxonomy['taxonomy']))), 0, 20);

							if (!array_key_exists($custom_taxonomy_id, $gct_settings)) {
								$gct_settings[$custom_taxonomy_id] = array(
									'taxonomy'	=> $custom_taxonomy_id,
									'post_type'	=> (array) $custom_taxonomy['post_type'],
									'args'		=> $custom_taxonomy['args']
								);

								if (function_exists("is_multisite") && is_multisite()) {
									update_site_option('gabfire_taxonomies_settings', $gct_settings);
								}else {
									update_option('gabfire_taxonomies_settings', $gct_settings);
								}

								$alerts[] = array(
									'status'	=> 'updated',
									'message'	=> __('The custom taxonomy <strong>', self::$text_domain) . $custom_taxonomy['taxonomy'] . __('</strong> has been installed correctly!', self::$text_domain),
								);
							} else {
								$alerts[] = array(
									'status'	=> 'error',
									'message'	=> __('The custom taxonomy <strong>', self::$text_domain) . $custom_taxonomy['taxonomy'] . __('</strong> could not be installed because it is already installed.', self::$text_domain),
								);
							}

						} else {
							$alerts[] = array(
								'status'	=> 'error',
								'message'	=> __('The custom taxonomy <strong>', self::$text_domain) . $custom_taxonomy['taxonomy'] . __('</strong> could not be installed because Gabfire Custom Taxonomies plugin is not activated.', self::$text_domain),
							);
						}
					}
				}

				if (isset($custom_fields) && is_array($custom_fields) && count($custom_fields) > 0) {

					foreach ($custom_fields as $custom_field) {

						if (is_plugin_active('gabfire-custom-fields/gabfire_custom_field.php')) {

							if (function_exists('is_multisite') && is_multisite()) {
								$group_settings = get_site_option('gabfire_custom_field_group_settings');
							} else {
								$group_settings = get_option('gabfire_custom_field_group_settings');
							}

							if ($group_settings === false) {
								$group_settings = self::$default;
							}

							$group_id = stripcslashes(strtolower(str_replace(' ','',sanitize_text_field($custom_field['group']['id']))));

							if (!array_key_exists($group_id, $group_settings)) {
								$group_settings[$group_id] = $custom_field['group'];

								if (function_exists("is_multisite") && is_multisite()) {
									update_site_option('gabfire_custom_field_group_settings', $group_settings);
								}else {
									update_option('gabfire_custom_field_group_settings', $group_settings);
								}

								$alerts[] = array(
									'status'	=> 'updated',
									'message'	=> __('The custom field group <strong>', self::$text_domain) . $custom_field['group']['id'] . __('</strong> has been installed correctly!', self::$text_domain),
								);
							} else {
								$alerts[] = array(
									'status'	=> 'error',
									'message'	=> __('The custom field group <strong>', self::$text_domain) . $custom_field['group']['id'] . __('</strong> could not be installed because it is already installed.', self::$text_domain),
								);
							}

							if (function_exists('is_multisite') && is_multisite()) {
								$field_settings = get_site_option('gabfire_custom_field_settings');
							} else {
								$field_settings = get_option('gabfire_custom_field_settings');
							}

							if ($field_settings === false) {
								$field_settings = self::$default;
							}

							foreach ($custom_field['fields'] as $field) {

								if (is_plugin_active('gabfire-custom-fields/gabfire_custom_field.php')) {

									$field_id = stripcslashes(strtolower(str_replace(' ','',sanitize_text_field($field['id']))));

									if (!array_key_exists($field_id, $field_settings)) {
										$field_settings[$field_id] = $field;

										if (function_exists("is_multisite") && is_multisite()) {
											update_site_option('gabfire_custom_field_settings', $field_settings);
										}else {
											update_option('gabfire_custom_field_settings', $field_settings);
										}

										$alerts[] = array(
											'status'	=> 'updated',
											'message'	=> __('The custom field <strong>', self::$text_domain) . $field['id'] . __('</strong> has been installed correctly!', self::$text_domain),
										);
									} else {
										$alerts[] = array(
											'status'	=> 'error',
											'message'	=> __('The custom field <strong>', self::$text_domain) . $field['id'] . __('</strong> could not be installed because it is already installed.', self::$text_domain),
										);
									}

								} else {
									$alerts[] = array(
										'status'	=> 'error',
										'message'	=> __('The custom field <strong>', self::$text_domain) . $field['id'] . __('</strong> could not be installed because Gabfire Custom Fields plugin is not activated.', self::$text_domain),
									);
								}
							}

						} else {
							$alerts[] = array(
								'status'	=> 'error',
								'message'	=> __('The custom field group <strong>', self::$text_domain) . $custom_field['group']['id'] . __('</strong> could not be installed because Gabfire Custom Fields plugin is not activated.', self::$text_domain),
							);
						}
					}
				}

				if (isset($custom_post_status) && is_array($custom_post_status) && count($custom_post_status) > 0) {

					foreach ($custom_post_status as $custom_post_status_item) {

						if (is_plugin_active('gabfire-custom-post-status/gabfire-custom-post-status.php')) {

							if (function_exists('is_multisite') && is_multisite()) {
								$gcps_settings = get_site_option('gabfire_custom_post_status_settings');
							} else {
								$gcps_settings = get_option('gabfire_custom_post_status_settings');
							}

							if ($gcps_settings === false) {
								$gcps_settings = array();
							}

							$custom_status_id = strtolower(str_replace(' ','',sanitize_text_field($custom_post_status_item['status'])));

							if (!array_key_exists($custom_status_id, $gcps_settings)) {
								$gcps_settings[$custom_status_id] = $custom_post_status_item['args'];

								if (function_exists("is_multisite") && is_multisite()) {
									update_site_option('gabfire_custom_post_status_settings', $gcps_settings);
								}else {
									update_option('gabfire_custom_post_status_settings', $gcps_settings);
								}

								$alerts[] = array(
									'status'	=> 'updated',
									'message'	=> __('The custom post status <strong>', self::$text_domain) . $custom_post_status_item['status'] . __('</strong> has been installed correctly!', self::$text_domain),
								);
							} else {
								$alerts[] = array(
									'status'	=> 'error',
									'message'	=> __('The custom post status <strong>', self::$text_domain) . $custom_post_status_item['status'] . __('</strong> could not be installed because it is already installed.', self::$text_domain),
								);
							}

						} else {
							$alerts[] = array(
								'status'	=> 'error',
								'message'	=> __('The custom post status <strong>', self::$text_domain) . $custom_post_status_item['status'] . __('</strong> could not be installed because Gabfire Custom Post Status plugin is not activated.', self::$text_domain),
							);
						}
					}
				}

				if (isset($custom_content_queries) && is_array($custom_content_queries) && count($custom_content_queries) > 0) {

					foreach ($custom_content_queries as $custom_content_query) {

						if (is_plugin_active('gabfire-custom-content-query/gabfire-custom-content-query.php')) {

							if (function_exists("is_multisite") && is_multisite()) {
								$gccq_settings = get_site_option('gabfire-custom-content-query-settings');
							}else {
								$gccq_settings = get_option('gabfire-custom-content-query-settings');
							}

							if ($gccq_settings === false) {
								$gccq_settings = array();
							}

							$query_id = strtolower(str_replace(' ','',sanitize_text_field($custom_content_query['query'])));

							if (!array_key_exists($query_id, $gccq_settings['queries'])) {
								$gccq_settings['queries'][$query_id] = $custom_content_query['args'];

								if (function_exists("is_multisite") && is_multisite()) {
									update_site_option('gabfire-custom-content-query-settings', $gccq_settings);
								}else {
									update_option('gabfire-custom-content-query-settings', $gccq_settings);
								}

								$alerts[] = array(
									'status'	=> 'updated',
									'message'	=> __('The custom content query <strong>', self::$text_domain) . $custom_content_query['query'] . __('</strong> has been installed correctly!', self::$text_domain),
								);
							} else {
								$alerts[] = array(
									'status'	=> 'error',
									'message'	=> __('The custom content query <strong>', self::$text_domain) . $custom_content_query['query'] . __('</strong> could not be installed because it is already installed.', self::$text_domain),
								);
							}

						} else {
							$alerts[] = array(
								'status'	=> 'error',
								'message'	=> __('The custom content query <strong>', self::$text_domain) . $custom_content_query['query'] . __('</strong> could not be installed because Gabfire Custom Content Query plugin is not activated.', self::$text_domain),
							);
						}
					}
				}
			}

			set_transient(self::$prefix . 'alert', $alerts, self::$transient_time);

			?>
			<script type="text/javascript">
				window.location = "<?php echo $_SERVER['PHP_SELF']; ?>?page=<?php echo self::$templates_page; ?>";
			</script>
			<?php
		}

		require_once('admin/templates.php');

		?><script type="text/javascript">jQuery(document).ready(function($){<?php

		if (($alerts = get_transient(self::$prefix . 'alert')) !== false) {
			foreach ($alerts as $alert) {
				?>$('.<?php echo self::$prefix_dash; ?>title').after('<div class="<?php echo $alert['status']; ?>"><p><?php _e($alert['message'], self::$text_domain); ?></p></div>');<?php
			}
		}

		?>});</script><?php

		flush_rewrite_rules();
	}
}

?>