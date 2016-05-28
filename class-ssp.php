<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class SSP {

  private static $instance;

  public static function get_instance() {
    if ( is_null( self::$instance ) ) {
      self::$instance = new SSP();
    }
    return self::$instance;
  }

  private function __construct() {
    $this->init_settings();
    $this->init_modules();
    $this->init_widgets();
  }

  private function init_settings() {
    include_once( SSP_DIR . 'admin/class-settings.php' );
    SSP_Settings::get_instance();
  }

  private function init_modules() {
    $options = get_option( 'ssp_settings' );
    $modules = $options['modules_activation'];
    if ( ! isset( $modules ) || ! is_array( $modules ) ) return;
    foreach ( $modules as $module => $activation ) {
      if ( $activation ) {
        include_once( SSP_DIR . 'modules/class-' . str_replace( '_', '-', $module ) . '.php' );
        $class = 'SSP_' . ucwords( $module, '_' );
        new $class;
      }
    }
  }

  private function init_widgets() {
    add_action( 'widgets_init', array( $this, 'register_widgets' ) );
  }

  public function register_widgets() {
    include_once( SSP_DIR . 'widgets/class-widget-facebook-page.php' );
    include_once( SSP_DIR . 'widgets/class-widget-newsletter.php' );
    include_once( SSP_DIR . 'widgets/class-widget-social-link.php' );
    register_widget( 'SSP_Widget_Facebook_Page' );
    register_widget( 'SSP_Widget_Newsletter' );
    register_widget( 'SSP_Widget_Social_Link' );
  }

  public function init_db() {
    global $wpdb;
    $settings = SSP_Settings::get_instance();
    $options = array();
    foreach ( $settings->get_settings_fields() as $field => $setting ) {
      $section = substr( $setting['section'], 13 );
      if ( $section === 'modules_activation' ) {
        $options[$section][$field] = 1;
      }
      else {
        $options[$section][$field] = null;
      }
    }
    add_option( 'ssp_settings', $options );
    $wpdb->query( "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}ssp_email_list (id INT AUTO_INCREMENT PRIMARY KEY, email VARCHAR(255) NOT NULL, subscription DATETIME NOT NULL);" );
  }

  public function clean_db() {
    global $wpdb;
    $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}ssp_email_list" );
    if ( count( $results ) === 0 ) {
      $wpdb->query( "DROP TABLE {$wpdb->prefix}ssp_email_list" );
    }
    delete_option( 'ssp_settings' );
    // TODO: delete order post meta from the "Tracking Order" module...
  }
}
