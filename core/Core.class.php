<?php
/**
 * Helpful Core Class
 *
 * @author  Devhats
 * @version 1.0
 */
 
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) exit;

// Init class
new HelpfulCore;

class HelpfulCore
{	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		global $jal_db_version;
		$jal_db_version = '1.0';
		
		// Install database table
		register_activation_hook( HELPFUL_FILE, array( $this, 'install' ) );
		
		// Register menu
		add_action('admin_menu', array( $this, 'menu' ) );
		
		// Register settings for settings page
		add_action( 'admin_init', array( $this, 'settings' ) );
		
		// Register tab general
		add_action( 'helpful_tabs', array( $this, 'tab_general' ), 1 );
		
		// Register tab system
		add_action( 'helpful_tabs', array( $this, 'tab_system' ), 99 );
		
		// Register tabs content
		add_action( 'helpful_tabs_content', array( $this, 'tabs_content' ) );
		
		// Add after content
		add_filter( 'the_content', array( $this , 'add_to_content' ) );		
		
		// Enqueue backend Scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'backend_enqueue' ) );

		// Enqueue frontend scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_enqueue' ) );
		
		// Ajax requests
		add_action( 'wp_ajax_helpfull_ajax_callback', array( $this, 'helpfull_ajax_callback' ) );
		add_action( 'wp_ajax_nopriv_helpfull_ajax_callback', array( $this, 'helpfull_ajax_callback' ) );
		
		// Frontend helpers
		add_filter( 'helpful_helpers', array( $this, 'frontend_helpers' ) );
		
		// Backend sidebar
		add_action( 'helpful_sidebar', array( $this, 'sidebar' ), 1 );
		
		// Error Message
		add_filter( 'helpful_error', array( $this, 'error' ), 1 );
		
		// After Messages
		add_filter( 'helpful_after_pro', array( $this, 'after_message' ), 1 );
		add_filter( 'helpful_after_contra', array( $this, 'after_message' ), 1 );
		
		// Register columns
		$this->register_columns();
		
		// Register columns content
		$this->register_columns_content();
		
		// Register sortable columns
		$this->register_sortable_columns();
		
		// Make columns values sortable in query
		add_action( 'pre_get_posts', array( $this, 'make_sortable_columns' ), 1 );
		
		// Dashboard widget
		$this->register_widget();
		
		// Truncate table (uninstall function)
		$this->truncate();
	}
	
	/**
	 * Register Menu
	 */
	public function menu()
	{
		// add submenu on options
		add_submenu_page(
			'options-general.php',
			__( 'Helpful', 'helpful' ), 
			__( 'Helpful', 'helpful' ),
			'manage_options', 
			'helpful', 
			array( $this, 'admin_page_callback' )
		);
	}
	
	/**
	 * Register Settings
	 */
	public function settings()
	{
		// general
		register_setting( 'helpful-general-settings-group', 'helpful_credits' );
		register_setting( 'helpful-general-settings-group', 'helpful_heading' );
		register_setting( 'helpful-general-settings-group', 'helpful_content' );
		register_setting( 'helpful-general-settings-group', 'helpful_pro' );
		register_setting( 'helpful-general-settings-group', 'helpful_exists' );
		register_setting( 'helpful-general-settings-group', 'helpful_post_types' );
		register_setting( 'helpful-general-settings-group', 'helpful_contra' );
		register_setting( 'helpful-general-settings-group', 'helpful_exists_hide' );
		register_setting( 'helpful-general-settings-group', 'helpful_count_hide' );
		register_setting( 'helpful-general-settings-group', 'helpful_widget' );
		register_setting( 'helpful-general-settings-group', 'helpful_column_pro' );
		register_setting( 'helpful-general-settings-group', 'helpful_column_contra' );

		// system
		register_setting( 'helpful-system-settings-group', 'helpful_uninstall' );
	}
	
	/**
	 * Default options
	 */
	public function default_options( $bool = false )
	{
		if( $bool == true ):
		
		global $helpful;
		
		// general
		update_option( 'helpful_heading', $helpful['strings']['heading'] );
		update_option( 'helpful_content', $helpful['strings']['content'] );
		update_option( 'helpful_exists', $helpful['strings']['exists'] );
		update_option( 'helpful_success', $helpful['strings']['success'] );
		update_option( 'helpful_error', $helpful['strings']['error'] );
		update_option( 'helpful_pro', $helpful['strings']['button-pro'] );
		update_option( 'helpful_contra', $helpful['strings']['button-contra'] );
		update_option( 'helpful_column_pro', $helpful['strings']['column-pro'] );
		update_option( 'helpful_column_contra', $helpful['strings']['column-contra'] );
		update_option( 'helpful_post_types', array('post') );
		update_option( 'helpful_count_hide', false );
		update_option( 'helpful_credits', true );
		update_option( 'helpful_widget', false );

		// system
		update_option( 'helpful_uninstall', false );
		
		endif;
	}
	
	/**
	 * Admin page callback
	 */
	public function admin_page_callback()
	{		
		include( plugin_dir_path( HELPFUL_FILE ) . 'templates/backend.php' );
	}
	
	/**
	 * Register tab general
	 */
	public function tab_general()
	{
		global $helpful;
		
		$class = ( $helpful['tab'] == 'general' ? 'helpful-tab helpful-tab-active' : 'helpful-tab' );
		
		echo '<li class="' . $class . '">';
		echo '<a href="?page=helpful&tab=general" class="helpful-tab-link">' . __('Allgemein', 'helpful')  . '</a>';
		echo '</li>';
	}
	
	/**
	 * Register tab general
	 */
	public function tab_system()
	{
		global $helpful;
		
		$class = ( $helpful['tab'] == 'system' ? 'helpful-tab helpful-tab-active' : 'helpful-tab' );
		
		echo '<li class="' . $class . '">';
		echo '<a href="?page=helpful&tab=system" class="helpful-tab-link">' . __('System', 'helpful')  . '</a>';
		echo '</li>';
	}
	
	/**
	 * Register tabs content
	 */
	public function tabs_content() 
	{
		foreach ( glob( plugin_dir_path( HELPFUL_FILE ) . "core/tabs/*.php" ) as $file ) {
			include_once $file;
		}
	}
	
	/**
	 * Add after content
	 */
	public function add_to_content($content) 
	{	
		// is single
		if( get_option('helpful_post_types') && is_singular() ) {
			
			global $post;
			$current = get_post_type( $post );
			
			if( in_array( $current, get_option('helpful_post_types') ) ):

			ob_start();
			
			// custom frontend exists?
			if( file_exists( get_template_directory() . '/helpful/frontend.php' ) ) {
				include( get_template_directory() . '/helpful/frontend.php' );				
			}			
			else {
				include( plugin_dir_path( HELPFUL_FILE ) . 'templates/frontend.php' );			
			}
			
			$helpful = ob_get_contents();
			ob_end_clean();	

			// add content after post content
			$content = $content . $helpful;
			
			endif;

		}
	
		// return the new content
		return $content;
	}
	
	/**
	 * Ajax callback
	 */
	public function helpfull_ajax_callback()
	{		
		
		// like it
		if( $_REQUEST['pro'] == 1 ) {
		
			// do request if defined		
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) { 
				
				// set args for insert command
				$args = array(
					'post_id' 	=> $_REQUEST['post_id'],
					'user'		=> $_REQUEST['user'],
					'pro' 		=> $_REQUEST['pro'],
					'contra'	=> $_REQUEST['contra']
				);				
				
				// do and check insert command
				if( $this->insert( $args ) ) {
					echo apply_filters( 'helpful_after_pro', $value );
					wp_die();
				}
				
				else {
					echo apply_filters( 'helpful_error', $value );
					wp_die();
				}
			}
			
			// if request not definied do redirect
			else {
				wp_redirect( get_permalink( $_REQUEST['post_id'] ) );
				exit();
			}
			
		}	
		
		// dont like it
		if( $_REQUEST['contra'] == 1 ) {
		
			// do requeset if defined
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) { 
				
				// set args for insert command
				$args = array(
					'post_id' 	=> $_REQUEST['post_id'],
					'user'		=> $_REQUEST['user'],
					'pro' 		=> $_REQUEST['pro'],
					'contra'	=> $_REQUEST['contra']
				);
				
				// do and check insert command
				if( $this->insert( $args ) ) {
					echo apply_filters( 'helpful_after_contra', $value );
					wp_die();
				}
				
				else {
					echo apply_filters( 'helpful_error', $value );
					wp_die();
				}
			}

			// if request not definied do redirect
			else {
				wp_redirect( get_permalink( $_REQUEST['post_id'] ) );
				exit();
			}
			
		}
		
		wp_die();
	}
	
	/**
	 * Backend enqueue scripts
	 */
	public function backend_enqueue()
	{
		// Register charts
		wp_register_style(
			'helpful-charts', 
			plugins_url( 'core/assets/css/charts.css', HELPFUL_FILE ), 
			false 
		);
		
		// current screen is helpful
		if( is_helpful() ) {

			// Backend CSS
			wp_enqueue_style ( 
				'helpful-backend', 
				plugins_url( 'core/assets/css/backend.css', HELPFUL_FILE ), 
				false 
			);
		}
	}
	
	/**
	 * Enqueue scripts
	 */
	public function frontend_enqueue()
	{
		if( get_option('helpful_post_types') && is_singular() ) {
			
			global $post;
			$current = get_post_type( $post );
			
			// if $current post type is in the helpful post type array
			if( in_array( $current, get_option('helpful_post_types') ) ):
			
				// Frontend CSS			
				if( !get_option( 'helpful_theme' ) ) {
					update_option('helpful_theme','base');
				}
			
				wp_enqueue_style ( 
					'helpful-frontend', 
					plugins_url( 'core/assets/themes/' . get_option( 'helpful_theme' ) . '.css', HELPFUL_FILE ), 
					false 
				);				

				// Frontend Ajax
				wp_enqueue_script( 
					'helpful-frontend', 
					plugins_url( 'core/assets/js/frontend.js', HELPFUL_FILE ), 
					array('jquery'), 
					'1.0', 
					true 
				);
			
				// Frontend Ajax (wp)
				wp_localize_script( 
					'helpful-frontend', 
					'helpful',
					array( 
						'ajax_url' => admin_url( 'admin-ajax.php' ) 
					)
				);	
			
			endif;
		
		}
	}
	
	/**
	 * Frontend helpers
	 */
	public function frontend_helpers( $content )
	{
		global $post, $helpful;
		$post_id = $post->ID;
		
		// options		
		$class = ( get_option('helpful_theme') ? 'helpful helpful-theme-' . get_option('helpful_theme') : 'helpful' );
		$credits = ( get_option( 'helpful_credits' ) ? $helpful['strings']['credits'] : '' );		
		$heading = get_option( 'helpful_heading' );
		$content = get_option( 'helpful_content' );
		$pro = get_option( 'helpful_pro' );
		$contra = get_option( 'helpful_contra' );
		$hide_counts = get_option( 'helpful_count_hide' );
		
		// md5 IP		
		$user = md5($_SERVER['REMOTE_ADDR']);
		
		// get counts
		$count_pro = ( get_post_meta( $post_id, 'helpful-pro', true ) ? get_post_meta( $post_id, 'helpful-pro', true ) : 0 );
		$count_con = ( get_post_meta( $post_id, 'helpful-contra', true ) ? get_post_meta( $post_id, 'helpful-contra', true ) : 0 );
		
		$count_pro = ( get_option( 'helpful_count_hide' ) ? '' : '<span class="counter">' . $count_pro . '</span>' );
		$count_con = ( get_option( 'helpful_count_hide' ) ? '' : '<span class="counter">' . $count_con . '</span>' );
		
		// markup btn pro
		$btn_pro = '<div class="helpful-pro" ';
		$btn_pro .= 'data-id="' . $post_id . '" ';
		$btn_pro .= 'data-user="' . $user . '" ';
		$btn_pro .= 'data-pro="1" ';
		$btn_pro .= 'data-contra="0">';
		$btn_pro .= $pro . $count_pro;
		$btn_pro .= '</div>';
		
		// markup btn contra
		$btn_con = '<div class="helpful-con" ';
		$btn_con .= 'data-id="' . $post_id . '" ';
		$btn_con .= 'data-user="' . $user . '" ';
		$btn_con .= 'data-pro="0" ';
		$btn_con .= 'data-contra="1">';
		$btn_con .= $contra . $count_con;
		$btn_con .= '</div>';
		
		// set array for frontend template
		$content = array(
			'class' 		=> $class,
			'credits' 		=> $credits,
			'heading' 		=> $heading,
			'content' 		=> nl2br( $this->str_to_helpful( $content, $post_id ) ),
			'button-pro' 	=> $btn_pro,
			'button-contra' => $btn_con,
			'exists'		=> $this->check( $post_id, $user ),
			'exists-text'	=> nl2br( $this->str_to_helpful( get_option('helpful_exists'), $post_id ) ),
			'exists-hide'	=> ( get_option( 'helpful_exists_hide' ) ? true : false ),
		);	
		
		return $content;		
	}
	
	/**
	 * String to Helpful (Helper)
	 */
	public function str_to_helpful( $string, $post_id )
	{
		$pro = get_post_meta( $post_id, 'helpful-pro', true );
		$pro = ( $pro ? $pro : 0 );
		$contra = get_post_meta( $post_id, 'helpful-contra', true );	
		$contra = ( $contra ? $contra : 0 );	
		$new = str_replace( '{pro}', $pro, $string );
		$new = str_replace( '{contra}', $contra, $new );		
		return $new;		
	}
	
	/**
	 * String to Helpful Pro (Helper)
	 */
	public function str_to_pro( $string, $post_id )
	{
		$pro = get_post_meta( $post_id, 'helpful-pro', true );
		$pro = ( $pro ? $pro : 0 );
		return str_replace( '{pro}', $pro, $string );		
	}
	
	/**
	 * String to Helpful Contra (Helper)
	 */
	public function str_to_contra( $string, $post_id )
	{
		$contra = get_post_meta( $post_id, 'helpful-contra', true );	
		$contra = ( $contra ? $contra : 0 );	
		return str_replace( '{contra}', $contra, $string );		
	}
	
	/**
	 * Install Database Table
	 */
	public function install()
	{		
		global $wpdb;
		global $jal_db_version;

		// table name
		$table_name = $wpdb->prefix . 'helpful';

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			user varchar(55) NOT NULL,
			pro mediumint(1) NOT NULL,
			contra mediumint(1) NOT NULL,
			post_id mediumint(9) NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		add_option( 'jal_db_version', $jal_db_version );
		
		$this->default_options(true);
	}
	
	/**
	 * Insert row
	 */
	public function insert( $args = null )
	{
		// check args
		if( $args == null) return false;		
		if( !$args['user'] ) return false;	
		if( !$args['post_id'] ) return false;	
		
		$user = $args['user'];
		$pro = ( $args['pro'] == 1 ? 1 : 0 );
		$contra = ( $args['contra'] == 1 ? 1 : 0 );
		$post_id = $args['post_id'];
		
		global $wpdb;
		
		$table_name = $wpdb->prefix . 'helpful';
		
		$wpdb->insert( 
			$table_name, 
			array( 
				'time' 		=> current_time( 'mysql' ), 
				'user' 		=> $user, 
				'pro' 		=> $pro, 
				'contra' 	=> $contra, 
				'post_id' 	=> $post_id, 
			) 
		);
		
		// insert pro in post meta
		if( $pro == 1 ) {
			if( get_post_meta( $args['post_id'], 'helpful-pro' ) ):		
				$current = get_post_meta( $args['post_id'], 'helpful-pro', true );
				$current = $current++;		
				update_post_meta( $args['post_id'], 'helpful-pro', $current );
			else:
				update_post_meta( $args['post_id'], 'helpful-pro', 1 );
			endif;
		}
		
		// insert contra in post meta
		if( $contra == 1 ) {
			if( get_post_meta( $args['post_id'], 'helpful-contra' ) ):		
				$current = get_post_meta( $args['post_id'], 'helpful-contra', true );
				$current = $current++;		
				update_post_meta( $args['post_id'], 'helpful-contra', $current );
			else:
				update_post_meta( $args['post_id'], 'helpful-contra', 1 );
			endif;
		}		
		
		return true;
	}
	
	/**
	 * Check user
	 */
	public function check( $post_id, $user ) 
	{
		if( !$post_id ) return false;
		if( !$user ) return false;
		
		global $wpdb;
		
		// table
		$table_name = $wpdb->prefix . 'helpful';
		
		$result = $wpdb->get_row( "SELECT * FROM $table_name WHERE post_id = $post_id AND user = '$user'" );
		
		if( $result ) return true;
		
		return $result;		
	}
	
	/**
	 * Truncate Table and delete post metas
	 */
	public function truncate()
	{
		if( get_option('helpful_uninstall') ):
		
		global $wpdb, $helpful;		
		
		$table_name = $wpdb->prefix . 'helpful';
		$wpdb->query("TRUNCATE TABLE $table_name");
		update_option( 'helpful_uninstall', false );		
			
		$posts = get_posts( array( 'post_type' => 'any', 'posts_per_page' => -1 ) );
		
		foreach( $posts as $post ) {			
			if( get_post_meta( $post->ID, 'helpful-pro' ) ) :
				delete_post_meta( $post->ID, 'helpful-pro' );
			endif;
			
			if( get_post_meta( $post->ID, 'helpful-contra' ) ) :
				delete_post_meta( $post->ID, 'helpful-contra' );
			endif;
		}
		
		$this->default_options(true);
	
		$helpful['system'] = __('Die Datenbank-Tabelle wurde erfolgreich zurückgesetzt!','helpful');		
		
		endif;
	}
	
	/**
	 * Backend informations container
	 */
	public function sidebar()
	{
		$html  = '<h4>' . __('Links & Support', 'helpful') . '</h4>';
		
		$html .= '<p>' . __('Du benötigst mehr Informationen?', 'helpful') . '</p>';		
		$html .= '<ul>';
		$html .= '<li><a href="https://wordpress.org/plugins/helpful/#developers" target="_blank">' . __('Changelog', 'helpful') . '</a></li>';
		$html .= '<li><a href="https://wordpress.org/support/plugin/helpful" target="_blank">' . __('Support', 'helpful') . '</a></li>';
		$html .= '</ul>';
		
		$html .= '<hr />';
		
		$html .= '<p>' . __('Auf der Suche nach Themes und Design-Einstellungen?<br />Kaufe dir noch heute die Pro-Version von Helpful!', 'helpful') . '</p>';
		$html .= '<ul>';
		$html .= '<li><a href="https://devhats.de/" target="_blank">' . __('Weitere Informationen', 'helpful') . '</a></li>';
		$html .= '</ul>';

		echo $html;
	}
	
	/**
	 * Register widget
	 */
	public function register_widget()
	{
		if( !get_option('helpful_widget') ) {
			add_action( 'wp_dashboard_setup', array( $this, 'widget' ), 1 );
		}
	}
	
	/**
	 * Widget
	 */
	public function widget()
	{
		global $wp_meta_boxes;
		wp_add_dashboard_widget(
			'helpful_widget', 
			__('Helpful Statistiken','helpful'), 
			array( $this, 'widget_callback') 
		);
		
		$dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
		
		$helpful_widget = array( 'helpful_widget' => $dashboard['helpful_widget'] );
		
		unset( $dashboard['helpful_widget'] );
		
		$sorted_dashboard = array_merge( $helpful_widget, $dashboard );
		
		$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
	}
	
	/** 
	 * Widget callback
	 */
	public function widget_callback()
	{	
		global $post;
		
		wp_enqueue_style('helpful-charts');
		
		$html = '';		
		$url  = admin_url('options-general.php?page=helpful');
		$post_types = get_option('helpful_post_types');
				
		// Pros
		$args = array(
			'posts_per_page' => 5,
			'post_type' => $post_types,
			'order' => 'DESC',
			'orderby' => 'meta_value_num',
			'meta_key' => 'helpful-pro'
		);
	 
		$pros = get_posts( $args['pro'] );		
		if( $pros ) : foreach( $pros as $pro ) :				
		$_pro[] = get_post_meta( $pro->ID, 'helpful-pro', true );
		endforeach;	endif;
				
		// Cons
		$args = array(
			'posts_per_page' => 5,
			'post_type' => $post_types,
			'order' => 'DESC',
			'orderby' => 'meta_value_num',
			'meta_key' => 'helpful-contra'
		);
	 
		$contras = get_posts( $args['contra'] );		
		if( $contras ) : foreach( $contras as $contra ) :				
		$_contra[] = get_post_meta( $contra->ID, 'helpful-contra', true );
		endforeach;	endif;
		
		$p = array_sum($_pro); // Sum of pros
		$c = array_sum($_contra); // Sum of cons
		
		#$s = ( $p / ($p+$c) ) * 100;		
		#$html .= '<div class="helpful-counter">';
		#$html .= '<span>' . $s . '%</span>';
		#$html .= '</div>';
			
		// Pro Counter
		$html .= '<div class="helpful-counter-pro">';		
		$html .= '<span>' . $p . '</span>';
		$html .= '</div>';
			
		// Contra Counter
		$html .= '<div class="helpful-counter-contra">';			
		$html .= '<span>' . $c . '</span>';		
		$html .= '</div>';
		
		$html .= '<hr />';
		
		// Credits Link
		$html .= '<div class="helpful-credits">';
		$html .= 'Powered by <a href="https://devhats.de/" target="_blank" rel="nofollow">DEVHATS</a>';
		$html .= '</div>';
		
		// Settings Link
		$html .= '<div class="helpful-settings">';
		$html .= '<a href="' . $url . '" title="' . __('Helpful Einstellungen','helpful') . '">';
		$html .= '<span class="dashicons dashicons-admin-generic"></span>';
		$html .= '</a>';
		$html .= '</div>';
		
		echo $html;
	}
	
	/**
	 * Register columns
	 */
	public function register_columns()
	{
		$post_types = get_option('helpful_post_types');
		
		if( $post_types ) {
			
			foreach( $post_types as $post_type ) {
				add_filter( 'manage_edit-' . $post_type . '_columns', array( $this, 'columns' ), 10 );
			}			
		}
	}
	
	/**
	 * Columns
	 */
	public function columns( $defaults )
	{		
		global $helpful;
		
		$columns = array();
		foreach ($defaults as $key => $value) {
			$columns[$key] = $value;
			if ( $key == 'title' ) { 
				$columns['helpful-pro'] = $helpful['strings']['column-pro'];
				$columns['helpful-contra'] = $helpful['strings']['column-contra'];
			} 
		}		
    	return $columns;
	}
	
	/**
	 * Register columns content
	 */
	public function register_columns_content()
	{
		$post_types = get_option('helpful_post_types');
		
		if( $post_types ) {
			
			foreach( $post_types as $post_type ) {
				add_action( 'manage_' . $post_type . '_posts_custom_column', array( $this, 'columns_content' ), 10, 2 );
			}			
		}
	}
	
	/**
	 * Columns content
	 */
	public function columns_content( $column_name, $post_id )
	{
		if ( 'helpful-pro' == $column_name ) {		
			$pros = get_post_meta( $post_id, 'helpful-pro', true );
			echo intval( $pros );
		}
		
		if ( 'helpful-contra' == $column_name ) {
			$cons = get_post_meta($post_id, 'helpful-contra', true );
			echo intval( $cons );
		}
	}
	
	/**
	 * Register sortable columns
	 */
	public function register_sortable_columns()
	{
		$post_types = get_option('helpful_post_types');
		
		if( $post_types ) {
			
			foreach( $post_types as $post_type ) {
				add_filter( 'manage_edit-' . $post_type . '_sortable_columns', array( $this, 'sortable_columns' ) );
			}			
		}
	}
	
	/**
	 * Sortable columns
	 */
	public function sortable_columns( $sortable_columns )
	{
		$sortable_columns[ 'helpful-pro' ] = 'helpful-pro';
   		$sortable_columns[ 'helpful-contra' ] = 'helpful-contra';
		return $sortable_columns;
	}
	
	/**
	 * Make columns values sortable in query
	 */
	public function make_sortable_columns( $query ) 
	{		
		if ( $query->is_main_query() && ( $orderby = $query->get( 'orderby' ) ) ) {
			
			switch( $orderby ) {
				
				case 'helpful-pro':
				
				$query->set( 'meta_key', 'helpful-pro' );
				$query->set( 'orderby', 'meta_value' );
				
            	break;
				
				case 'helpful-contra':
				
				$query->set( 'meta_key', 'helpful-contra' );
				$query->set( 'orderby', 'meta_value' );
				
            	break;

      		}
		}
	}
	
	/**
	 * After Message
	 */
	public function after_message( $value )
	{
		$value = __('Vielen Dank für Ihre Stimme!','helpful');
		return $value;
	}
	
	/**
	 * Error
	 */
	public function error()
	{
		_e('Leider ist ein Fehler aufgetreten.','helpful');
	}
}