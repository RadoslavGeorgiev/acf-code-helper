<?php
/**
 * Plugin name: ACF Code Helper
 * Author: Radoslav Georgiev
 * Author URI: http://rageorgiev.com
 * Plugin URI: https://github.com/RadoslavGeorgiev/acf-code-helper
 * Version 0.1.0
 * Description: Allows you to register fields by using PHP code, but without needing to specify a field key nor use it for conditional logic.
 */

/**
 * Handles the logic behind the plugin.
 * 
 * @since 0.1.0
 */
class ACF_Code_Helper {
	/**
	 * Creates an instance of the class.
	 * 
	 * @since 0.1.0
	 * @var ACF_Code_Helper
	 */
	public static function instance() {
		static $instance;

		if( is_null( $instance ) ) {
			$instance = new self;
		}

		return $instance;
	}

	/**
	 * Includes the necessary files and triggers the necessary hooks.
	 *
	 * @since 0.1.0
	 */
	protected function __construct() {
		include_once( 'class-acf-group-location.php' );
		include_once( 'class-acf-group.php' );
		include_once( 'examples.php' );

		add_action( 'after_setup_theme', array( $this, 'initialize' ) );
	}

	/**
	 * Triggerst he neccessary hooks for initializing fields.
	 * 
	 * @since 0.1.0
	 */
	public function initialize() {
		do_action( 'register_acf_groups' );
	}
}

# Instantiate the class
ACF_Code_Helper::instance();