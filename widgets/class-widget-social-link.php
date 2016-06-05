<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class SSP_Widget_Social_Link extends WP_Widget {
  
  public function __construct() {
    parent::__construct( 'ssp_widget_social_link', 'Social Link', array( 'description' => 'Storefront SP Social Link Widget' ) );
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
  }

	public function form( $instance ) {
    $title = isset( $instance['title'] ) ? $instance['title'] : '';
		echo '<p><label for="' . esc_attr( $this->get_field_name( 'title' ) ) . '">' . esc_html__( 'Title:' ) . '</label>';
		echo '<input class="widefat" id="' . esc_attr( $this->get_field_id( 'title' ) ) . '" name="' . esc_attr( $this->get_field_name( 'title' ) ) . '" type="text" value="' . esc_attr( $title ) . '" /></p>';
	}
	
	public function widget( $args, $instance ) {
    $options = get_option( 'ssp_settings' );

    echo $args['before_widget'];
		echo $args['before_title'];
		echo $instance['title'];
		echo $args['after_title'];
		
    echo '<div class="ssp-widget-social-link">';
    echo '<ul class="ssp-widget-social-link-list">';
    foreach ( $options['social_network'] as $network => $url ) {
      if ( ! is_null( $url ) ) {
        echo '<li><a class="ssp-widget-social-link-' . $network . '" rel="external" href="' . esc_url( $url ) .'" target="_blank"></a></li>';
      }
    }
    echo '</ul>';
    echo '</div>';
		echo $args['after_widget'];
  }

  public function enqueue_scripts() {
    wp_register_style( 'ssp-widget-social-link-style', SSP_URL . 'assets/css/widget-social-link.min.css' );
    wp_enqueue_style( 'ssp-widget-social-link-style' );
  }
}
