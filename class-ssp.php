<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class SSP {

  public function __construct() {
    $this->init_modules();
    $this->init_widgets();
    $this->init_settings();
  }

  private function init_modules() {
    
    $options = get_option( 'ssp_settings' );
    $enabled = $options['modules_activation'];
    
    if ( isset( $enabled['aggregator'] ) ) {
      include_once( SSP_DIR . 'modules/class-aggregator.php' );
      new SSP_Aggregator();
    }
    if ( isset( $enabled['contact_form'] ) ) {
      include_once( SSP_DIR . 'modules/class-contact-form.php' );
      new SSP_Contact_Form();
    }
    if ( isset( $enabled['data_structurer'] ) ) {
      include_once( SSP_DIR . 'modules/class-data-structurer.php' );
      new SSP_Data_Structurer();
    }
    if ( isset( $enabled['dynamic_sidebar'] ) ) {
      include_once( SSP_DIR . 'modules/class-dynamic-sidebar.php' );
      new SSP_Dynamic_Sidebar();
    }
    if ( isset( $enabled['float_menu'] ) ) {
      include_once( SSP_DIR . 'modules/class-float-menu.php' );
      new SSP_Float_Menu();
    }
    if ( isset( $enabled['order_tracking'] ) ) {
      include_once( SSP_DIR . 'modules/class-order-tracking.php' );
      new SSP_Order_Tracking();
    }
    if ( isset( $enabled['sharer'] ) ) {
      include_once( SSP_DIR . 'modules/class-sharer.php' );
      new SSP_Sharer();
    }
    if ( isset( $enabled['slider'] ) ) {
      include_once( SSP_DIR . 'modules/class-slider.php' );
      new SSP_Slider();
    }
    if ( isset( $enabled['store_credit'] ) ) {
      include_once( SSP_DIR . 'modules/class-store-credit.php' );
      new SSP_Store_Credit();
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

  private function init_settings() {
    include_once( SSP_DIR . 'admin/class-settings.php' );
    new SSP_Settings();
  }

  public function init_db() {
    global $wpdb;
    $options = array(
      'modules_activation' => array(
        'aggregator'       => 1,
        'contact_form'     => 1,
        'data_structurer'  => 1,
        'dynamic_sidebar'  => 1,
        'float_menu'       => 1,
        'order_tracking'   => 1,
        'sharer'           => 1,
        'slider'           => 1,
        'store_credit'     => 1
      )
    );
    $wpdb->query( "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}ssp_email_list (id INT AUTO_INCREMENT PRIMARY KEY, email VARCHAR(255) NOT NULL, subscription DATETIME NOT NULL);" );
    add_option( 'ssp_settings', $options );
  }

  public function clean_db() {
    global $wpdb;
    $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}ssp_email_list" );
    if ( count( $results ) === 0 ) {
      $wpdb->query( "DROP TABLE {$wpdb->prefix}ssp_email_list" );
    }
    delete_option( 'ssp_settings' );
    // Also have to delete order post meta from the "Tracking Order" module...
  }
}
