<?php
/*
Plugin Name: Narnoo Distributor Media
Plugin URI: http://narnoo.com/
Description: Allows Tourism organisations that use Wordpress to manage and include their Narnoo Media Library into their Wordpress site. You will need a Narnoo API key pair to include your Narnoo media as well as our Narnoo Distributor Plugin. You can find this by logging into your account at Narnoo.com and going to Account -> View APPS.
Version: 1.0
Author: Narnoo Wordpress developer
Author URI: http://www.narnoo.com/
License: GPL2 or later
*/

/*  Copyright 2017  Narnoo.com  (email : info@narnoo.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// plugin definitions
define( 'NARNOO_DISTRIBUTOR_MEDIA_PLUGIN_NAME', 	'Narnoo Distributor Media' );
define( 'NARNOO_DISTRIBUTOR_MEDIA_CURRENT_VERSION', '1.0.0' );
define( 'NARNOO_DISTRIBUTOR_MEDIA_I18N_DOMAIN', 	'narnoo-distributor-media' );

define( 'NARNOO_DISTRIBUTOR_MEDIA_PLUGIN_URL', 		plugin_dir_url( __FILE__ ) );
define( 'NARNOO_DISTRIBUTOR_MEDIA_PLUGIN_PATH', 	plugin_dir_path( __FILE__ ) );

// include files
if( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

require_once( NARNOO_DISTRIBUTOR_MEDIA_PLUGIN_PATH . 'class-narnoo-distributor-albums-table.php' );
require_once( NARNOO_DISTRIBUTOR_MEDIA_PLUGIN_PATH . 'class-narnoo-distributor-images-table.php' );
require_once( NARNOO_DISTRIBUTOR_MEDIA_PLUGIN_PATH . 'class-narnoo-distributor-search-media-table.php' );
require_once( NARNOO_DISTRIBUTOR_MEDIA_PLUGIN_PATH . 'class-narnoo-distributor-search-images-table.php' );
require_once( NARNOO_DISTRIBUTOR_MEDIA_PLUGIN_PATH . 'class-narnoo-distributor-search-brochures-table.php' );
require_once( NARNOO_DISTRIBUTOR_MEDIA_PLUGIN_PATH . 'class-narnoo-distributor-search-videos-table.php' );
/*require_once( NARNOO_DISTRIBUTOR_MEDIA_PLUGIN_PATH . 'class-narnoo-distributor-brochures-table.php' );
require_once( NARNOO_DISTRIBUTOR_MEDIA_PLUGIN_PATH . 'class-narnoo-distributor-channels-table.php' );
require_once( NARNOO_DISTRIBUTOR_MEDIA_PLUGIN_PATH . 'class-narnoo-distributor-videos-table.php' );
require_once( NARNOO_DISTRIBUTOR_MEDIA_PLUGIN_PATH . 'class-narnoo-distributor-operator-products-accordion-table.php' );
require_once( NARNOO_DISTRIBUTOR_MEDIA_PLUGIN_PATH . 'class-narnoo-distributor-search-brochures-table.php' );
require_once( NARNOO_DISTRIBUTOR_MEDIA_PLUGIN_PATH . 'class-narnoo-distributor-search-videos-table.php' );
require_once( NARNOO_DISTRIBUTOR_MEDIA_PLUGIN_PATH . 'class-narnoo-distributor-imported-media-meta-box.php' );*/


// begin!
new Narnoo_Distributor_Media();

class Narnoo_Distributor_Media {

	/**
	 * Plugin's main entry point.
	 **/
	function __construct() {
		register_uninstall_hook( __FILE__, array( 'NarnooDistributorMedia', 'uninstall' ) );


		if ( is_admin() ) {
			//add_action( 'plugins_loaded', array( &$this, 'load_language_file' ) );
			//add_filter( 'plugin_action_links', array( &$this, 'plugin_action_links' ), 10, 2 );

			//add_action( 'admin_notices', array( &$this, 	'display_reminders' ) );
			add_action( 'admin_menu', array( &$this, 		'create_menus' ), 9 );
			//add_action( 'admin_init', array( &$this, 		'admin_init' ) );
			//add_action( 'admin_enqueue_scripts', array( 'Narnoo_Distributor_Categories_Table', 'load_scripts' ) );
			//add_action( 'admin_enqueue_scripts', array( 'Narnoo_Distributor_Operators_Table', 'load_scripts' ) );
			//add_action( 'admin_enqueue_scripts', array( 'Narnoo_Distributor_Search_Add_Operators_Table', 'load_scripts' ) );
			//add_action( 'admin_enqueue_scripts', array( 'Narnoo_Distributor_Operator_Products_Accordion_Table', 'load_scripts' ) );
			//add_action( 'admin_enqueue_scripts', array( 'Narnoo_Distributor_Search_Media_Table', 'load_scripts' ) );
			//add_action( 'admin_enqueue_scripts', array( 'Narnoo_Distributor_Search_Operator_Media_Table', 'load_scripts' ) );
			//add_action( 'admin_enqueue_scripts', array( &$this, 'load_admin_scripts' ) );

			//add_filter( 'media_upload_tabs', 									array( &$this, 						'add_narnoo_library_menu_tab' ) );
			//add_action( 'media_upload_narnoo_library', 							array( &$this, 				'media_narnoo_library_menu_handle') );
			//add_action( 'media_upload_narnoo_distributor_library', 				array( &$this, 	'media_narnoo_distributor_library_menu_handle') );

			//add_action( 'wp_ajax_narnoo_distributor_api_request', 				array( 'Narnoo_Distributor_Helper', 'ajax_api_request' ) );
			//add_action( 'wp_ajax_narnoo_add_image_to_wordpress_media_library', 	array( 'Narnoo_Distributor_Helper', 'ajax_add_image_to_wordpress_media_library' ) );

			//Meta Boxes - Operators
			add_action('add_meta_boxes', 	array( &$this, 'add_noo_op_album_meta_box'));
			add_action( 'save_post', 		array( &$this, 'save_noo_op_album_meta_box'));
			
		} else {

			//add_action( 'wp_enqueue_scripts', array( &$this, 'load_scripts' ) );
			//add_filter( 'widget_text', 'do_shortcode' );
		}

		//add_action( 'wp_ajax_narnoo_distributor_lib_request', 			array( &$this, 'narnoo_distributor_ajax_lib_request' ) );
		//add_action( 'wp_ajax_nopriv_narnoo_distributor_lib_request', 		array( &$this, 'narnoo_distributor_ajax_lib_request' ) );
		//add_shortcode('category_listings', 								array( &$this, 'narnoo_listings_child_pages') );
	}


	/**
	 * Clean up upon plugin uninstall.
	 **/
	static function uninstall() {
	//	unregister_setting( 'narnoo_distributor_settings', 'narnoo_distributor_settings', array( &$this, 'settings_sanitize' ) );
	}


	/**
	 * Load language file upon plugin init (for future extension, if any)
	 **/
	function load_language_file() {
		load_plugin_textdomain( NARNOO_DISTRIBUTOR_I18N_DOMAIN, false, NARNOO_DISTRIBUTOR_PLUGIN_PATH . 'languages/' );
	}


	/**
	 * Add admin menus and submenus to backend.
	 **/
	function create_menus() {
		
		// add main Narnoo menu
		add_menu_page(
			__( 'Narnoo Media', NARNOO_DISTRIBUTOR_MEDIA_I18N_DOMAIN ),
			__( 'Narnoo Media', NARNOO_DISTRIBUTOR_MEDIA_I18N_DOMAIN ),
			'manage_options', 
			'narnoo-distributor-images', 
			array( &$this, 'images_page' ),   
			NARNOO_DISTRIBUTOR_PLUGIN_URL . 'images/icon-16.png', 
			11
		);

		$page = add_submenu_page( 
			'narnoo-distributor-images',
			__( 'Albums', 	NARNOO_DISTRIBUTOR_MEDIA_I18N_DOMAIN ),
			__( 'Albums', 					NARNOO_DISTRIBUTOR_MEDIA_I18N_DOMAIN ),
			'manage_options', 
			'narnoo-distributor-albums', 
			array( &$this, 'albums_page' )
		); 
		add_action( "load-$page", array( 'Narnoo_Distributor_Albums_Table', 'add_screen_options' ) );
        
		$page = add_submenu_page( 
			'narnoo-distributor-images',
			__( 'Images', NARNOO_DISTRIBUTOR_MEDIA_I18N_DOMAIN ),
			__( 'Images', 				 NARNOO_DISTRIBUTOR_I18N_MEDIA_DOMAIN ),
			'manage_options', 
			'narnoo-distributor-images', 
			array( &$this, 'images_page' ) 
		); 
		add_action( "load-$page", array( 'Narnoo_Distributor_Images_Table', 'add_screen_options' ) );
		
		$page = add_submenu_page( 
			'narnoo-distributor-images',
			__( 'Search', NARNOO_DISTRIBUTOR_MEDIA_I18N_DOMAIN ),
			__( 'Search Media', NARNOO_DISTRIBUTOR_MEDIA_I18N_DOMAIN ),
			'manage_options', 
			'narnoo-distributor-search-media', 
			array( &$this, 'search_media_page' )
		); 
		add_action( "load-$page", array( 'Narnoo_Distributor_Search_Media_Table', 'add_screen_options' ) );    

	}

	/**
	 * Upon admin init, register plugin settings and Narnoo shortcodes button, and define input fields for API settings.
	 **/
	function admin_init() {
		return;

	}

	/**
	 * Display Narnoo Images page.
	 **/
	function images_page() {
		global $narnoo_distributor_images_table;		
		?>
		<div class="wrap">
			<div class="icon32"><img src="<?php echo NARNOO_DISTRIBUTOR_PLUGIN_URL; ?>/images/icon-32.png" /><br /></div>
			<h2><?php _e( 'Narnoo Media - Images', NARNOO_DISTRIBUTOR_MEDIA_I18N_DOMAIN ) ?></h2>
			<form id="narnoo-images-form" method="post" action="?<?php echo esc_attr( build_query( array( 'page' => isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : '', 'paged' => $narnoo_distributor_images_table->get_pagenum() ) ) ); ?>">
				<?php
				if ( $narnoo_distributor_images_table->prepare_items() ) {
					 $narnoo_distributor_images_table->display();
				}
				?>
			</form>			
		</div>
		<?php
	}


	/**
	 * Display Narnoo Albums page.
	 **/
	function albums_page() {		
		global $narnoo_distributor_albums_table;		
		?>
		<div class="wrap">
			<div class="icon32"><img src="<?php echo NARNOO_DISTRIBUTOR_PLUGIN_URL; ?>/images/icon-32.png" /><br /></div>
			<h2><?php _e( 'Narnoo Media - Albums', NARNOO_DISTRIBUTOR_MEDIA_I18N_DOMAIN ) ?>	
				<a href="?<?php echo build_query( array( 'page' => isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : '', 'paged' => $narnoo_distributor_albums_table->get_pagenum(), 'action' => 'create' ) ); ?>" class="add-new-h2"><?php echo esc_html_x( 'Create New', NARNOO_DISTRIBUTOR_I18N_DOMAIN ); ?></a></h2>
			<form id="narnoo-albums-form" method="post" action="?<?php echo esc_attr( build_query( array( 
				'page' => isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : '', 
				'paged' => $narnoo_distributor_albums_table->get_pagenum(), 
				'album_page' => $narnoo_distributor_albums_table->current_album_page, 
				'album' => $narnoo_distributor_albums_table->current_album_id, 
				'album_name' => $narnoo_distributor_albums_table->current_album_name 
			) ) ); ?>">
			<?php
			if ( $narnoo_distributor_albums_table->prepare_items() ) {
				?><h3>Currently viewing album: <?php echo $narnoo_distributor_albums_table->current_album_name; ?></h3><?php
				_e( 'Select album:', NARNOO_DISTRIBUTOR_MEDIA_I18N_DOMAIN );
				echo $narnoo_distributor_albums_table->select_album_html_script;
				submit_button( __( 'Go', NARNOO_DISTRIBUTOR_MEDIA_I18N_DOMAIN ), 'button-secondary action', 'album_select_button', false );
				
				$narnoo_distributor_albums_table->display();
			}
			?>
			</form>			
		</div>
		<?php
	}

	/**
	 * Display Search Media page.
	 **/
	function search_media_page() {		
		global $narnoo_distributor_search_media_table;		
		?>
		<div class="wrap">
			<div class="icon32"><img src="<?php echo NARNOO_DISTRIBUTOR_PLUGIN_URL; ?>/images/icon-32.png" /><br /></div>
			<h2><?php _e( 'Narnoo Media - Search', NARNOO_DISTRIBUTOR_MEDIA_I18N_DOMAIN ) ?></h2>
			<form id="narnoo-search-media-form" method="post" action="?<?php echo esc_attr( build_query( array( 
				'page' => isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : '', 
				'paged' => $narnoo_distributor_search_media_table->get_pagenum(), 
				'search_media_type'  => $narnoo_distributor_search_media_table->search_media_type ,
				'search_media_id'    => $narnoo_distributor_search_media_table->search_media_id   ,
				'search_category'    => $narnoo_distributor_search_media_table->search_category   , 
				'search_subcategory' => $narnoo_distributor_search_media_table->search_subcategory, 
				'search_suburb'      => $narnoo_distributor_search_media_table->search_suburb     , 
				'search_location'    => $narnoo_distributor_search_media_table->search_location   , 
				'search_latitude'    => $narnoo_distributor_search_media_table->search_latitude   , 
				'search_longitude'   => $narnoo_distributor_search_media_table->search_longitude  , 
				'search_radius'      => $narnoo_distributor_search_media_table->search_radius     , 
				'search_privilege'   => $narnoo_distributor_search_media_table->search_privilege  , 
				'search_keywords'    => $narnoo_distributor_search_media_table->search_keywords   , 
			) ) ); ?>">
				<?php
				if ( $narnoo_distributor_search_media_table->prepare_items() ) {
					$narnoo_distributor_search_media_table->display();
				}
				?>
			</form>			
		</div>
		<?php
	}

/*************************************************************************
					OPERATOR PAGES META BOXES
	/*************************************************************************/
	/*
	*
	*	title: Narnoo add narnoo album to a page
	*	date created: 15-09-16
	*/
	function add_noo_op_album_meta_box( )
	{



        add_meta_box(
            'noo-album-box-class',      		// Unique ID
		    'Select Narnoo Album', 		 		    // Title
		    array( &$this,'box_display_op_album_information'),    // Callback function
		    array('narnoo_attraction','narnoo_accommodation','narnoo_service','narnoo_dining'),         					// Admin page (or post type)
		    'side',         					// Context
		    'low'         					// Priority
         );

	}

	/*
	*
	*	title: Display the album select box
	*	date created: 15-09-16
	*/
	function box_display_op_album_information( $post )
	{

	global $post;

    //First check that this is a Narnoo imported product
    $dataSource = get_post_meta($post->ID,'data_source',true);
    if( empty($dataSource) || $dataSource != 'narnoo'){
    	return  _e( 'This product has not been imported via Narnoo.com',  NARNOO_DISTRIBUTOR_I18N_DOMAIN);
    }

    $operatorId = get_post_meta($post->ID,'operator_id',true);
    if( empty($operatorId) ){
    	return _e( 'There is no Narnoo ID associated with this product',  NARNOO_DISTRIBUTOR_I18N_DOMAIN);
    }

    //$values = get_post_custom( $post->ID );
    $selected = get_post_meta($post->ID,'noo_op_album_select_id',true);

	// We'll use this nonce field later on when saving.
    wp_nonce_field( 'op_album_meta_box_nonce', 'box_display_op_album_information_nonce' );

		$current_page 		      = 1;
		//$cache	 				  = Narnoo_Distributor_Helper::init_noo_cache();
		$request 				  = Narnoo_Distributor_Helper::init_api( 'operator' );

		//Get Narnoo Ablums.....
		if ( ! is_null( $request ) ) {

			//$list = $cache->get('albums_'.$current_page);

			if( empty($list) ){

					try {

						$list = $request->getAlbums( $operatorId,$current_page );
						if ( ! is_array( $list->operator_albums ) ) {
							throw new Exception( sprintf( __( "Error retrieving albums. Unexpected format in response page #%d.", NARNOO_DISTRIBUTOR_I18N_DOMAIN ), $current_page ) );
						}

						if(!empty( $list->success ) ){
								//$cache->set('albums_'.$current_page, $list, 43200);
						}

					} catch ( Exception $ex ) {
						//Narnoo_Distributor_Helper::show_api_error( $ex ); don't need to show anything
					}

			}

			//Check the total pages and run through each so we can build a bigger list of albums

		}


    ?> <p>
        <label for="my_meta_box_select">Narnoo Album:</label>
        <select name="noo_op_album_select" id="noo_op_album_select">
        	<option value="">None</option>
            <?php foreach ($list->operator_albums as $album) { ?>
            		<option value="<?php echo $album->album_id; ?>" <?php selected( $selected, $album->album_id ); ?>><?php echo ucwords( $album->album_name ); ?></option>
            <?php } ?>
        </select>
        <p><small><em>Select an album and this will be displayed the page.</em></small></p>
    </p>
  	<?php

	}

	function save_noo_op_album_meta_box( $post_id ){

		// Bail if we're doing an auto save
	    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

	    // if our nonce isn't there, or we can't verify it, bail
	    if( !isset( $_POST['box_display_op_album_information_nonce'] ) || !wp_verify_nonce( $_POST['box_display_op_album_information_nonce'], 'op_album_meta_box_nonce' ) ) return;

	    // if our current user can't edit this post, bail
	    if( !current_user_can( 'edit_post' ) ) return;

	    if( isset( $_POST['noo_op_album_select'] ) ){
        	update_post_meta( $post_id, 'noo_op_album_select_id', esc_attr( $_POST['noo_op_album_select'] ) );
    	}

	}

	/*************************************************************************
					OPERATOR PAGES META BOXES [end]
	/*************************************************************************/




}
