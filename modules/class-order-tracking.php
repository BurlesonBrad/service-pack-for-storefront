<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class SSP_Order_Tracking {

  private $order_tracking_options = null;
  private $tracking_shipper = null;
  private $tracking_number  = null;
	
  public function __construct() {
    $this->init_order_tracking_options();
    add_filter( 'is_protected_meta', array( $this, 'hide' ), 10, 2 );
    add_action( 'save_post', array( $this, 'save' ) );
    add_action( 'admin_notices', array( $this, 'admin_notice' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'woocommerce_email_order_meta', array( $this, 'email_template' ) );
		add_action( 'woocommerce_before_my_account', array( $this, 'frontend_template' ), 20 );
  }

  /**
   * Grep serialized 'order_tracking' options and return them in a nicely structured array.
   * Pretty complicated for what it has to do... I believe there are better implementations or approches.
   * Feel free to PR it if you have !
   *
   * The option we're grepping are structured this way:
   * 
   * 'ssp_settings' = array(
   *   'order_tracking' => array(
   *     'shipper_name_0' => 'Foobar Shipper',
   *     'shipper_url_0'  => 'http://www.foobar.com/tracking-service?url=',
   *     'shipper_name_1' => null,
   *     'shipper_url_1'  => null,
   *   )
   * )
   * NOTE: The pair of option fields are added dynamically when a new shipper is found.
   * For more info, check SSP_Settings.
   *
   * We're attemting to return the options this way:
   *
   * 'foobar_shipper' = array(
   *   'name'         => 'Foobar Shipper',
   *   'url'          => 'http://www.foobar.com/tracking-service?url='
   * )
   */
   private function init_order_tracking_options() {
    $options = get_option( 'ssp_settings' );
    $shippers_options =  isset( $options['order_tracking'] ) ? $options['order_tracking'] : null;
    $shippers_tmp = array(); // Temporary shippers array classified by their ID.
    $shippers = array(); // Futur shippers array which will have their name (if available) as key. Else by thier ID.
    $number = 0;

    if ( ! isset( $shippers_options ) ) return;
    foreach ( $shippers_options as $key => $value ) {
      list( , $option, $id ) = explode( '_', $key );
      $shippers_tmp[$id][$option] = isset( $value ) ? $value : null;
      if ( $option === 'name' ) {
        // If shipper's name has not been set, set his ID as key.
        if ( ! isset( $value ) ) {
          $new_key = $id;
        }
        // Else, his name value as key.
        else {
          $new_key = str_replace( ' ', '_', strtolower( $value ) );
          // We have a shipper.
          $any_shipper = true;
        }
        $shippers[$new_key] = null;
      }
    }
    // Check if we've got a shipper.
    if ( ! isset( $any_shipper ) ) {
      return;
    }
    // Change shippers keys by their new ID.
    foreach ( $shippers as $key => $value ) {
      $shippers[$key] = $shippers_tmp[$number];
      $number ++;
    }
    $this->order_tracking_options = $shippers;
  }

  private function init_tracking_meta( $post_ID = null ) {
    if ( is_null( $post_ID ) ) {
      global $post;
      $post_ID = $post->ID;
    }
    $tracking_shipper = ! empty( $post_meta = get_post_meta( $post_ID, 'ssp_order_tracking_shipper', true ) ) ? $post_meta : null;
    $tracking_number  = ! empty( $post_meta = get_post_meta( $post_ID, 'ssp_order_tracking_number', true ) ) ? $post_meta : null;

    if ( ! array_key_exists( $tracking_shipper, $this->order_tracking_options ) ) {
      delete_post_meta( $post_ID, 'ssp_order_tracking_shipper', $tracking_shipper );
      delete_post_meta( $post_ID, 'ssp_order_tracking_number', $tracking_number );
    }
    $this->tracking_shipper = ! empty( $post_meta = get_post_meta( $post_ID, 'ssp_order_tracking_shipper', true ) ) ? $post_meta : null;
    $this->tracking_number  = ! empty( $post_meta = get_post_meta( $post_ID, 'ssp_order_tracking_number', true ) ) ? $post_meta : null;
  }

  public function add_meta_boxes() {
    add_meta_box(
      'ssp_order_tracking',
      __( 'Order Tracking', 'ssp' ),
      array( $this, 'meta_box' ),
      'shop_order',
      'side',
      'default'
    );
	}

  public function meta_box() {
    if ( ! isset( $this->order_tracking_options ) ) {
      echo '<p>' . esc_html__( 'You first need to add new shippers on' ) . ' <a href="' . esc_url( admin_url( 'options-general.php?page=ssp_settings_page' ) ) . '">' . 'Storefront SP ' . esc_html__( 'settings page', 'ssp' ) . '</a></p>';
      return;
    }
    $this->init_tracking_meta();
    echo '<p class="description">' . esc_html__( 'Select the shipper, enter your tracking number and save', 'ssp' ) . '...</p>';
    echo '<p><label for="ssp_order_tracking_shipper">' . esc_html__( 'Shipper' ) . '</label /><br />';
    echo '<select id="ssp_order_tracking_shipper" name="ssp_order_tracking_shipper">';
    echo '<option value="">' . esc_html__( 'Select the shipper', 'ssp' ) . '</option>';
    
    foreach ( $this->order_tracking_options as $key => $value ) {
      if ( ! is_int( $key ) ) {
        $selected = ( $this->tracking_shipper === $key ) ? 'selected ' : '';
        echo '<option ' . $selected . 'value="' . esc_attr( $key ) . '">' . esc_html( $value['name'] ) . '</option>';
      }
    }
    echo '</select></p>';
    echo '<p><label for="ssp_order_tracking_number">' . esc_html__( 'Tracking number', 'ssp' ) . '</label>';
    echo '<input type="text" id="ssp_order_tracking_number" name="ssp_order_tracking_number" value="' . esc_attr( $this->tracking_number ) . '" /></p>';
    
    if ( isset( $this->tracking_number ) && isset( $this->tracking_shipper ) ) {
      echo '<a href="' . esc_url( $this->order_tracking_options[$this->tracking_shipper]['url'] . $this->tracking_number ) . '" rel="nofollow" target="_blank">' . esc_html__( 'Track it', 'ssp' ) . '</a>';
    }
  }
	
  public function save( $post_ID ) {
    if ( empty( $_POST['ssp_order_tracking_shipper'] ) || empty( $_POST['ssp_order_tracking_number'] ) || ! current_user_can( 'edit_post', $post_ID ) ) {
      return;
    }
    $track_shipper = sanitize_text_field( $_POST['ssp_order_tracking_shipper'] );
    $track_number = sanitize_text_field( $_POST['ssp_order_tracking_number'] );
    
    if ( preg_match( '#^[a-z0-9_]{2,20}$#i', $track_shipper ) && preg_match( '#^[a-z0-9]{5,50}$#i', $track_number ) ) {
      update_post_meta( $post_ID, 'ssp_order_tracking_shipper', $track_shipper );
      update_post_meta( $post_ID, 'ssp_order_tracking_number', $track_number );
    }
    else set_transient( 'ssp_order_tracking_error', true, 60 );
  }

  public function admin_notice() {
    if ( get_transient( 'ssp_order_tracking_error' ) ) {
      echo '<div class="updated error notice is-dismissible">';
		  echo '<p>' . esc_html__( 'Invalid / Missing tracking number or tracking shipper', 'ssp' ) . '.</p>';
      echo '</div>';
      delete_transient( 'ssp_order_tracking_error' );
    }
	}

  public function email_template() {
    $this->init_tracking_meta();
    
    if ( ! isset( $this->tracking_shipper ) || ! isset( $this->tracking_number ) ) return;
    $html  = '<h3>' . esc_html__( 'Tracking information', 'ssp' ) . '</h3>';
    $html .= '<p>' . esc_html__( 'You can track anytime your order by clicking', 'ssp' ) . ' <a href="' . esc_url( $this->order_tracking_options[$this->tracking_shipper]['url'] . $this->tracking_number ) . '" target="_blank">' . esc_html__( 'here' ) . '</a>.</p>';
    
    echo apply_filters( 'ssp_order_tracking_email_template', $html );
	}	

  public function frontend_template() {
    $customer_post_orders = $this->get_customer_post_orders();
    if ( ! isset( $customer_post_orders ) ) return;
    ob_start();
    
    foreach ( $customer_post_orders as $post_order ) {
      $order = new WC_Order( $post_order->ID );
			$this->init_tracking_meta( $post_order->ID );
      if ( isset( $this->tracking_shipper ) && isset( $this->tracking_number ) ) {
        $loop  = '<li>';
			  $loop .= '<strong>' . esc_html__( 'Order N°', 'ssp' ) . ' ' . esc_html( $order->get_order_number() ) . '</strong><br />';
			  $loop .= esc_html__( 'Sent by', 'ssp' ) . ' ' . esc_html( $this->order_tracking_options[$this->tracking_shipper]['name'] ) . '<br />';
        $loop .= esc_html__( 'Tracking number', 'ssp' ) . ': '. esc_html( $this->tracking_number ) . '<br />';
		    $loop .= esc_html__( 'You can track anytime you order by clicking', 'ssp' ) . ' ' . '<a href="' . esc_url( $this->order_tracking_options[$this->tracking_shipper]['url'] . $this->tracking_number ) . '" rel="nofollow" target="_blank">' . esc_html__( 'here', 'ssp' ) . '</a>.';
        $loop .= '</li>';

        echo apply_filters( 'ssp_order_tracking_frontend_loop', $loop );
		  }
    }
    $orders = ob_get_clean();
    if ( ! $orders ) return;
    echo '<h2>' . esc_html__( 'My trackings', 'ssp' ) . '</h2>';
    echo '<ul>';
    echo $orders;
    echo '</ul>';
	}
  	
  private function get_customer_post_orders() {
		$user = wp_get_current_user();
		$args = array(
			'post_type'      => 'shop_order',
			'post_status'    => 'completed',
			'posts_per_page' => -1,
			'meta_value'   => $user->user_email,
		);
		return get_posts( $args );
  }

  public function hide( $protected, $meta_key ) {
    if ( 'ssp_order_tracking_shipper' === $meta_key || 'ssp_order_tracking_number' === $meta_key ) {
      return true;
		}
		return $protected;
  }
}
