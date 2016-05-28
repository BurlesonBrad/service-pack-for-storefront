<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class SSP_Settings {

  private $settings_fields = array(
    'aggregator'     => array(
      'name'         => 'Aggregator',
      'type'         => 'checkbox',
      'section'      => 'ssp_settings_modules_activation',
      'description'  => 'Aggregates the last blog posts on home page and product reviews in product category pages.'
    ),
    'contact_form'   => array(
      'name'         => 'Contact Form',
      'type'         => 'checkbox',
      'section'      => 'ssp_settings_modules_activation',
      'description'  => 'Simple front end contact form. Once activated, add the shortcode on your contact page: [ssp_contact_form]'
    ),
    'data_structurer'=> array(
      'name'         => 'Data Structurer',
      'type'         => 'checkbox',
      'section'      => 'ssp_settings_modules_activation',
      'description'  => 'Valid all your pages in Google Structured Data and so improve your SEO...'
    ),
    'dynamic_sidebar'=> array(
      'name'         => 'Dynamic Sidebar',
      'type'         => 'checkbox',
      'section'      => 'ssp_settings_modules_activation',
      'description'  => 'Adds specific sidebar for product page, product category page, post page, etc...'
    ),
    'float_menu'     => array(
      'name'         => 'Float Menu',
      'type'         => 'checkbox',
      'section'      => 'ssp_settings_modules_activation',
      'description'  => 'Makes the basic storefront navigation menu floating when scrolling down.'
    ),
    'order_tracking' => array(
      'name'         => 'Order Tracking',
      'type'         => 'checkbox',
      'section'      => 'ssp_settings_modules_activation',
      'description'  => 'Gives you and your customers the ability to track simply orders via links pointing to the shipper\'s site tracking service.'
    ),
    'sharer'         => array(
      'name'         => 'Sharer',
      'type'         => 'checkbox',
      'section'      => 'ssp_settings_modules_activation',
      'description'  => 'Brings to your customers the possibility to share easily products and blog posts on their social network accounts.'
    ),
    'slider'         => array(
      'name'         => 'Slider',
      'type'         => 'checkbox',
      'section'      => 'ssp_settings_modules_activation',
      'description'  => 'Simple slider based on "Flex Slider" from WooThemes. Edit slides in the new menu section.'
    ),
    'store_credit'   => array(
      'name'         => 'Store Credit',
      'type'         => 'checkbox',
      'section'      => 'ssp_settings_modules_activation',
      'description'  => 'Gives you the ability to create and send by email store credit to your customers.'
    ),
    'facebook'       => array(
      'name'         => 'Facebook',
      'type'         => 'text',
      'section'      => 'ssp_settings_social_network'
    ),
    'twitter'        => array(
      'name'         => 'Twitter',
      'type'         => 'text',
      'section'      => 'ssp_settings_social_network'
    ),
    'googleplus'     => array(
      'name'         => 'Google +',
      'type'         => 'text',
      'section'      => 'ssp_settings_social_network'
    ),
    'instagram'      => array(
      'name'         => 'Instagram',
      'type'         => 'text',
      'section'      => 'ssp_settings_social_network'
    ),
    'youtube'        => array(
      'name'         => 'YouTube',
      'type'         => 'text',
      'section'      => 'ssp_settings_social_network'
    ),
    'my_account'     => array(
			'name'         => 'My account',
			'type'         => 'checkbox',
      'section'      => 'ssp_settings_store_credit',
      'description'  => 'Show credits on My Account page.',
      'modulable'    => 'store_credit'
		),
		'after_use'      => array(
			'name'         => 'Delete after use',
      'type'         => 'checkbox',
      'section'      => 'ssp_settings_store_credit',
      'description'  => 'When the credit is spent, delete it.',
      'modulable'    => 'store_credit'
		),
		'before_tax'	   => array(
			'name'         => 'Apply before taxes',
      'type'         => 'checkbox',
      'section'      => 'ssp_settings_store_credit',
			'description'  => 'Apply the credit before taxes.',
      'modulable'    => 'store_credit'
    ),
    'individual_use' => array(
      'name'         => 'Individual usage',
			'type'         => 'checkbox',
      'section'      => 'ssp_settings_store_credit',
      'modulable'    => 'store_credit'
    ),
    'shipper_name_0' => array(
      'name'         => 'Shipper\'s Name',
      'type'         => 'text',
      'section'      => 'ssp_settings_order_tracking',
      'description'  => 'Add the name of your choice for identifying the new shipper.',
      'modulable'    => 'order_tracking'
    ),
    'shipper_url_0'  => array(
      'name'         => 'Shipper\'s URL',
      'type'         => 'text',
      'section'      => 'ssp_settings_order_tracking',
      'description'  => 'Add the new shipper\'s tracking service URL. Eg for FEDEX: http://www.fedex.com/Tracking?action=track&tracknumbers=<br />
                        More examples <a href="http://verysimple.com/2011/07/06/ups-tracking-url/" rel="nofollow" target="_blank">here</a>.',
      'modulable'    => 'order_tracking'
    )
  );

  private $settings_sections = array(
    'modules_activation' => array(
      'name'             => 'Modules Activation',
      'description'      => 'Enable/Disable the modules below...'
    ),
    'social_network'     => array(
      'name'             => 'Social Network',
      'description'      => 'Your social network pages URLs...'
    ),
    'store_credit'       => array(
      'name'             => 'Module - Store Credit',
      'description'      => 'Store credit settings',
      'modulable'        => 'store_credit'
    ),
    'order_tracking'     => array(
      'name'             => 'Module - Order Tracking',
      'description'      => 'In this section, you can add new shippers and/or edit the shippers previously created.<br />
                            Once the shippers created, go on the "Edit Order" page, in the new "Order Tracking" metabox on the right panel, select the shipper\'s name and enter your tracking number.<br />
                            Now, your customers have the possibility to track their orders with a simple click from their account page or from their updated order status email they will get.',
      'modulable'        => 'order_tracking'
    )
  );

  private static $instance;

  public static function get_instance() {
    if ( is_null( self::$instance ) ) {
      self::$instance = new SSP_Settings();
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
    add_action( 'admin_menu', array( $this, 'init_options_page' ) );
    add_action( 'admin_init', array( $this, 'init_settings_sections' ) );
    add_action( 'admin_init', array( $this, 'init_settings_fields' ) );
  }

  public function init_options_page() {
    add_options_page(
      'Storefront SP',
      'Storefront SP',
      'manage_options',
      'ssp_settings_page',
      array( $this, 'settings_page_template' )
    );
  }

  public function init_settings_sections() {
    $options = get_option( 'ssp_settings' );
    foreach ( $this->settings_sections as $section => $setting ) {
      if ( ! isset( $setting['modulable'] ) || isset( $setting['modulable'] ) && isset( $options['modules_activation'][$setting['modulable']] ) ) {
        add_settings_section(
          'ssp_settings' . '_' . $section,
          __( $setting['name'], 'ssp' ),
          array( $this, 'settings_sections_template' ),
          'ssp_settings_page'
        );
      }
    }
  }

  public function init_settings_fields() {
    register_setting(
      'ssp_settings_group',
      'ssp_settings',
      array( $this, 'sanitization' )
    );
    $options = get_option( 'ssp_settings' );
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
          $this->settings_fields = array_merge( $this->settings_fields, $new_fields );
          $field_number ++;
        }
      }
    }
    foreach ( $this->settings_fields as $field => $setting ) {
      // Add the field if it doesn't concern a particular module OR if this module is activated
      if ( ! isset( $setting['modulable'] ) || isset( $setting['modulable'] ) && isset( $options['modules_activation'][$setting['modulable']] ) ) {
        $section = substr( $setting['section'], 13 );
        $value = isset( $options[$section][$field] ) ? $options[$section][$field] : null;
        add_settings_field(
          $slug = $setting['section'] . '_' . $field,
          __( $setting['name'], 'ssp' ),
          array( $this, 'settings_fields_template' ),
          'ssp_settings_page',
          $setting['section'],
          array(
          'section'   => $section,
          'field'     => $field,
          'slug'      => $slug,
          'value'     => $value,
          'type'      => $setting['type'],
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
    echo '<h1>' . esc_html__( 'Storefront Service Pack Settings', 'ssp' ) . '</h1>';
    echo '<form method="POST" action="options.php">';
    do_settings_sections( 'ssp_settings_page' );
    submit_button();
    settings_fields( 'ssp_settings_group' );
    echo '</form>';
  }

  public function settings_sections_template( $args ) {
    $section = substr( $args['id'], 13 );
    echo '<p>' . __( $this->settings_sections[$section]['description'], 'ssp' ) . '</p>';
  }

  public function settings_fields_template( $args ) {
    $section       = $args['section'];
    $field         = $args['field'];
    $slug          = $args['slug'];
    $name          = 'ssp_settings[' . $section . '][' . $field . ']';
    $type          = $args['type'];
    $value         = $args['value'];
    $description   = isset( $this->settings_fields[$field]['description'] ) ? $this->settings_fields[$field]['description'] : false;
    
    if ( $type === 'checkbox' ) {
      echo '<input type="hidden" name="' . $name . '" value="0">';
      echo '<input type="' . $type . '" id="' . $slug . '" name="' . $name . '" value="1" ' . checked( 1, $value, false ) . ' />';
      if ( ! empty( $description ) ) {
        echo '<label for="' . $slug . '">' . __( $description, 'ssp' ) . '</label>';
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
        echo '<p class="description">' . __( $description, 'ssp' ) . '</p>';
      }
    }
  }
}
