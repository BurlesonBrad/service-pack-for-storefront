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
    $page_url_facebook   = isset( $options['social_network']['facebook'] ) ? $options['social_network']['facebook'] : null;
    $page_url_twitter    = isset( $options['social_network']['twitter'] ) ? $options['social_network']['twitter'] : null;
    $page_url_googleplus = isset( $options['social_network']['googleplus'] ) ? $options['social_network']['googleplus'] : null;
    $page_url_instagram  = isset( $options['social_network']['instagram'] ) ? $options['social_network']['instagram'] : null;
    $page_url_youtube    = isset( $options['social_network']['youtube'] ) ? $options['social_network']['youtube'] : null;

    echo $args['before_widget'];
		echo $args['before_title'];
		echo apply_filters( 'widget_title', $instance['title'] );
		echo $args['after_title']; ?>
		
		<div class="ssp-widget-social-link">
      <ul class="ssp-widget-social-link-list"><?php
        if ( isset( $page_url_facebook ) ) { ?>
          <li><a class="ssp-widget-social-link-facebook" rel="external" href="<?php echo esc_url( $page_url_facebook ); ?>" target="_blank"></a></li><?php
        }
        if ( isset( $page_url_twitter ) ) { ?>
          <li><a class="ssp-widget-social-link-twitter" rel="external" href="<?php echo esc_url( $page_url_twitter ); ?>" target="_blank"></a></li><?php
        }
        if ( isset( $page_url_googleplus ) ) { ?>
          <li><a class="ssp-widget-social-link-google-plus" rel="external" href="<?php echo esc_url( $page_url_googleplus ); ?>" target="_blank"></a></li><?php
        }
        if ( isset( $page_url_instagram ) ) { ?>
          <li><a class="ssp-widget-social-link-instagram" rel="external" href="<?php echo esc_url( $page_url_instagram ); ?>" target="_blank"></a></li><?php
        }
        if ( isset( $page_url_youtube ) ) { ?>
          <li><a class="ssp-widget-social-link-youtube" rel="external" href="<?php echo esc_url( $page_url_youtube ); ?>" target="_blank"></a></li><?php
        } ?>
        <li><a class="ssp-widget-social-link-feed" rel="alternate" href="<?php echo esc_url( get_bloginfo( 'url' ) ) . '/feed'; ?>" target="_blank"></a></li>
      </ul>
		</div><?php
		
		echo $args['after_widget'];
  }

  public function enqueue_scripts() {
    wp_register_style( 'ssp-widget-social-link-style', SSP_URL . 'assets/css/widget-social-link.css' );
    wp_enqueue_style( 'ssp-widget-social-link-style' );
  }
}
