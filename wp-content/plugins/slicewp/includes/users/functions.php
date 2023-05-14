<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Includes the files needed for the user area
 *
 */
function slicewp_include_files_user() {

	// Get dir path
	$dir_path = plugin_dir_path( __FILE__ );

	// Include the db layer classes
	if ( file_exists( $dir_path . 'class-user-notices.php' ) ) {
		include $dir_path . 'class-user-notices.php';
	}

	// Include HTML list table class.
	if ( file_exists( $dir_path . 'class-list-table.php' ) ) {
		include $dir_path . 'class-list-table.php';
	}

	if ( file_exists( $dir_path . 'class-list-table-affiliate-account-commissions.php' ) ) {
		include $dir_path . 'class-list-table-affiliate-account-commissions.php';
	}

	if ( file_exists( $dir_path . 'class-list-table-affiliate-account-payment-commissions.php' ) ) {
		include $dir_path . 'class-list-table-affiliate-account-payment-commissions.php';
	}

	if ( file_exists( $dir_path . 'class-list-table-affiliate-account-visits.php' ) ) {
		include $dir_path . 'class-list-table-affiliate-account-visits.php';
	}

	if ( file_exists( $dir_path . 'class-list-table-affiliate-account-payments.php' ) ) {
		include $dir_path . 'class-list-table-affiliate-account-payments.php';
	}

	// Include HTML list table class.
	if ( file_exists( $dir_path . 'functions-actions-list-table.php' ) ) {
		include $dir_path . 'functions-actions-list-table.php';
	}

}
add_action( 'slicewp_include_files', 'slicewp_include_files_user' );


/**
 * Adds a central action hook on the init that the plugin and add-ons
 * can use to do certain actions, like adding a new user, editing a user, etc.
 *
 */
function slicewp_register_user_do_actions() {

	// Exit if is accessed from admin panel
	if ( is_admin() ) {
		return;
	}

	if ( empty( $_REQUEST['slicewp_action'] ) ) {
		return;
	}

	$action = sanitize_text_field( $_REQUEST['slicewp_action'] );

	/**
	 * Hook that should be used by all processes that make a certain action
	 * withing the plugin, like adding a new user, editing an user, etc.
	 *
	 */
	do_action( 'slicewp_user_action_' . $action );

}
add_action( 'init', 'slicewp_register_user_do_actions' );


/**
 * Returns an array with the registered tabs found in the affiliate account.
 * 
 * @return array
 * 
 */
function slicewp_get_affiliate_account_tabs() {

	$tabs = array(
		'dashboard' => array(
			'label' => __( 'Dashboard', 'slicewp' ),
			'icon'  => slicewp_get_svg( 'outline-home' )
		),
		'affiliate_links' => array(
			'label' => __( 'Affiliate Links', 'slicewp' ),
			'icon'  => slicewp_get_svg( 'outline-link' )
		),
		'commissions' => array(
			'label' => __( 'Commissions', 'slicewp' ),
			'icon'  => slicewp_get_svg( 'outline-chart-pie' )
		),
		'visits' => array(
			'label' => __( 'Visits', 'slicewp' ),
			'icon'  => slicewp_get_svg( 'outline-cursor-click' )
		),
		'creatives' => array(
			'label' => __( 'Creatives', 'slicewp' ),
			'icon'  => slicewp_get_svg( 'outline-color-swatch' )
		),
		'payments' => array(
			'label' => __( 'Payouts', 'slicewp' ),
			'icon'  => slicewp_get_svg( 'outline-cash' )
		),
		'settings' => array(
			'label' => __( 'Settings', 'slicewp' ),
			'icon'  => slicewp_get_svg( 'outline-adjustments' )
		)
	);
	
	// Add logout if enabled.
	if ( slicewp_get_setting( 'affiliate_account_logout' ) ) {
	
		$tabs['logout'] = array(
			'label' => __( 'Logout', 'slicewp' ),
			'icon'	=> slicewp_get_svg( 'outline-logout' ),
			'url'	=> slicewp_get_logout_url()
		);
	
	}
	
	
	/**
	 * Filter the tabs for the settings edit screen
	 *
	 * @param array $tabs
	 *
	 */
	$tabs = apply_filters( 'slicewp_affiliate_account_tabs', $tabs );

	return $tabs;

}


/**
 * Returns an array with the options the user has for the items per page selector.
 * 
 * @return array
 * 
 */
function slicewp_get_list_table_items_per_page_options() {

	$options = array( 10, 25, 50, 100 );

	/**
	 * Filter the array before returning.
	 * 
	 * @param array
	 * 
	 */
	$options = apply_filters( 'slicewp_list_table_items_per_page_options', $options );

	return $options;

}