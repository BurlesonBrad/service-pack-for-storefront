<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class SSP_Dynamic_Sidebar {

  public function __construct() {
    add_action( 'init', array( $this, 'storefront_remove_sidebar' ) );
    add_action( 'widgets_init', array( $this, 'register_sidebars' ) );
    add_action( 'storefront_sidebar', array( $this, 'sidebar_template' ) );
    add_action( 'storefront_footer', array( $this, 'top_footer_template' ), 5 );
  }

  public function storefront_remove_sidebar() {
    remove_action( 'storefront_sidebar', 'storefront_get_sidebar', 10 );
  }

  public function sidebar_template() {
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
    if ( ! is_active_sidebar( $sidebar ) ) return;
    echo '<div id="secondary" class="widget-area" role="complementary">';
    dynamic_sidebar( $sidebar );
    echo '</div>';
  }
  
  public function top_footer_template() {
    $row = 2;
    $j = 0;
    while ( $j < $row ) {
      $j++;
      if ( is_active_sidebar( 'top-footer-' . $j . '-2' ) ) {
			  $widget_columns = 2;
		  } elseif ( is_active_sidebar( 'top-footer-' . $j . '-1' ) ) {
			    $widget_columns = 1;
		  } else {
			    $widget_columns = 0;
		  }
      if ( $widget_columns > 0 ) {
        echo '<section class="footer-widgets col-' . intval( $widget_columns ) . ' top-footer row-' . intval( $j ) . ' fix">';
			  $i = 0;
			  while ( $i < $widget_columns ) {
          $i++;
				  if ( is_active_sidebar( 'top-footer-' . $j . '-' . $i ) ) {
            echo '<section class="block footer-widget-' . intval( $i ) . '">';
					  dynamic_sidebar( 'top-footer-' . intval( $j ) . '-' . intval( $i ) );
					  echo '</section>';
          }
			  }
        echo '</section>';
      }
	  }
  }

  public function register_sidebars() {
    register_sidebar(
      array(
		    'name'					=> __( 'Blog Sidebar', 'ssp' ),
		    'id'						=> 'sidebar-blog',
		    'description' 	=> 'Sidebar displaying on your blog.',
		    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		    'after_widget'	=> '</aside>',
		    'before_title'	=> '<p class="widget-title">',
		    'after_title' 	=> '</p>',
	    )
    );
    register_sidebar(
      array(
		    'name'					=> __( 'Product Category Sidebar', 'ssp' ),
		    'id'						=> 'sidebar-product-category',
		    'description' 	=> 'Sidebar displaying in product categories.',
		    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		    'after_widget'	=> '</aside>',
		    'before_title'	=> '<p class="widget-title">',
		    'after_title' 	=> '</p>',
	    )
    );
    register_sidebar(
      array(
		    'name'					=> __( 'Product Sidebar', 'ssp' ),
		    'id'						=> 'sidebar-product',
		    'description' 	=> 'Sidebar displaying in product page.',
		    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		    'after_widget'	=> '</aside>',
		    'before_title'	=> '<p class="widget-title">',
		    'after_title' 	=> '</p>',
	    )
    );
    register_sidebar(
      array(
		    'name'					=> __( 'Page Sidebar', 'ssp' ),
		    'id'						=> 'sidebar-page',
		    'description' 	=> 'Sidebar displaying in pages.',
		    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		    'after_widget'	=> '</aside>',
		    'before_title'	=> '<p class="widget-title">',
		    'after_title' 	=> '</p>',
	    )
    );
    register_sidebar(
      array(
		    'name'					=> __( 'Special Sidebar', 'ssp' ),
		    'id'						=> 'sidebar-special',
		    'description' 	=> 'Sidebar displaying on my acount, checkout, and cart pages.',
		    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		    'after_widget'	=> '</aside>',
		    'before_title'	=> '<p class="widget-title">',
		    'after_title' 	=> '</p>',
	    )
    );
	  register_sidebar(
      array(
		    'name'					=> __( 'Top Footer 1', 'ssp' ),
		    'id'  					=> 'top-footer-1-1',
		    'description' 	=> 'Top footer row 1 sidebar 1',
		    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		    'after_widget'	=> '</aside>',
		    'before_title'	=> '<p class="widget-title">',
		    'after_title' 	=> '</p>',
	    )
    );
	  register_sidebar(
      array(
		    'name'					=> __( 'Top Footer 2', 'ssp' ),
		    'id'  					=> 'top-footer-1-2',
		    'description' 	=> 'Top footer row 1 sidebar 2',
		    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		    'after_widget'	=> '</aside>',
		    'before_title'	=> '<p>',
		    'after_title' 	=> '</p>',
	    )
    );
	  register_sidebar(
      array(
		    'name'					=> __( 'Top Footer 3', 'ssp' ),
		    'id'  					=> 'top-footer-2-1',
		    'description' 	=> 'Top footer row 2 sidebar 1',
		    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		    'after_widget'	=> '</aside>',
		    'before_title'	=> '<p>',
		    'after_title' 	=> '</p>',
	    )
    );
	  register_sidebar(
      array(
		    'name'					=> __( 'Top Footer 4', 'ssp' ),
		    'id'  					=> 'top-footer-2-2',
		    'description' 	=> 'Top footer row 2 sidebar 2',
		    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		    'after_widget'	=> '</aside>',
		    'before_title'	=> '<p>',
		    'after_title' 	=> '</p>',
	    )
    );
  }
}
