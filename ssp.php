<?php

/**
 * Plugin Name: Storefront Service Pack
 * Description: Adds modulable functionalities and SEO improvements to your WooCommerce/Storefront site.
 * Version: 0.0.1
 * Author: opportus
 * Text Domain: ssp
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

define( 'SSP_VERSION', '0.0.1' );
define( 'SSP_DIR', plugin_dir_path( __FILE__ ) . '/' );
define( 'SSP_URL', plugins_url( '/', __FILE__ ) );

require_once( SSP_DIR . 'class-ssp.php' );

$SSP = SSP::get_instance();

register_activation_hook( __FILE__, array( $SSP, 'init_db' ) );
register_deactivation_hook( __FILE__, array( $SSP, 'clean_db' ) );
