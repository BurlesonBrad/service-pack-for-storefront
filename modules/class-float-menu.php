<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class SSP_Float_Menu {

  public function __construct() {
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
  }
  
  public function enqueue_scripts() {
    wp_register_script( 'ssp-float-menu-script', SSP_URL . 'assets/js/float-menu.min.js', array( 'jquery' ) );
    wp_enqueue_script( 'ssp-float-menu-script' );
    wp_register_style( 'ssp-float-menu-style', SSP_URL . 'assets/css/float-menu.min.css' );
    wp_enqueue_style( 'ssp-float-menu-style' );
  }
}
