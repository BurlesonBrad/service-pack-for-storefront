<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class SPFS_Settings {

  private static $instance;
  private $settings_fields;
  private $settings_sections;

  public static function get_instance() {
    if ( is_null( self::$instance ) ) {
      self::$instance = new SPFS_Settings();
    }
    return self::$instance;
  }

  public function get_settings_fields() {
    return $this->settings_fields;
  }
  
  public function get_settings_sections() {
    return $this->settings_sections;
  }
  
  private function __construct() {
    $this->set_settings_fields();
    $this->set_settings_sections();
    add_action( 'admin_menu', array( $this, 'init_options_page' ) );
    add_action( 'admin_init', array( $this, 'init_settings_sections' ) );
    add_action( 'admin_init', array( $this, 'init_settings_fields' ) );
  }

  private function set_settings_fields( $dynamic_fields = false ) {
    $settings_fields = array(
      'aggregator'     => array(
        'name'         => __( 'Aggregator', 'service-pack-for-storefront' ),
        'type'         => 'checkbox',
        'section'      => 'spfs_settings_modules_activation',
        'description'  => __( 'Aggregates the last blog posts on home page and product reviews in product category pages.', 'service-pack-for-storefront' ),
        'require'      => 'storefront'
      ),
      'contact_form'   => array(
        'name'         => __( 'Contact Form', 'service-pack-for-storefront' ),
        'type'         => 'checkbox',
        'section'      => 'spfs_settings_modules_activation',
        'description'  => __( 'Simple front end contact form. Once activated, add its shortcode on your "contact" page: [spfs_contact_form]', 'service-pack-for-storefront' )
      ),
      'dynamic_sidebar'=> array(
        'name'         => __( 'Dynamic Sidebar', 'service-pack-for-storefront' ),
        'type'         => 'checkbox',
        'section'      => 'spfs_settings_modules_activation',
        'description'  => __( 'Adds specific sidebar for product page, product category page, post page, etc...', 'service-pack-for-storefront' ),
        'require'      => 'storefront'
      ),
      'float_menu'     => array(
        'name'         => __( 'Float Menu', 'service-pack-for-storefront' ),
        'type'         => 'checkbox',
        'section'      => 'spfs_settings_modules_activation',
        'description'  => __( 'Makes the basic storefront navigation menu floating when scrolling down.', 'service-pack-for-storefront' ),
        'require'      => 'storefront'
      ),
      'order_tracking' => array(
        'name'         => __( 'Order Tracking', 'service-pack-for-storefront' ),
        'type'         => 'checkbox',
        'section'      => 'spfs_settings_modules_activation',
        'description'  => __( 'Gives you and your customers the ability to track simply orders via links pointing to the shipper\'s site tracking service.', 'service-pack-for-storefront' ),
        'require'      => 'woocommerce'
      ),
      'sharer'         => array(
        'name'         => __( 'Sharer', 'service-pack-for-storefront' ),
        'type'         => 'checkbox',
        'section'      => 'spfs_settings_modules_activation',
        'description'  => __( 'Brings to your customers the possibility to share easily products and blog posts on their social network accounts.', 'service-pack-for-storefront' ),
        'require'      => 'storefront'
      ),
      'slider'         => array(
        'name'         => __( 'Slider', 'service-pack-for-storefront' ),
        'type'         => 'checkbox',
        'section'      => 'spfs_settings_modules_activation',
        'description'  => __( 'Simple slider based on "Flex Slider" by WooThemes. Edit slides in the new menu section.', 'service-pack-for-storefront' )
      ),
      'store_credit'   => array(
        'name'         => __( 'Store Credit', 'service-pack-for-storefront' ),
        'type'         => 'checkbox',
        'section'      => 'spfs_settings_modules_activation',
        'description'  => __( 'Gives you the ability to create and send by email store credits to your customers.', 'service-pack-for-storefront' ),
        'require'      => 'woocommerce'
      ),
      'facebook'       => array(
        'name'         => 'Facebook',
        'type'         => 'text',
        'section'      => 'spfs_settings_social_network'
      ),
      'twitter'        => array(
        'name'         => 'Twitter',
        'type'         => 'text',
        'section'      => 'spfs_settings_social_network'
      ),
      'googleplus'     => array(
        'name'         => 'Google +',
        'type'         => 'text',
        'section'      => 'spfs_settings_social_network'
      ),
      'instagram'      => array(
        'name'         => 'Instagram',
        'type'         => 'text',
        'section'      => 'spfs_settings_social_network'
      ),
      'youtube'        => array(
        'name'         => 'YouTube',
        'type'         => 'text',
        'section'      => 'spfs_settings_social_network'
      ),
      'my_account'     => array(
			  'name'         => __( 'My account', 'service-pack-for-storefront' ),
			  'type'         => 'checkbox',
        'section'      => 'spfs_settings_store_credit',
        'description'  => __( 'Show credits on My Account page.', 'service-pack-for-storefront' ),
        'modulable'    => 'store_credit'
		  ),
		  'after_use'      => array(
			  'name'         => __( 'Delete after use', 'service-pack-for-storefront' ),
        'type'         => 'checkbox',
        'section'      => 'spfs_settings_store_credit',
        'description'  => __( 'When the credit is spent, delete it.', 'service-pack-for-storefront' ),
        'modulable'    => 'store_credit'
		  ),
		  'before_tax'	   => array(
			  'name'         => __( 'Apply before taxes', 'service-pack-for-storefront' ),
        'type'         => 'checkbox',
        'section'      => 'spfs_settings_store_credit',
			  'description'  => __( 'Apply the credit before taxes.', 'service-pack-for-storefront' ),
        'modulable'    => 'store_credit'
      ),
      'individual_use' => array(
        'name'         => __( 'Individual usage', 'service-pack-for-storefront' ),
			  'type'         => 'checkbox',
        'section'      => 'spfs_settings_store_credit',
        'modulable'    => 'store_credit'
      ),
      'shipper_name_0' => array(
        'name'         => __( 'Shipper\'s Name', 'service-pack-for-storefront' ),
        'type'         => 'text',
        'section'      => 'spfs_settings_order_tracking',
        'description'  => __( 'Enter the name of the new shipper. Note that it will be displayed as such to your customer.', 'service-pack-for-storefront' ),
        'modulable'    => 'order_tracking'
      ),
      'shipper_url_0'  => array(
        'name'         => __( 'Shipper\'s URL', 'service-pack-for-storefront' ),
        'type'         => 'text',
        'section'      => 'spfs_settings_order_tracking',
        'description'  => __( 'Add the new shipper\'s tracking service URL. Eg for FEDEX: http://www.fedex.com/Tracking?action=track&tracknumbers=<br />More examples <a href="http://verysimple.com/2011/07/06/ups-tracking-url/" rel="nofollow" target="_blank">here</a>.', 'service-pack-for-storefront' ),
        'modulable'    => 'order_tracking'
      )
    );
    if ( is_null( $this->settings_fields ) ) {
      return $this->settings_fields = $settings_fields;
    }
    elseif ( $dynamic_fields ) {
      return $this->settings_fields += $dynamic_fields;
    }
  }

  private function set_settings_sections() {
    $settings_sections = array(
      'modules_activation' => array(
        'name'             => __( 'Modules Activation', 'service-pack-for-storefront' ),
        'description'      => __( 'Enable/Disable the modules below...', 'service-pack-for-storefront' )
      ),
      'social_network'     => array(
        'name'             => __( 'Social Network', 'service-pack-for-storefront' ),
        'description'      => __( 'Your social network pages URLs...', 'service-pack-for-storefront' )
      ),
      'store_credit'       => array(
        'name'             => __( 'Module - Store Credit', 'service-pack-for-storefront' ),
        'description'      => __( 'Store credit settings', 'service-pack-for-storefront' ),
        'modulable'        => 'store_credit'
      ),
      'order_tracking'     => array(
        'name'             => __( 'Module - Order Tracking', 'service-pack-for-storefront' ),
        'description'      => __( 'In this section, you can add new shippers and/or edit the shippers previously created.<br />Once the shippers created, go on the "Edit Order" page, in the new "Order Tracking" metabox on the right panel, select the shipper\'s name and enter your tracking number.<br />Now, your customers have the possibility to track their orders with a simple click from their account page or from their updated order status email they will get.', 'service-pack-for-storefront' ),
        'modulable'        => 'order_tracking'
      )
    );
    if ( is_null( $this->settings_sections ) ) {
      return $this->settings_sections = $settings_sections;
    }
  }
 
  public function init_options_page() {
    add_options_page(
      __( 'Service Pack for Storefront', 'service-pack-for-storefront' ),
      __( 'Service Pack for Storefront', 'service-pack-for-storefront' ),
      'manage_options',
      'spfs_settings_page',
      array( $this, 'settings_page_template' )
    );
  }

  public function init_settings_sections() {
    $options = get_option( 'spfs_settings' );
    foreach ( $this->settings_sections as $section => $setting ) {
      if ( ! isset( $setting['modulable'] ) || isset( $setting['modulable'] ) && isset( $options['modules_activation'][$setting['modulable']] ) ) {
        add_settings_section(
          'spfs_settings' . '_' . $section,
          $setting['name'],
          array( $this, 'settings_sections_template' ),
          'spfs_settings_page'
        );
      }
    }
  }

  public function init_settings_fields() {
    register_setting(
      'spfs_settings_group',
      'spfs_settings',
      array( $this, 'sanitization' )
    );
    $options = get_option( 'spfs_settings' );
    // Add dynamically new pair of setting fields for the 'order_tracking' option group
    if ( isset( $options['order_tracking'] ) ) {
      $shippers = preg_grep( '#^shipper_name#', array_keys( $options['order_tracking'] ) );
      $shipper_number = 0;
      foreach( $shippers as $shipper ) {
        if ( ! is_null( $options['order_tracking'][$shipper] ) ) {
          $shipper_number ++;
        }
      }
      if ( $shipper_number ) {
        $field_number = 1;
        while ( $field_number <= $shipper_number ) {
          $new_fields = array(
            'shipper_name_' . $field_number => $this->settings_fields['shipper_name_0'],
            'shipper_url_' . $field_number  => $this->settings_fields['shipper_url_0']
          );
          $this->set_settings_fields( $new_fields );
          $field_number ++;
        }
      }
    }
    foreach ( $this->settings_fields as $field => $setting ) {
      // Add the field if it doesn't concern a particular module OR if this module is activated
      if ( ! isset( $setting['modulable'] ) || isset( $setting['modulable'] ) && isset( $options['modules_activation'][$setting['modulable']] ) ) {
        $section = substr( $setting['section'], 14 );
        $value = isset( $options[$section][$field] ) ? $options[$section][$field] : null;
        add_settings_field(
          $slug = $setting['section'] . '_' . $field,
          $setting['name'],
          array( $this, 'settings_fields_template' ),
          'spfs_settings_page',
          $setting['section'],
          array(
          'section'   => $section,
          'field'     => $field,
          'slug'      => $slug,
          'value'     => $value,
          'type'      => $setting['type'],
          'require'   => isset( $setting['require'] ) ? $setting['require'] : false,
          'label_for' => $slug
          )
        );
      }
    }
  }

  public function sanitization( $input ) {
    foreach ( $input as $section => $fields ) {
      if ( ! isset( $this->settings_sections[$section] ) ) {
        return;
      }
      foreach ( $fields as $field => $setting ) {
        if ( ! isset( $this->settings_fields[$field] ) ) {
          // Try to get the base slug of dynamically added fields without the ID at the end, eg: shipper_name_1, shipper_url_1, etc...
          $key = substr( $field, 0, -2 ) . '_0';
          // TODO test it...
          if ( empty( array_keys( $this->settings_fields, $key ) ) ) {
            return;
          }
        }
        if ( ! empty( $setting ) ) {
          $output[$section][$field] = sanitize_text_field( stripslashes( $setting ) );
        }
        else {
          $output[$section][$field] = null;
        }
      }
    }
    return $output;
  }

  public function settings_page_template() {
    echo '<h1>' . esc_html__( 'Service Pack for Storefront', 'service-pack-for-storefront' ) . '</h1>';
    echo '<form method="POST" action="options.php">';
    do_settings_sections( 'spfs_settings_page' );
    submit_button();
    settings_fields( 'spfs_settings_group' );
    echo '</form>';
  }

  public function settings_sections_template( $args ) {
    $section = substr( $args['id'], 14 );
    echo '<p>' . $this->settings_sections[$section]['description'] . '</p>';
  }

  public function settings_fields_template( $args ) {
    $missing_dependency = SPFS::get_instance()->is_missing_dependency( $args['require'] );
    $section            = $args['section'];
    $field              = $args['field'];
    $slug               = $args['slug'];
    $name               = 'spfs_settings[' . $section . '][' . $field . ']';
    $type               = $args['type'];
    $require            = $args['require'] ? ucfirst( $args['require'] ) : false;
    $readonly           = $missing_dependency ? 'readonly onclick="return false"' : '';
    $value              = $missing_dependency ? 0 : $args['value'];
    $description        = isset( $this->settings_fields[$field]['description'] ) ? $this->settings_fields[$field]['description'] : false;

    if ( $type === 'checkbox' ) {
      echo '<input type="hidden" name="' . $name . '" value="0">';
      echo '<input type="' . $type . '" id="' . $slug . '" name="' . $name . '" value="1" ' . checked( 1, $value, false ) . $readonly . ' />';
      if ( ! empty( $description ) ) {
        echo '<label for="' . $slug . '">' . $description . $requirement = $require ? ' <strong>' . sprintf( __( 'Require %s.', 'service-pack-for-storefront' ), $require ) . '</strong>' : '' . '</label>';
      }
    }
    elseif ( $type === 'text' ) {
      $special = null;
      $style   = '';
      if ( $section === 'order_tracking' && ! empty( $value ) ) {
        $special = true;
        $style = ' style="background-color: #f2f2f2"';
      }
      echo '<input type="' . $type . '" id="' . $slug . '" name="' . $name . '" value="' . esc_attr( $value ) . '" class="regular-text"' . $style . ' />';
      if ( ! empty( $description ) && ! $special ) {
        echo '<p class="description">' . $description . '</p>';
      }
    }
  }
}
