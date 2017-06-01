<?php
/**
 * Narnoo Distributor - Search Images table.
 **/
class Narnoo_Distributor_Search_Images_Table extends Narnoo_Distributor_Search_Media_Table {
	public $search_media_type = 'image';
	
	function column_default( $item, $column_name ) {
		switch( $column_name ) { 
			case 'caption':
			case 'entry_date':
			case 'image_id':
			//case 'owner':
			//case 'operator_id':
				return $item[ $column_name ];
			default:
				return print_r( $item, true );
		}
	}

	function column_thumbnail_image( $item ) {    
		$actions = array(
			'add_to_album'  => sprintf( 
									'<a href="?%s">%s</a>', 
									build_query( 
										array(
											'page' 				 => isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : '',
											'paged' 			 => $this->get_pagenum(),
											'action' 			 => 'add_to_album', 
											'search_media_type'  => $this->search_media_type,
											'search_media_id'    => $this->search_media_id,
											/*'search_category'    => $this->search_category,
											'search_subcategory' => $this->search_subcategory,
											'search_suburb'      => $this->search_suburb,
											'search_location'    => $this->search_location,
											'search_latitude'    => $this->search_latitude,
											'search_longitude'   => $this->search_longitude,
											'search_radius'      => $this->search_radius,
											'search_privilege'   => $this->search_privilege,*/
											'search_keywords'    => $this->search_keywords,
											'images[]' 			 => $item['image_id'], 
											'url' . $item['image_id'] => $item['thumbnail_image']
										)
									),
									__( 'Add to album', NARNOO_DISTRIBUTOR_MEDIA_I18N_DOMAIN ) 
								),
			'download'    	=> sprintf( 
									'<a href="?%s">%s</a>', 
									build_query( 
										array(
											'page' 				 => isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : '',
											'paged' 			 => $this->get_pagenum(),
											'action' 			 => 'download', 
											'search_media_type'  => $this->search_media_type,
											'search_media_id'    => $this->search_media_id,
											/*'search_category'    => $this->search_category,
											'search_subcategory' => $this->search_subcategory,
											'search_suburb'      => $this->search_suburb,
											'search_location'    => $this->search_location,
											'search_latitude'    => $this->search_latitude,
											'search_longitude'   => $this->search_longitude,
											'search_radius'      => $this->search_radius,
											'search_privilege'   => $this->search_privilege,*/
											'search_keywords'    => $this->search_keywords,
											'images[]' 			 => $item['image_id'], 
										)
									),
									__( 'Download', NARNOO_DISTRIBUTOR_MEDIA_I18N_DOMAIN ) 
								),
		);
		return sprintf( 
			'<input type="hidden" name="url%1$s" value="%2$s" /> %3$s <br /> %4$s', 
			$item['image_id'],
			$item['thumbnail_image'],
			"<img src='" . $item['thumbnail_image'] . "' />", 
			$this->row_actions($actions) 
		);
	}
	
	function column_cb($item) {
		return sprintf(
			'<input type="checkbox" class="item-id-cb" name="images[]" value="%s" />', 
			$item['image_id']
		);    
	}
	
	function get_columns() {
		return array(
			'cb'				=> '<input type="checkbox" />',
			'thumbnail_image'	=> __( 'Thumbnail', NARNOO_DISTRIBUTOR_MEDIA_I18N_DOMAIN ),
			'caption'			=> __( 'Caption', NARNOO_DISTRIBUTOR_MEDIA_I18N_DOMAIN ),
			'entry_date'		=> __( 'Entry Date', NARNOO_DISTRIBUTOR_MEDIA_I18N_DOMAIN ),
			'image_id'			=> __( 'Image ID', NARNOO_DISTRIBUTOR_MEDIA_I18N_DOMAIN )
		);
	}
	
	function get_bulk_actions() {
		$actions = array(
			'add_to_album'	=> __( 'Add to album', NARNOO_DISTRIBUTOR_MEDIA_I18N_DOMAIN ),
			'download'		=> __( 'Download', NARNOO_DISTRIBUTOR_MEDIA_I18N_DOMAIN )
		);
		return $actions;
	}

	/**
	 * Process actions and returns true if the rest of the table SHOULD be rendered.
	 * Returns false otherwise.
	 **/
	function process_action() {
		if ( isset( $_REQUEST['cancel'] ) ) {
			Narnoo_Distributor_Helper::show_notification( __( 'Action cancelled.', NARNOO_DISTRIBUTOR_I18N_DOMAIN ) );
			return true;
		}
		
		if ( isset( $_REQUEST['back'] ) || isset( $_REQUEST['search-submit'] ) ) {
			return true;
		}

		$action = $this->current_action();
		if ( false !== $action ) {
			$image_ids = isset( $_REQUEST['images'] ) ? $_REQUEST['images'] : array();
			$num_ids = count( $image_ids );
			if ( empty( $image_ids ) || ! is_array( $image_ids ) || $num_ids === 0  ) {
				return true;				
			}
			
			switch ( $action ) {

				// confirm add to album
				case 'add_to_album':
					// retrieve list of albums		
					$list = null;
					$request = Narnoo_Distributor_Helper::init_api( );
					if ( ! is_null( $request ) ) {
						try {
							$list = $request->getAlbums();
							if ( ! is_array( $list->distributor_albums ) ) {
								throw new Exception( sprintf( __( "Error retrieving albums. Unexpected format in response page #%d.", NARNOO_DISTRIBUTOR_I18N_DOMAIN ), $current_page ) );
							}
						} catch ( Exception $ex ) {
							Narnoo_Distributor_Helper::show_api_error( $ex );
						} 				
					}
							
					// no albums retrieved
					if ( is_null( $list ) ) {
						return true;
					}
					if ( count( $list->distributor_albums ) === 0 ) {
						Narnoo_Distributor_Helper::show_error( sprintf( __( '<strong>ERROR:</strong> No albums found. Please <strong><a href="%s">create an album</a></strong> first!', NARNOO_DISTRIBUTOR_I18N_DOMAIN ), "?" . build_query( array( 'page' => 'narnoo-distributor-albums', 'action' => 'create' ) ) ) );
						return true;
					}

					$total_pages = max( 1, intval( $list->total_pages ) );
					
					?>
					<h3><?php _e( 'Confirm add to album', NARNOO_DISTRIBUTOR_I18N_DOMAIN ); ?></h3>
					<?php
					
					foreach ( $list->distributor_albums as $album ) { ?>
						<input type="hidden" name="album<?php echo $album->album_id; ?>" value="<?php echo esc_attr( $album->album_name ); ?>" /><?php
					}
					?>
					<p>
						<?php printf( __( 'Please select an album to add the following %d image(s) to:', NARNOO_DISTRIBUTOR_I18N_DOMAIN ), $num_ids ); ?>
						<?php echo Narnoo_Distributor_Helper::get_album_select_html_script( $list->distributor_albums, $total_pages, 1, '' ); ?>
					</p>
					<ol>
					<?php 
					foreach ( $image_ids as $id ) { 
						?>
						<input type="hidden" name="images[]" value="<?php echo $id; ?>" />
						<li><span><?php echo __( 'Image ID:', NARNOO_DISTRIBUTOR_I18N_DOMAIN ) . ' ' . $id; ?></span><span><img style="vertical-align: middle; padding-left: 20px;" src="<?php echo ( isset( $_REQUEST[ 'url' . $id ] ) ? $_REQUEST[ 'url' . $id ] : '' ); ?>" /></span></li>
						<?php 
					} 
					?>
					</ol>
					<input type="hidden" name="action" value="do_add_to_album" />
					<p class="submit">
						<input type="submit" name="submit" id="album_select_button" class="button-secondary" value="<?php _e( 'Confirm Add to Album', NARNOO_DISTRIBUTOR_I18N_DOMAIN ); ?>" />
						<input type="submit" name="cancel" id="cancel" class="button-secondary" value="<?php _e( 'Cancel' ); ?>" />
					</p>
					<?php
					
					return false;
					
				// perform actual add to album
				case 'do_add_to_album':
					if ( ! isset( $_POST['narnoo_album_id'] ) ) {
						return true;
					}
					$album_id 		= $_POST['narnoo_album_id'];
					$album_name 	=  isset( $_POST['narnoo_album_name'] ) ? stripslashes( $_POST['narnoo_album_name'] ) : '';
					$album_page 	= isset( $_POST['narnoo_album_page'] ) ? $_POST['narnoo_album_page'] : '';
					
					?>
					<h3><?php _e( 'Add to album', NARNOO_DISTRIBUTOR_I18N_DOMAIN ); ?></h3>
					<p><?php echo sprintf( __( "Adding the following %s image(s) to album '%s' (ID %d):", NARNOO_DISTRIBUTOR_I18N_DOMAIN ), $num_ids, $album_name, $album_id ); ?></p>
					<input type="hidden" name="view_album" value="view_album" />
					<input type="hidden" name="narnoo_album_id" value="<?php echo $album_id; ?>" />
					<input type="hidden" name="narnoo_album_name" value="<?php echo esc_attr( $album_name ); ?>" />
					<input type="hidden" name="narnoo_album_page" value="<?php echo $album_page; ?>" />
					<ol>
					<?php
					foreach( $image_ids as $id ) {
						Narnoo_Distributor_Helper::print_media_ajax_script_body( $id, 'albumAddImage', array( $id, $album_id ) );
					}
					?>
					</ol>
					<?php 
					Narnoo_Distributor_Helper::print_ajax_script_footer( $num_ids, __( 'Back to images', NARNOO_DISTRIBUTOR_MEDIA_I18N_DOMAIN ), __( 'Back to search', NARNOO_DISTRIBUTOR_MEDIA_I18N_DOMAIN ) );

					return false;
			
				// perform download
				case 'download':					
					?>
					<h3><?php _e( 'Download', NARNOO_DISTRIBUTOR_MEDIA_I18N_DOMAIN ); ?></h3>
					<p><?php echo sprintf( __( "Requesting download links for the following %s image(s):", NARNOO_DISTRIBUTOR_MEDIA_I18N_DOMAIN ), $num_ids ); ?></p>
					<ol>
					<?php
					foreach( $image_ids as $index => $id ) {
						Narnoo_Distributor_Helper::print_media_ajax_script_body( $id, 'downloadImage', array( $id ) );
					}
					?>
					</ol>
					<?php 
					Narnoo_Distributor_Helper::print_ajax_script_footer( $num_ids, __( 'Back to images', NARNOO_DISTRIBUTOR_MEDIA_I18N_DOMAIN ) );

					return false;
					
			} 	// end switch( $action )
		}	// endif ( false !== $action )
		
		return true;
	}
		
	/**
	 * Request the current page data from Narnoo API server.
	 **/
	function get_current_page_data() {
		$data = array( 'total_pages' => 1, 'items' => array() );
		
		$list = parent::get_current_page_data();
		
		if ( ! is_null( $list ) ) {
			$data['total_pages'] = max( 1, intval( $list->total_pages ) );
			foreach ( $list->distributor_images as $image ) {
				//$item['owner'] = $image->media_owner_business_name;
				$item['thumbnail_image'] = $image->crop_image_path;
				$item['caption'] = $image->image_caption;
				$item['entry_date'] = $image->entry_date;
				$item['image_id'] = $image->image_id;
				$data['items'][] = $item;
			}
		}

		return $data;
	}
}    