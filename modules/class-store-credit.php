<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class SSP_Store_Credit {

  private $store_credit_options = null;

  public function __construct() {
    $this->init_store_credit_options();
    add_filter( 'woocommerce_coupon_discount_types', array( $this, 'init_discount_type' ) );
    add_action( 'admin_menu', array( $this, 'init_submenu_page_store_credit_send' ), 10 );
    add_action( 'load-woocommerce_page_ssp_store_credit_send', array( $this, 'handler_store_credit_send' ) );
    add_action( 'admin_notices', array( $this, 'admin_notice_store_credit_send' ) );
    add_filter( 'woocommerce_coupon_is_valid', array( $this, 'coupon_is_valid' ), 10, 2 );
		add_action( 'woocommerce_before_my_account', array( $this, 'store_credit_my_account_template' ), 20 );
		add_filter( 'woocommerce_coupon_is_valid_for_cart', array( $this, 'coupon_is_valid_for_cart' ), 10, 2 );
		add_action( 'woocommerce_new_order', array( $this, 'update_credit_amount' ), 9 );
		add_filter( 'woocommerce_coupon_get_discount_amount', array( $this, 'coupon_get_discount_amount' ), 10, 5 );
		add_filter( 'woocommerce_cart_totals_coupon_label', array( $this, 'cart_totals_coupon_label' ), 10, 2 );
  }

  private function init_store_credit_options() {
    $options = get_option( 'ssp_settings' );
    if ( ! isset( $options['store_credit'] ) ) return;
    $this->store_credit_options = $options['store_credit'];
  }

  public function init_discount_type( $discount_types ) {
    $discount_types['ssp_store_credit'] = __( 'Store Credit', 'ssp' );
		return $discount_types;
  }

  public function init_submenu_page_store_credit_send() {
    add_submenu_page(
      'woocommerce',
      __( 'Send Credits', 'ssp' ),
      __( 'Send Credits', 'ssp' ) ,
      'manage_woocommerce',
      'ssp_store_credit_send',
      array( $this, 'submenu_page_store_credit_send_template' )
    );
	}
  
  public function submenu_page_store_credit_send_template() {
    echo '<div class="wrap">';
		echo '<div id="icon-woocommerce" class="icon32 icon32-posts-shop_coupon"><br></div>';
		echo '<h2>' . esc_html__( 'Send Credits', 'ssp' ) . '</h2>';
		echo '<form method="post">';
		echo '<table class="form-table">';
		echo '<tr valign="top">';
		echo '<th scope="row">' . esc_html__( 'Email address', 'ssp' ) . '</th>';
	  echo '<td><input id="ssp_store_credit_send_email" name="ssp_store_credit_send_email" class="regular-text" /></td>';
		echo '</tr>';
		echo '<tr valign="top">';
		echo '<th scope="row">' . esc_html__( 'Amount of the credit', 'ssp' ) . '</th>';
	  echo '<td><input id="ssp_store_credit_send_amount" name="ssp_store_credit_send_amount" class="regular-text" placeholder="0.00" /></td>';
		echo '</tr>';
    echo '</table>';
    submit_button( esc_html__( 'Generate a store credit and mail it to your customer', 'ssp' ) );
		echo '</div>';
	}
  
  public function handler_store_credit_send() {
    if ( ! isset( $_POST['ssp_store_credit_send_email'] ) ) return;
    $email  = sanitize_email( $_POST[ 'ssp_store_credit_send_email'] );
		$amount = sanitize_text_field( $_POST['ssp_store_credit_send_amount'] );
    if ( ! is_email( $email ) ) {
      $error_message = esc_html__( 'Invalid email address', 'ssp' );
    }
    if ( ! is_numeric( $amount ) || $amount < 1 ) {
      $error_message = isset( $error_message ) ? $error_message . '<br />' : '';
      $error_message .= esc_html__( 'Invalid amount', 'ssp' );
    }
    if ( isset( $error_message ) ) {
      set_transient( 'ssp_store_credit_send_error', $error_message, 60 );
      return;
    }
    $coupon_code   = uniqid( sanitize_email( $email ) . '-' );
    $coupon_id = wp_insert_post(
      array(
			  'post_title'   => $coupon_code,
			  'post_content' => '',
			  'post_status'  => 'publish',
			  'post_author'  => 1,
			  'post_type'    => 'shop_coupon'
      )
    );
		update_post_meta( $coupon_id, 'discount_type', 'ssp_store_credit' );
		update_post_meta( $coupon_id, 'coupon_amount', $amount );
		update_post_meta( $coupon_id, 'individual_use', $this->store_credit_options['individual_use'] );
		update_post_meta( $coupon_id, 'product_ids', '' );
		update_post_meta( $coupon_id, 'exclude_product_ids', '' );
		update_post_meta( $coupon_id, 'usage_limit', '' );
		update_post_meta( $coupon_id, 'expiry_date', '' );
		update_post_meta( $coupon_id, 'apply_before_tax', $this->store_credit_options['before_tax'] );
		update_post_meta( $coupon_id, 'free_shipping', 'no' );
		update_post_meta( $coupon_id, 'customer_email', array( $email ) );
    $this->email_store_credit_send( $email, $coupon_code, $amount );
    set_transient( 'ssp_store_credit_send_success', __( 'Credit sent.', 'ssp' ), 60 );
  }

  public function admin_notice_store_credit_send() {
    if ( $success_message = get_transient( 'ssp_store_credit_send_success' ) ) {
      echo '<div id="message" class="updated fade"><p><strong>' . $success_message . '</strong></p></div>';
      delete_transient( 'ssp_store_credit_send_success' );
      return;
    }
    if ( $error_message = get_transient( 'ssp_store_credit_send_error' ) ) {
      echo '<div id="message" class="error fade"><p><strong>' . $error_message . '</strong></p></div>';
      delete_transient( 'ssp_store_credit_send_error' );
    }
  }
   
  private function email_store_credit_send( $email, $coupon_code, $amount ) {
    $mailer        = WC()->mailer();
		$blogname      = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		$subject       = sprintf( '[%s] %s', $blogname, __( 'Store Credit', 'ssp' ) );
		$email_heading =  sprintf( __( 'You have got a credit of %s ', 'ssp' ), woocommerce_price( $amount ) );
		ob_start();
		$this->send_store_credit_email_template( $email_heading, $coupon_code );
	  $message = ob_get_clean();
		$mailer->send( $email, $subject, $message );
  }

  private function send_store_credit_email_template( $email_heading, $coupon_code ) {
    do_action( 'woocommerce_email_header', $email_heading );
    echo '<p>' . esc_html__( 'To use your store credit, please, enter the following discount code during your next checkout', 'ssp' ) . '.</p>';
    echo '<strong style="margin: 10px 0; font-size: 18px; font-weight: bold; display: block; text-align: center;">' . esc_html( $coupon_code ) . '</strong>';
    echo '<div style="clear:both;"></div>';
    do_action( 'woocommerce_email_footer' );
  }

	public function store_credit_my_account_template() {
		if ( $coupons = $this->get_customer_credit() ) {
			echo '<h2>' . esc_html__( 'My Store Credits', 'ssp' ) . '</h2>';
			echo '<ul class="store-credit">';
		  foreach ( $coupons as $code ) {
				$coupon = new WC_Coupon( $code->post_title );
				if ( 'ssp_store_credit' === $coupon->type || $coupon->is_store_credit ) {
					echo '<li><strong>' . $coupon->code . '</strong> &mdash;' . wc_price( $coupon->amount ) . '</li>';
				}
			}
			echo '</ul>';
		}
	}

  private function get_customer_credit() {
    if ( ! $this->store_credit_options['my_account'] ) return;
		$user = wp_get_current_user();
		$args = array(
			'post_type'      => 'shop_coupon',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'meta_query'     => array(
				array(
					'key'     => 'customer_email',
					'value'   => $user->user_email,
					'compare' => 'LIKE'
				),
				array(
					'key'     => 'coupon_amount',
					'value'   => '0',
					'compare' => '>=',
					'type'    => 'NUMERIC'
				)
			)
		);
		return get_posts( $args );
	}

	public function coupon_is_valid( $valid, $coupon ) {
    if ( $valid && ( 'ssp_store_credit' === $coupon->type || $coupon->is_store_credit ) && $coupon->amount <= 0 ) {
			wc_add_notice( esc_html__( 'Your credit is over.', 'ssp' ), 'error' );
			return false;
		}
		return $valid;
	}

  public function coupon_is_valid_for_cart( $valid, $coupon ) {
    if ( ( 'ssp_store_credit' === $coupon->type || $coupon->is_store_credit ) ) {
			return true;
		}
		return $valid;
	}

	public function update_credit_amount() {
    if ( $coupons = WC()->cart->get_coupons() ) {
			foreach ( $coupons as $code => $coupon ) {
				if ( ( 'ssp_store_credit' === $coupon->type || $coupon->is_store_credit ) ) {
					$credit_remaining = max( 0, ( $coupon->amount - WC()->cart->coupon_discount_amounts[ $code ] ) );
					if ( $credit_remaining <= 0 && $this->store_credit_options['after_usage'] ) {
						wp_delete_post( $coupon->id );
					}
					else {
						update_post_meta( $coupon->id, 'coupon_amount', $credit_remaining );
					}
				}
			}
		}
	}
  
  public function coupon_get_discount_amount( $discount, $discounting_amount, $cart_item, $single, $coupon ) {
    if ( ( 'ssp_store_credit' === $coupon->type || $coupon->is_store_credit ) && ! is_null( $cart_item ) ) {
			$discount_percent = 0;
			if ( WC()->cart->subtotal_ex_tax ) {
				$discount_percent = ( $cart_item['data']->get_price_excluding_tax() * $cart_item['quantity'] ) / WC()->cart->subtotal_ex_tax;
			}
			$discount = min( ( $coupon->amount * $discount_percent ) / $cart_item['quantity'], $discounting_amount );
		}
		elseif ( ( 'ssp_store_credit' === $coupon->type || $coupon->is_store_credit ) ) {
			$discount = min( $coupon->amount, $discounting_amount );
		}
		return $discount;
	}
  
  public function cart_totals_coupon_label( $label, $coupon ) {
    if ( ( 'ssp_store_credit' === $coupon->type || $coupon->is_store_credit ) ) {
			$label = __( 'Store Credit', 'ssp' );
		}
		return $label;
	}
}
