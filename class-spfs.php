<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class SPFS {

  private static $instance;

  public static function get_instance() {
    if ( is_null( self::$instance ) ) {
      self::$instance = new SPFS();
    }
    return self::$instance;
  }

  private function __construct() {
    $this->init_settings();
    $this->init_modules();
    $this->init_widgets();
  }

  private function init_settings() {
    if ( is_admin() ) {
      include_once( SPFS_DIR . 'admin/class-spfs-settings.php' );
      SPFS_Settings::get_instance();
    }
  }

  private function init_modules() {
    $options = get_option( 'spfs_settings' );
    $modules = $options['modules_activation'];
    if ( ! isset( $modules ) || ! is_array( $modules ) ) return;
    foreach ( $modules as $module => $activation ) {
      if ( $activation ) {
        include_once( SPFS_DIR . 'modules/class-spfs-' . str_replace( '_', '-', $module ) . '.php' );
        $class = 'SPFS_' . ucwords( $module, '_' );
        new $class;
      }
    }
  }

  private function init_widgets() {
    add_action( 'widgets_init', array( $this, 'register_widgets' ) );
  }

  public function register_widgets() {
    include_once( SPFS_DIR . 'widgets/class-spfs-widget-facebook-page.php' );
    include_once( SPFS_DIR . 'widgets/class-spfs-widget-newsletter.php' );
    include_once( SPFS_DIR . 'widgets/class-spfs-widget-social-link.php' );
    register_widget( 'SPFS_Widget_Facebook_Page' );
    register_widget( 'SPFS_Widget_Newsletter' );
    register_widget( 'SPFS_Widget_Social_Link' );
  }

  public function init_db() {
    global $wpdb;
    $settings = SPFS_Settings::get_instance();
    $options = array();
    foreach ( $settings->get_settings_fields() as $field => $setting ) {
      $section = substr( $setting['section'], 14 );
      if ( $section === 'modules_activation' ) {
        $options[$section][$field] = 1;
      }
      else {
        $options[$section][$field] = null;
      }
    }
    add_option( 'spfs_settings', $options );
    $wpdb->query( "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}spfs_email_list (id INT AUTO_INCREMENT PRIMARY KEY, email VARCHAR(255) NOT NULL, subscription DATETIME NOT NULL);" );
  }

  public function clean_db() {
    global $wpdb;
    $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}spfs_email_list" );
    if ( count( $results ) === 0 ) {
      $wpdb->query( "DROP TABLE {$wpdb->prefix}spfs_email_list" );
    }
    delete_option( 'spfs_settings' );
    // TODO: delete order post meta from the "Tracking Order" module...
  }
}
