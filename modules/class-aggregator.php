<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class SSP_Aggregator {

	public function __construct() {
    add_action( 'storefront_before_footer', array( $this, 'template' ) );
    add_action( 'ssp_aggregator', array( $this, 'post_loop' ), 10 );
    add_action( 'ssp_aggregator', array( $this, 'review_loop' ), 20 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}
	
	public function enqueue_scripts() {
	  wp_register_style( 'ssp-aggregator-style', SSP_URL . 'assets/css/aggregator.css' );
		wp_enqueue_style( 'ssp-aggregator-style' );
  }

  public function template() { ?>
    
    <div class="before-footer">
      <div class="col-full">
        
        <?php do_action( 'ssp_aggregator_before' ); ?>

				<div class="ssp-aggregator">
            
            <?php do_action( 'ssp_aggregator' ); ?>
          
				</div>
  
        <?php do_action( 'ssp_aggregator_after' ); ?>

      </div>
    </div><?php
  }

  public function post_loop() {
    if ( is_front_page() ) {
      $args = array( 'posts_per_page' => 4 );
			$query = new WP_Query( $args );
			if ( $query->have_posts() ) {
        $post_counter = 0;
        ob_start();

        echo '<h2 class="ssp-aggregator-title">' . apply_filters( 'ssp_aggregator_post_loop_title', __( 'Blog', 'ssp' ) ) . '</h2>';
        echo '<ul class="ssp-aggregator-post-list">';
				while ( $query->have_posts() ) {
          $query->the_post();
          $post_counter ++;
          $post_wrap = ( $post_counter > 1 ) ? '' : ' ssp-aggregator-post-wrap-first';
					echo '<li class="ssp-aggregator-post-wrap' . $post_wrap . '">';
					echo '<a class="ssp-aggregator-post-permalink" href="' . esc_url( get_the_permalink() ) . '" data-wow-delay="' . esc_attr( $post_counter ) . '\'s">';
					if ( has_post_thumbnail() ) {
						$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'blog-thumbnail' );
						echo '<img src="' . esc_url( $image[0] ) . '" class="ssp-aggregator-post-image wp-post-image" alt="' . esc_attr( get_the_title() ) . '" />';
					}
					echo '<h3 class="ssp-aggregator-post-title">' . esc_html( get_the_title() ) . '</h3>';
					echo '<time class="ssp-aggregator-post-date">' . esc_html( get_the_date() ) . '</time>';
					echo '<p class="ssp-aggregator-post-excerpt">' . wp_strip_all_tags( mb_substr( get_the_excerpt(), 0, 150 ) ) . '...</p>';
					echo '</a>';
					echo '</li>';
        }
        echo '</ul>';
				wp_reset_postdata();
      }
      $loop = ob_get_clean();
      echo apply_filters( 'ssp_aggregator_post_loop', $loop );
		}
  }

  public function review_loop() {
    if ( is_product_category() ) {
			$queried_object = get_queried_object();
			$post_query_args = array(
				'post_type'      => 'product',
				'posts_per_page' => 4,
				'orderby'        => 'comment_count',
				'tax_query'      => array(
					array(
						'taxonomy'   => 'product_cat',
						'field'      => 'id', 
						'terms'      => $queried_object->term_taxonomy_id
					)
				)
			);
			$post_query = new WP_Query( $post_query_args );
			if ( $post_query->have_posts() ) {
        $reviews_counter = 0;
				ob_start();
        
        while( $post_query->have_posts() ) {
          $post_query->the_post();
					$product = wc_get_product( get_the_ID() );
					$comments_query_args = array(
						'post_id'    => get_the_ID(),
						'number'     => 1
					);
					$comments = get_comments( $comments_query_args ); 
					if ( ! empty( $comments[0] ) ) {
            $reviews_counter ++;
            if ( $reviews_counter === 1 ) {
              echo '<h2 class="ssp-aggregator-title">' . apply_filters( 'ssp_aggregator_post_loop_title', __( 'Reviews', 'ssp' ) ) . '</h2>';
              echo '<ul class="ssp-aggregator-post-list">';
            }
            $post_wrap = ( $reviews_counter > 1 ) ? '' : ' ssp-aggregator-post-wrap-first';
						echo '<li class="ssp-aggregator-post-wrap' . $post_wrap . '" itemscope itemtype="http://schema.org/Review">';
					  echo '<a class="ssp-aggregator-post-permalink" href="' . get_the_permalink() . '">';
						if ( has_post_thumbnail() ) {
							echo $product->get_image();
						}
						echo '<h3 class="ssp-aggregator-post-title" itemprop="itemReviewed" itemscope itemtype="http://schema.org/Product"><span itemprop="name">' . get_the_title() . '</span></h3>';
						echo $this->rating_template( $product );
						echo '<p itemprop="author" itemscope itemtype="http://schema.org/Person" class="ssp-aggregator-post-author"><span itemprop="name">' . esc_html( $comments[0]->comment_author ) . '</span></p>';
						echo '<p class="ssp-aggregator-post-excerpt" itemprop="reviewBody">' . esc_html( $comments[0]->comment_content ) . '</p>';
						echo '</a>';
						echo '</li>';
          }
          if ( $reviews_counter === 1 ) {
            echo '</ul>';
          }
					wp_reset_postdata();
        }
        $loop = ob_get_clean();
        echo apply_filters( 'ssp_aggregator_review_loop', $loop );
			}
		}
  }

  private function rating_template( $product ) { 
		$rating = $product->get_average_rating(); ?>
		<div class="ssp-aggregator-rating" title="<?php echo sprintf( esc_attr__( 'Rated %s out of 5', 'woocommerce' ), esc_attr( $rating )  ); ?>" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
			<span style="width: <?php echo ( ( $rating / 5 ) * 100 ); ?>%"><strong class="rating" itemprop="ratingValue"><?php echo esc_html( $rating ); ?></strong> <?php esc_html_e( 'out of 5', 'woocommerce' ); ?></span>
		</div><?php
	}
}
