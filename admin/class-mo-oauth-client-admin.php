<?php

require('partials/class-mo-oauth-client-admin-menu.php');
class Mo_OAuth_Client_Admin {

	
	private $plugin_name;
	private $version;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/admin.css', array(), $this->version, 'all' );
		if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'licensing'){
            wp_enqueue_style( 'mo_oauth_bootstrap_css', plugins_url( 'css/bootstrap/bootstrap.min.css', __FILE__ ) );
        }

	}

	/**
	 * Register the JavaScript for the admin area.
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'jquery' ), $this->version, false );
		if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'licensing'){
            wp_enqueue_script( 'mo_oauth_modernizr_script', plugins_url( 'js/modernizr.js', __FILE__ ) );
            wp_enqueue_script( 'mo_oauth_popover_script', plugins_url( 'js/bootstrap/popper.min.js', __FILE__ ) );
            wp_enqueue_script( 'mo_oauth_bootstrap_script', plugins_url( 'js/bootstrap/bootstrap.min.js', __FILE__ ) );
        }
	}

	public function admin_menu() {

		$page = add_menu_page( 'MO OAuth Settings ' . __( 'Configure OpenID Connect', 'mo_oauth_settings' ), 'SwissID OIDC Integrator', 'administrator', 'mo_oauth_settings', array( $this, 'menu_options' ) ,plugin_dir_url(__FILE__));
		
		// $page = add_submenu_page( 'mo_oauth_settings', 'MO Login ' . __('Advanced EVE Online Settings'), __('Advanced EVE Online Settings'), 'administrator', 'mo_oauth_eve_online_setup', 'mo_eve_online_config' );

		global $submenu;
		if(is_array($submenu) && isset($submenu['mo_oauth_settings'])){
			$submenu['mo_oauth_settings'][0][0] = __( 'Configure OpenID Connect', 'mo_oauth_login' );
		}	
	}
		
	function menu_options () {
		global $wpdb;
		update_option( 'host_name', 'https://login.xecurify.com' );
		$customerRegistered = mo_oauth_is_customer_registered();
		mo_oauth_client_main_menu();
	}
}
