<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class SSP_Data_Structurer {

  private $post_data = '';
  private $post_category_data = '';
  private $product_data = '';
  private $product_category_data = '';

  public function __construct() {
    add_action( 'wp_footer', array( $this, 'insert_post_category_data' ) );
    add_action( 'storefront_loop_post', array( $this, 'init_post_category_data' ) );
    add_action( 'wp_footer', array( $this, 'insert_post_data' ) );
    add_action( 'storefront_single_post', array( $this, 'init_post_data' ) );
    add_action( 'wp_footer', array( $this, 'insert_product_category_data' ) );
    add_action( 'woocommerce_before_shop_loop_item', array( $this, 'init_product_category_data' ) );  
  }

  // Insert Structured Data...

  public function insert_post_data() {
    if ( ! is_single() || is_product() ) return;
    echo '<script type="application/ld+json">{ "@context": "http://schema.org",' . $this->post_data . '}</script>';
  }
  
  public function insert_post_category_data() {
    if ( ! is_home() && ! is_category() && ! is_date() ) return;
    echo '<script type="application/ld+json">{ "@context": "http://schema.org/", "@graph": [ ' . rtrim( $this->post_category_data, ',' ) . ' ] }</script>';
  }
  
  public function insert_product_category_data() {
    if ( ! is_product_category() ) return;
    echo '<script type="application/ld+json">{ "@context": "http://schema.org/", "@graph": [ ' . rtrim( $this->product_category_data, ',' ) . ' ] }</script>';
  }

  // Init Structured Data...

  public function init_post_category_data() {
    $this->init_post_data();
    $this->post_category_data .= '{ ' . $this->post_data . ' },';
  }

  public function init_product_category_data() {
    $this->init_product_data();
    $this->product_category_data .= '{ ' . $this->product_data . ' },';
  }

  public function init_post_data() {
    global $post;
    $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->id ), 'normal' );
    $logo  = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' );

    $json  = '"@type": "BlogPosting",';
    $json .= '"mainEntityOfPage": { "@type": "webpage", "@id": "' . get_the_permalink() . '" },';
    $json .= '"datePublished": "' . get_post_time( 'c' ) . '",';
    $json .= '"dateModified": "' . get_the_modified_date( 'c' ) . '",';
    $json .= '"name": "' . strip_tags( get_the_title() ) . '",';
    $json .= '"headline": "' . strip_tags( get_the_title() ) . '",';
    $json .= '"description": "' . strip_tags( get_the_excerpt() ) . '",';
    $json .= '"image": { "@type": "ImageObject", "url": "' . $image[0] . '", "width": "' . $image[1] . '", "height": "' . $image[2] . '" },';
    $json .= '"publisher": { "@type": "organization", "name": "' . get_bloginfo( 'name' ) . '", "logo": { "@type": "ImageObject", "url": "' . $logo[0] . '", "width": "' . $logo[1] . '", "height": "' . $logo[2] . '" } },';
    $json .= '"author": { "@type": "person", "name": "' . get_the_author() . '" }';

    $this->post_data = $json;
  }

  public function init_product_data() {
    global $product;
    
    $json  = '"@type": "Product",';
    $json .= '"name": "' . strip_tags( get_the_title() ) . '",';
    $json .= '"image": "' . wp_get_attachment_url( $product->get_image_id() ) . '",';
    $json .= '"description": "' . strip_tags( get_the_excerpt() ) . '",';
    $json .= '"sku": "' . strip_tags( $product->get_sku() ) . '",';
    $json .= '"brand": { "@type": "Thing", "name": "' . strip_tags( $product->get_attribute( __( 'brand', 'ssp' ) ) ) . '" },';
    $json .= $product->get_rating_count() ? '"aggregateRating": { "@type": "AggregateRating", "ratingValue": "' . $product->get_average_rating() . '", "reviewCount": "' . $product->get_rating_count() . '"},' : '';
    $json .= '"offers": { ';
    $json .= '"@type": "Offer",';
    $json .= '"priceCurrency": "' . get_woocommerce_currency() . '",';
    $json .= '"price": "' . $product->get_price() . '",';
    $json .= '"itemCondition": "http://schema.org/NewCondition",';
    $json .= '"availability": "http://schema.org/' . $stock = ( $product->is_in_stock() ? 'InStock' : 'OutOfStock' ) . '",';
    $json .= '"seller": { "@type": "Organization", "name": "' . strip_tags( get_bloginfo( 'name' ) ) . '" } }';

    $this->product_data = $json;
  }
}
