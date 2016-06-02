<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class SSP_Dynamic_Sidebar {

  public function __construct() {
    add_action( 'init', array( $this, 'storefront_remove_sidebar' ) );
    add_action( 'widgets_init', array( $this, 'register_sidebars' ) );
    add_action( 'storefront_sidebar', array( $this, 'sidebar_template' ), 50 );
    add_action( 'storefront_footer', array( $this, 'top_footer_template' ), 5 );
  }

  public function storefront_remove_sidebar() {
    remove_action( 'storefront_sidebar', 'storefront_get_sidebar', 10 );
  }

  public function sidebar_template() {
    $sidebar = null;

    if ( is_front_page() ) {
      $sidebar = 'sidebar-1';
    } elseif ( is_home() || is_category() || is_date() || is_single() && ! is_product() ) {
        $sidebar = 'sidebar-blog';
    } elseif ( is_product_category() || is_search() && is_woocommerce() ) {
        $sidebar = 'sidebar-product-category';
    } elseif ( is_product() ) {
        $sidebar = 'sidebar-product';
    } elseif ( is_page() && ! is_cart() && ! is_checkout() && ! is_account_page() ) {
        $sidebar = 'sidebar-page';
    } elseif ( is_cart() || is_checkout() || is_account_page() ) {
        $sidebar = 'sidebar-special';
    }
    apply_filters( 'ssp_dynamic_sidebar_template', $sidebar );

    if ( ! is_active_sidebar( $sidebar ) ) return;
    
    echo '<div id="secondary" class="widget-area" role="complementary">';
    dynamic_sidebar( $sidebar );
    echo '</div>';
  }
  
  public function top_footer_template() {
    $rows = apply_filters( 'ssp_dynamic_sidebar_top_footer_template_rows', 2 );
    $r = 0;
    
    while ( $r < $rows ) {
      $r++;
      if ( is_active_sidebar( 'top-footer-' . $r . '-2' ) ) {
			  $columns = 2;
		  } elseif ( is_active_sidebar( 'top-footer-' . $r . '-1' ) ) {
			    $columns = 1;
		  } else {
			    $columns = 0;
      }
      apply_filters( 'ssp_dynamic_sidebar_top_footer_template_columns', $columns );

      if ( $columns > 0 ) {
        echo '<div class="footer-widgets col-' . intval( $columns ) . ' top-footer row-' . intval( $r ) . ' fix">';
        $c = 0;
        
        while ( $c < $columns ) {
          $c++;
          if ( is_active_sidebar( 'top-footer-' . $r . '-' . $c ) ) {
            echo '<section class="block footer-widget-' . intval( $c ) . '">';
					  dynamic_sidebar( 'top-footer-' . intval( $r ) . '-' . intval( $c ) );
					  echo '</section>';
          }
			  }
        echo '</div>';
      }
	  }
  }

  public function register_sidebars() {
    $sidebars = array(
      array(
		    'name'					=> __( 'Blog Sidebar', 'ssp' ),
		    'id'						=> 'sidebar-blog',
		    'description' 	=> __( 'Sidebar displaying on your blog.', 'ssp' ),
		    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		    'after_widget'	=> '</aside>',
		    'before_title'	=> '<p class="widget-title">',
		    'after_title' 	=> '</p>'
	    ),
      array(
		    'name'					=> __( 'Product Category Sidebar', 'ssp' ),
		    'id'						=> 'sidebar-product-category',
		    'description' 	=> __( 'Sidebar displaying on product categories.', 'ssp' ),
		    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		    'after_widget'	=> '</aside>',
		    'before_title'	=> '<p class="widget-title">',
		    'after_title' 	=> '</p>'
	    ),
      array(
		    'name'					=> __( 'Product Sidebar', 'ssp' ),
		    'id'						=> 'sidebar-product',
		    'description' 	=> __( 'Sidebar displaying on product page.', 'ssp' ),
		    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		    'after_widget'	=> '</aside>',
		    'before_title'	=> '<p class="widget-title">',
		    'after_title' 	=> '</p>'
	    ),
      array(
		    'name'					=> __( 'Page Sidebar', 'ssp' ),
		    'id'						=> 'sidebar-page',
		    'description' 	=> __( 'Sidebar displaying on single pages.', 'ssp' ),
		    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		    'after_widget'	=> '</aside>',
		    'before_title'	=> '<p class="widget-title">',
		    'after_title' 	=> '</p>'
	    ),
      array(
		    'name'					=> __( 'Special Sidebar', 'ssp' ),
		    'id'						=> 'sidebar-special',
		    'description' 	=> __( 'Sidebar displaying on my account, checkout, and cart pages.', 'ssp' ),
		    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		    'after_widget'	=> '</aside>',
		    'before_title'	=> '<p class="widget-title">',
		    'after_title' 	=> '</p>'
	    ),
      array(
		    'name'					=> __( 'Top Footer 1', 'ssp' ),
		    'id'  					=> 'top-footer-1-1',
		    'description' 	=> __( 'Top footer row-1 column-1', 'ssp' ),
		    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		    'after_widget'	=> '</aside>',
		    'before_title'	=> '<p class="widget-title">',
		    'after_title' 	=> '</p>'
	    ),
      array(
		    'name'					=> __( 'Top Footer 2', 'ssp' ),
		    'id'  					=> 'top-footer-1-2',
		    'description' 	=> __( 'Top footer row-1 column-2', 'ssp' ),
		    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		    'after_widget'	=> '</aside>',
		    'before_title'	=> '<p>',
		    'after_title' 	=> '</p>'
	    ),
      array(
		    'name'					=> __( 'Top Footer 3', 'ssp' ),
		    'id'  					=> 'top-footer-2-1',
		    'description' 	=> __( 'Top footer row-2 column-1', 'ssp' ),
		    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		    'after_widget'	=> '</aside>',
		    'before_title'	=> '<p>',
		    'after_title' 	=> '</p>'
	    ),
      array(
		    'name'					=> __( 'Top Footer 4', 'ssp' ),
		    'id'  					=> 'top-footer-2-2',
		    'description' 	=> __( 'Top footer row-2 column-2', 'ssp' ),
		    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		    'after_widget'	=> '</aside>',
		    'before_title'	=> '<p>',
		    'after_title' 	=> '</p>'
	    )
    );
    apply_filters( 'ssp_dynamic_sidebar_register', $sidebars );
    
    if ( ! is_array( $sidebars ) ) return;
     
    foreach ( $sidebars as $sidebar ) {
      if ( isset( $sidebar['id'] ) ) {
        register_sidebar( $sidebar );
      }
    }
  }
}
