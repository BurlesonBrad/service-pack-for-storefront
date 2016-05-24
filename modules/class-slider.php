<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class SSP_Slider {

  private $slide_link_box = array( 
	  'id'              => 'ssp_slide_link',
	  'title'           => 'Slide Link',
	  'page'            => array( 'ssp_slide' ),
	  'context'         => 'normal',
	  'priority'        => 'default',
	  'fields'          => array(
		  array(
			  'name'        => 'Slide URL',
			  'desc'        => '',
			  'id'          => 'ssp_slide_url',
			  'class'       => 'ssp_slide_url',
			  'type'        => 'text',
			  'rich_editor' => 0,            
			  'max'         => 0             
		  ),
	  ),
  );

  public function __construct() {
    add_action( 'init', array( $this, 'register_slide_post_type' ) );
    add_action( 'woocommerce_before_main_content', array( $this, 'template' ), 30 );  
    add_action( 'save_post', array( $this, 'save' ) );
    add_action( 'add_meta_boxes_ssp_slide', array( $this, 'add_meta_boxes' ) );
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
  }

  public function enqueue_scripts() {
    if ( ! is_front_page() ) return;
    wp_register_style( 'ssp-slider-style', SSP_URL . 'assets/css/slider.css' );
    wp_register_script( 'ssp-slider-init-script', SSP_URL . 'assets/js/slider-init.js', array( 'jquery' ) );
	  wp_register_script( 'ssp-slider-script', SSP_URL . 'assets/js/slider.js', array( 'jquery' ), false, true );
    wp_enqueue_style( 'ssp-slider-style' );
		wp_enqueue_script( 'ssp-slider-init-script' );
		wp_enqueue_script( 'ssp-slider-script' );
  }
  
  public function register_slide_post_type() {
    $labels = array(
		  'name'              => _x( 'Slides', 'post type general name', 'ssp' ),
		  'singular_name'     => _x( 'Slide', 'post type singular name', 'ssp' ),
		  'add_new'           => __( 'Add New Slide', 'ssp' ),
	  	'add_new_item'      => __( 'Add New Slide', 'ssp' ),
		  'edit_item'         => __( 'Edit Slide', 'ssp' ),
		  'new_item'          => __( 'New Slide', 'ssp' ),
		  'view_item'         => __( 'View Slide', 'ssp' ),
		  'search_items'      => __( 'Search Slides', 'ssp' ),
		  'not_found'         => __( 'Slide', 'ssp' ),
		  'not_found_in_trash'=> __( 'Slide', 'ssp' ),
		  'parent_item_colon' => __( 'Slide', 'ssp' ),
		  'menu_name'         => __( 'Slides', 'ssp' )
	  );
    $taxonomies = array();
    $supports = array( 'title', 'thumbnail' );
    $post_type_args = array(
		  'labels'            => $labels,
		  'singular_label'    => __( 'Slide', 'ssp' ),
		  'public'            => true,
		  'show_ui'           => true,
		  'publicly_queryable'=> true,
		  'query_var'         => true,
		  'capability_type'   => 'post',
		  'has_archive'       => false,
		  'hierarchical'      => false,
		  'rewrite'           => array( 'slug' => 'ssp_slide', 'with_front' => false ),
		  'supports'          => $supports,
		  'menu_position'     => 27,
		  'menu_icon'         => 'dashicons-images-alt',
		  'taxonomies'        => $taxonomies
	  );
    register_post_type( 'ssp_slide', $post_type_args );
  }

  public function add_meta_boxes() {
    foreach ( $this->slide_link_box['page'] as $page ) {
      add_meta_box(
        $this->slide_link_box['id'],
			  $this->slide_link_box['title'],
			  array( $this, 'meta_box' ),
			  $page,
			  'normal',
			  'default'
		  );
    }
  }

  public function meta_box( $post )  {
    //echo '<input type="hidden" name="ssp_slide_link_box_nonce" value="' . wp_create_nonce( basename( __FILE__ ) ) . '" />';
    echo '<table class="form-table">';
    foreach ( $this->slide_link_box['fields'] as $field ) {
      $meta = get_post_meta( $post->ID, $field['id'], true );
      echo '<tr>';
      echo '<th style="width:20%"><label for="' . esc_attr( $field['id'] ) . '">' . esc_html( $field['name'] ) . '</label></th>';
		  echo '<td class="field_type_' . esc_attr( str_replace( ' ', '_', $field['type'] ) ) . '">';
      if ( $field['type'] === 'text' ) {
				echo '<input type="text" name="' . esc_attr( $field['id'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $meta ) . '" size="30" style="width:97%" /><br />' . esc_html( $field['desc'] );
		  }
      echo '</td>';
      echo '</tr>';
	  }
    echo '</table>';
    wp_nonce_field( 'ssp_slide_link_box_name', 'security' );
  }

  public function save( $post_id ) {
		if ( isset( $_POST['ssp_slide_link_box_nonce'] ) && ! wp_verify_nonce( $_POST['ssp_slide_link_box_nonce'], 'security' ) ) {
		  return $post_id;
	  }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		  return $post_id;
	  }
    if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
		  if ( ! current_user_can( 'edit_page', $post_id ) ) {
			  return $post_id;
		  }
	  }
    elseif ( ! current_user_can( 'edit_post', $post_id ) ) {
		  return $post_id;
	  }
    foreach ( $this->slide_link_box['fields'] as $field ) {
		  $old = get_post_meta( $post_id, $field['id'], true );
		  $new = isset( $_POST[$field['id']] ) ? sanitize_text_field( $_POST[$field['id']] ) : '';
      if ( $new && $new != $old ) {
				if ( $field['type'] == 'date' ) {
					$new = format_date( $new );
					update_post_meta( $post_id, $field['id'], $new );
				}
        else {
					if ( is_string( $new ) ) {
						$new = $new;
					}
					update_post_meta( $post_id, $field['id'], $new );
				}
			}
      elseif ( '' == $new && $old ) {
			  delete_post_meta( $post_id, $field['id'], $old );
		  }
	  } 
  }
  
  public function template() {
    if ( ! is_front_page() ) return;
    $args = array(
		  'post_type'      => 'ssp_slide',
		  'posts_per_page' => 5
	  );  
    $query = new WP_Query( $args );
    if ( $query->have_posts() ) {
      echo '<div class="flexslider">';
      echo '<ul class="slides">';
      while ( $query->have_posts() ) {
        $query->the_post();
        echo '<li>';
        if ( get_post_meta( get_the_id(), 'ssp_slide_url', true) != '' ) {
          echo '<a href="' . esc_url( get_post_meta( get_the_id(), 'ssp_slide_url', true ) ) . '">';
			  }
        echo the_post_thumbnail();
        if ( get_post_meta( get_the_id(), 'ssp_slide_url', true ) != '' ) {
          echo '</a>';
        }
        echo '</li>';
      }
      echo '</ul>';
      echo '</div>';
	  }
	  wp_reset_postdata();
  }
}
