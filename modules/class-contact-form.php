<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class SSP_Contact_Form {

  public function __construct() {
    add_shortcode( 'ssp_contact_form', array( $this, 'shortcode' ) );
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    add_action( 'wp_ajax_ssp_contact_form_handler', array( $this, 'handler' ) );
    add_action( 'wp_ajax_nopriv_ssp_contact_form_handler', array( $this, 'handler' ) );
  }

  public function enqueue_scripts() {
    if ( is_page( 'contact' ) ) {
      wp_register_style( 'ssp-contact-form-style', SSP_URL . 'assets/css/contact-form.css' );
		  wp_register_script( 'ssp-contact-form-script', SSP_URL . 'assets/js/contact-form.js', array( 'jquery' ) );
		  wp_enqueue_style( 'ssp-contact-form-style' );
		  wp_enqueue_script( 'ssp-contact-form-script' );
		  wp_localize_script( 'ssp-contact-form-script', 'ssp_contact_form_ajax', array( 'url' => admin_url( 'admin-ajax.php' ) ) );
    }
  }

  public function shortcode() {
    ob_start();
	  $this->template();
	  $contact_form = ob_get_clean();
	  return $contact_form;
  }

  private function template() { ?>
    <form id="ssp-contact-form">
			<div class="ssp-contact-form-panel-left">
        <label for="ssp-contact-form-email"><?php esc_html_e( 'Email', 'ssp' ); ?><abbr class="required" title="<?php esc_attr_e( 'Required', 'ssp' ); ?>"> *</abbr></label>
			  <input type="email" id="ssp-contact-form-email" name="ssp_contact_form_email" value="<?php if ( isset( $_POST['ssp_contact_form_email'] ) ) echo  esc_attr( $_POST['ssp_contact_form_email'] ); ?>" required />
        <label for="ssp-contact-form-name"><?php esc_html_e( 'Name', 'ssp' ); ?></label>
			  <input type="text" id="ssp-contact-form-name" name="ssp_contact_form_name" value="<?php if ( isset( $_POST['ssp_contact_form_name'] ) ) echo esc_attr( $_POST['ssp_contact_form_name'] ); ?>" />
      </div>
      <div class="ssp-contact-form-panel-rigth">
        <label for="ssp-contact-form-message"><?php esc_html_e( 'Message', 'ssp' ); ?><abbr class="required" title="<?php esc_attr_e( 'Required', 'ssp' ); ?>"> *</abbr></label>
			  <textarea id="ssp-contact-form-message" name="ssp_contact_form_message" rows="17" required><?php if ( isset( $_POST['ssp_contact_form_message'] ) ) echo esc_textarea( $_POST['ssp_contact_form_message'] ); ?></textarea>
      </div>
      
			<?php wp_nonce_field( 'ssp_contact_form_nonce', 'security' ); ?>
			<input type="hidden" name="action" value="ssp_contact_form_handler">
			<button type="submit" id="ssp-contact-form-send"></button>
	  </form><?php
  }

  public function handler() {
	  check_ajax_referer( 'ssp_contact_form_nonce', 'security' );
    
    $errors = new WP_Error();
	  $email = $_POST['ssp_contact_form_email'];
	  $message = $_POST['ssp_contact_form_message'];
	  $name = $_POST['ssp_contact_form_name'];
	  $valid_email = null;
	  $valid_message = null;
	  $valid_name = '?';
    $response_message = array(
	    too_long_email    => 'The max length for your email address is 30 characters.',
	    too_long_message  => 'The max length for your message is 2000 characters.',
	    too_long_name     => 'The max length for your name is 30 characters.',
      invalid_email     => 'Invalid email.',
	    invalid_message   => 'Invalid message.',
	    invalid_name      => 'Invalid name.',
      missing_email     => 'Don\'t forget your email address...',
	    missing_message   => 'Don\'t forget your message...',
      failure           => 'Your message has not been sent. Please, try again...',
      success           => 'Your message has successfully been sent. We will reply as soon as possible.'
    );
	  // Email Validation & Sanitization
    if ( ! empty( $email) ) {
      if ( strlen( $email ) <= 30 ) {
        if ( preg_match( '#^[a-z0-9_.-]+@[a-z0-9_.-]{2,}\.[a-z]{2,4}$#', $email ) ) {
          $valid_email = sanitize_email( $email );
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
    // Message Validation & Sanitization
	  if ( ! empty( $message ) ) {
      if ( strlen( $message ) <= 2000 ) {
        if ( preg_match( '#^.+$#s', $message ) ) {
          $valid_message = wp_kses( nl2br( $message ), array( 'br' => array() ) );
			  }
			  else {
          $errors->add( 'invalid_message', __( $response_message['invalid_message'], 'ssp' ) );
			  }
		  }
		  else {
        $errors->add( 'too_long_message', __( $response_message['too_long_message'], 'ssp' ) );
		  }
	  }
	  else {
      $errors->add( 'missing_message', __( $response_message['missing_message'], 'ssp' ) );
	  }
    // Name Validation & Sanitization
	  if ( ! empty( $name ) ) {  
      if ( strlen( $name ) <= 60 ) {
        if ( preg_match( '#^[a-zA-ZÀ-ÖØ-öø-ÿœŒ\s]+$#', $name ) ) {
          $valid_name = sanitize_text_field( $name );
				  $valid_message .= '<p>' . esc_html__( 'Name', 'ssp' )  . ': ' . esc_html( $valid_name ) . '</p>';
			  }
			  else {
          $errors->add( 'invalid_name', __( $response_message['invalid_name'], 'ssp' ) );
			  }
		  }
		  else {
        $errors->add( 'too_long_name', __( $response_message['too_long_name'], 'ssp' ) );
		  }
	  }
	  $error_detected = $errors->get_error_code();
	  // Mailer
	  if ( empty( $error_detected ) && ! empty( $valid_email ) && ! empty( $valid_message ) ) {
      // PHP Mailer Variables
      $mailer = WC()->mailer();
		  $to = get_option( 'admin_email' );
      $subject = esc_html__( 'You have got an email from', 'ssp' ) . ' ' . $valid_name;
		  $headers = array( 'From: ' . $valid_email,
			  'Reply-To: ' . $valid_email,
			  'Content-Type: text/html; charset=UTF-8'
      );
      ob_start();
      $this->email_template( $subject, $valid_message );
      $content = ob_get_clean();
      // Send Email
		  if ( ! $sent_message = $mailer->send( $to, $subject, $content, $headers ) ) {  
        $errors->add( 'failure', __( $response_message['failure'], 'ssp' ) );
		  }
	  }
	  // AJAX Response
	  $error_detected = $errors->get_error_code();
	  if ( empty( $error_detected ) ) {  
      wp_send_json_success( esc_html__( $response_message['success'], 'ssp' ) );
	  }
	  else if ( ! empty( $error_detected ) ) {
      $error = null;
      foreach ( $errors->get_error_messages() as $error_message ) {
        $error .= $error_message . ' ';
		  }
		  wp_send_json_error( esc_html( $error ) );
	  }
	  exit;
  }

  private function email_template( $subject, $valid_message ) {
    do_action( 'woocommerce_email_header', $subject );
    echo $valid_message;
    echo '<div style="clear:both;"></div>';
    do_action( 'woocommerce_email_footer' );
  }
}
