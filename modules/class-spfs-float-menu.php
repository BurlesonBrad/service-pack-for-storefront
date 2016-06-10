<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class SPFS_Float_Menu {

  public function __construct() {
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
  }
  
  public function enqueue_scripts() {
    wp_register_script( 'spfs-float-menu-script', SPFS_URL . 'assets/js/float-menu.min.js', array( 'jquery' ) );
    wp_enqueue_script( 'spfs-float-menu-script' );
    wp_register_style( 'spfs-float-menu-style', SPFS_URL . 'assets/css/float-menu.min.css' );
    wp_enqueue_style( 'spfs-float-menu-style' );
  }
}
