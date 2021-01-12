<?php
/**
 * Plugin Name:     WordPress HighLevel Connector
 * Plugin URI:      https://github.com/mwender/wp-highlevel-connector
 * Description:     Provides various connections between WordPress and HighLevel CRM
 * Author:          Michael Wender
 * Author URI:      https://mwender.com
 * Text Domain:     highlevel-connector
 * Domain Path:     /languages
 * Version:         1.1.0
 *
 * @package         Highlevel_Connector
 */

// Include required files
require_once( 'assets/fns/the-events-calendar.php' );
require_once( 'assets/fns/notices.php' );

// Include Composer packages
/*
if( ! file_exists( plugin_dir_path( __FILE__ ) . 'vendor/autoload.php' ) ){
  add_action( 'admin_notices', 'WPHLC\\notices\\run_composer_install_notice' );
} else {
  require_once( 'vendor/autoload.php' );
}
*/

// Utility function for debuging:
if( ! function_exists( 'uber_log' ) ){
  /**
   * Enhanced logging.
   *
   * @param      string  $message  The log message
   */
  function uber_log( $message = null ){
    static $counter = 1;

    $bt = debug_backtrace();
    $caller = array_shift( $bt );

    if( 1 == $counter )
      error_log( "\n\n" . str_repeat('-', 25 ) . ' STARTING DEBUG [' . date('h:i:sa', current_time('timestamp') ) . '] ' . str_repeat('-', 25 ) . "\n\n" );
    error_log( "\n" . $counter . '. ' . basename( $caller['file'] ) . '::' . $caller['line'] . "\n" . $message . "\n---\n" );
    $counter++;
  }
}