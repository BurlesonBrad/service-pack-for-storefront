<?php

/**
 * Plugin Name: Service Pack for Storefront
 * Description: Adds modulable functionalities and SEO improvements to your WooCommerce/Storefront site.
 * Version: 0.0.1
 * Author: opportus
 * Text Domain: service-pack-for-storefront
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

define( 'SPFS_VERSION', '0.0.1' );
define( 'SPFS_DIR', plugin_dir_path( __FILE__ ) . '/' );
define( 'SPFS_URL', plugins_url( '/', __FILE__ ) );

require_once( SPFS_DIR . 'class-spfs.php' );

// Get a singleton instance of the main plugin class.
$SPFS = SPFS::get_instance();

register_activation_hook( __FILE__, array( $SPFS, 'init_db' ) );
// Will use uninstall hook later.
register_deactivation_hook( __FILE__, array( $SPFS, 'clean_db' ) );
