<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class SSP_Widget_Newsletter extends WP_Widget {
  
  public function __construct() {
    parent::__construct( 'ssp_widget_newsletter', 'Newsletter', array( 'description' => __( 'Newsletter subscription widget.' ) ) );
		add_action( 'wp_ajax_ssp_widget_newsletter_save_email', array( $this, 'save_email' ) );
		add_action( 'wp_ajax_nopriv_ssp_widget_newsletter_save_email', array( $this, 'save_email' ) );
	  add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) )	;
	}

	public function form( $instance ) {
    $title = isset( $instance['title'] ) ? $instance['title'] : '';
		echo '<p><label for="' . esc_attr( $this->get_field_name( 'title' ) ) . '">' . _e( 'Title:' ) . '</label>';
		echo '<input class="widefat" id="' . esc_attr( $this->get_field_id( 'title' ) ) . '" name="' . esc_attr( $this->get_field_name( 'title' ) ) . '" type="text" value="' . esc_attr( $title ) . '" /></p>';
	}
	
	public function widget( $args, $instance ) {
    echo $args['before_widget'];
		echo $args['before_title'];
		echo apply_filters( 'widget_title', $instance['title'] );
		echo $args['after_title']; ?>
		
    <form class="ssp-widget-newsletter">
      <input name="ssp_widget_newsletter_email" placeholder="<?php esc_attr_e( 'Your email address', 'ssp' ); ?>" type="email" class="ssp-widget-newsletter-email" />
      <input type="hidden" name="action" value="ssp_widget_newsletter_save_email" />
			<?php wp_nonce_field( 'ssp_widget_newsletter_nonce', 'security' ); ?>
			<button type="submit" class="ssp-widget-newsletter-send"></button>
		</form><?php
			
		echo $args['after_widget'];
  }
  
  public function save_email() {
    check_ajax_referer( 'ssp_widget_newsletter_nonce', 'security' );
    
    $errors = new WP_Error;
    // Response Messages
    $response_message = array(
		  'too_long_email'    => 'The maximum length of you email address is 50 characters.',
		  'invalid_email'     => 'Invalid email address.',
		  'missing_email'     => 'Don\'t forget your email address...',
		  'already_subcribed' => 'You are already subcribed to our newsletter...',
		  'insertion_failure' => 'Sorry but you can not subscribe to our newsletter because something not predicted happened, please try again.',
		  'success'           => 'Thank you for subscribing to our newsletter !'
    );
    if ( isset( $_POST['ssp_widget_newsletter_email'] ) && ! empty( $_POST['ssp_widget_newsletter_email'] ) ) {
			$email = $_POST['ssp_widget_newsletter_email'];
			// Validation & Sanitazation
      if ( strlen( $email ) <= 50 ) {
      	if ( preg_match( '#^[a-z0-9_.-]+@[a-z0-9_.-]{2,}\.[a-z]{2,4}$#', $email ) ) {
          $valid_email = sanitize_email( $email );
					global $wpdb;
					$row = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}ssp_email_list WHERE email = '$valid_email'" );
          if ( is_null( $row ) ) {
            if ( ! $insertion_success = $wpdb->insert( "{$wpdb->prefix}ssp_email_list", array( 'email' => $valid_email, 'subscription' => current_time( 'mysql' ) ) ) ) {
					    $errors->add( 'insertion_failure', __( $response_message['insertion_failure'], 'ssp' ) );
						}
					}
					else {
						$errors->add( 'already_subscribed', __( $response_message['already_subcribed'], 'ssp' ) );
					}
				}
				else {
					$errors->add( 'invalid_email', __( $response_message['invalid_email'], 'ssp' ) );
				}
			}
			else {
				$errors->add( 'too_long_email', __( $response_message['too_long_email'], 'ssp' ) );
			}
		}
		else {
			$errors->add( 'missing_email', __( $response_message['missing_email'], 'ssp' ) );
		}
    // AJAX Response
		$error_detected = $errors->get_error_code();
    if ( empty( $error_detected ) ) {
      wp_send_json_success( esc_html__( $response_message['success'], 'ssp' ) );
		}
		else if ( ! empty( $error_detected ) ) {
      foreach ( $errors->get_error_messages() as $error_message ) {
        $error .= $error_message . ' ';
			}
			wp_send_json_error( esc_html( $error ) );
		}
		exit;
	}

  public function enqueue_scripts() {
    wp_enqueue_style( 'ssp-widget-newsletter-style', SSP_URL . 'assets/css/widget-newsletter.css' );
		wp_enqueue_script( 'ssp-widget-newsletter-script', SSP_URL . 'assets/js/widget-newsletter.js', array( 'jquery' ) );
		wp_localize_script( 'ssp-widget-newsletter-script', 'ssp_widget_newsletter_ajax', array( 'url' => admin_url( 'admin-ajax.php' ) ) );
  }
}
