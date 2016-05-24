<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class SSP_Sharer {

  public function __construct() {
    add_action( 'storefront_single_post', array( $this, 'template' ) );
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
    if ( is_single() || is_product() ) { ?>
      <ul class="ssp-sharer">
        <li class="ssp-sharer-facebook-button">
          <a class="ssp-sharer-facebook-link" rel="nofollow" href="https://www.facebook.com/sharer/sharer.php?u=<?php esc_url( the_permalink() ) ?>" title="<?php esc_attr_e( 'Share on Facebook', 'ssp' ); ?> ..." target="_blank"><span>Facebook</span></a>
        </li>
        <li class="ssp-sharer-twitter-button">
          <a class="ssp-sharer-twitter-link" rel="nofollow" href="https://twitter.com/intent/tweet?url=<?php esc_url( the_permalink() ) ?>" title="<?php esc_attr_e( 'Share on Twitter', 'ssp' ); ?> ..." target="_blank"><span>Twitter</span></a>
        </li>
        <li class="ssp-sharer-googleplus-button">
          <a class="ssp-sharer-googleplus-link" rel="nofollow" href="https://plus.google.com/share?url=<?php esc_url( the_permalink() ) ?>" title="<?php esc_attr_e( 'Share on Google+', 'ssp' ); ?> ..." target="_blank"><span>Google+</span></a>
        </li>
		  </ul><?php
	  }
  }
}
