<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class SSP_Sharer {

  public function __construct() {
    add_action( 'storefront_single_post', array( $this, 'template' ), 50 );
    add_action( 'woocommerce_share', array ( $this, 'template' ) );
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
  }

  public function enqueue_scripts() {
    if ( is_single() || is_product() ) {
      wp_register_style( 'ssp-sharer-style', SSP_URL . 'assets/css/sharer.css' );
		  wp_enqueue_style( 'ssp-sharer-style' );
	  }
  }

  public function template() {
    if ( ! is_single() && ! is_product() ) return;
    $sharers = array(
      array(
        'slug' => 'facebook',
        'url'  => 'https://www.facebook.com/sharer/sharer.php?u='
      ),
      array(
        'slug' => 'twitter',
        'url'  => 'https://twitter.com/intent/tweet?url='
      ),
      array(
        'slug' => 'google+',
        'url'  => 'https://plus.google.com/share?url='
      )
    );
    apply_filters( 'ssp_sharer_list', $sharers );
    if ( ! is_array( $sharers ) || ! isset( $sharers ) ) return;
    
    echo '<ul class="ssp-sharer">';

    $sharers_number = count( $sharers );
    $sharers_count = 0;
    foreach ( $sharers as $sharer ) {
      $sharers_count ++;
      $first = $sharers_count === 1 ? ' first' : '';

      echo '<li class="ssp-sharer-' . esc_attr( $sharer['slug'] ) . '-button' . $first . '">';
      echo '<a class="ssp-sharer-' . esc_attr( $sharer['slug'] ) . '-link" rel="nofollow" href="' . esc_url( $sharer['url'] ) . get_the_permalink() . '" title="' . esc_attr__( 'Share on', 'ssp' ) . ' ' . esc_attr( ucfirst( $sharer['slug'] ) ) . '..." target="_blank"><span>' . esc_html( ucfirst( $sharer['slug'] ) ) . '</span></a>';
      echo '</li>';
    }
    echo '</ul>';
  }
}
