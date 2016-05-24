<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class SSP_Aggregator {

	public function __construct() {
    add_action( 'storefront_before_footer', array( $this, 'init' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}
	
	public function enqueue_scripts() {
    if ( is_front_page() || is_product_category() ) {
			wp_register_style( 'ssp-aggregator-style', SSP_URL . 'assets/css/aggregator.css' );
			wp_enqueue_style( 'ssp-aggregator-style' );
		}
	}

	public function init() {
		// Blog Posts Aggregator
    if ( is_front_page() ) {
      $args = array( 'posts_per_page' => 4 );
			$query = new WP_Query( $args );
			if ( $query->have_posts() ) {
        $post_counter = 0;
				ob_start();
				while ( $query->have_posts() ) {
          $query->the_post();
					$post_counter ++; ?>
					
					<li class="<?php echo $post_wrap = ( $post_counter > 1 ) ? 'ssp-aggregator-post-wrap' : 'ssp-aggregator-post-wrap ssp-aggregator-post-wrap-first'; ?>">
						<a class="ssp-aggregator-post-permalink" href="<?php echo esc_url( the_permalink() ); ?>" data-wow-delay="<?php echo esc_attr( $post_counter ); ?>'s"><?php
							if ( has_post_thumbnail() ) {
								$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'blog-thumbnail' ); ?>
								<img src="<?php echo esc_url( $image[0] ); ?>" class="ssp-aggregator-post-image wp-post-image" alt="<?php echo esc_attr( the_title() ); ?>"><?php
							} ?>
							<p class="ssp-aggregator-post-title"><?php echo esc_html( the_title() ); ?></p>
							<p class="ssp-aggregator-post-date"><?php echo esc_html( get_the_date() ); ?></p>
							<p class="ssp-aggregator-post-excerpt"><?php echo wp_strip_all_tags( mb_substr( get_the_excerpt(), 0, 150 ) ); ?>...</p>
						</a>
					</li><?php
				}
				wp_reset_postdata();
			}
			if ( $post_counter > 0 ) {
        $this->template( $loop = ob_get_clean(), __( 'Blog', 'ssp' ) );
			}
		}
		// Product Reviews Aggregator
    elseif ( is_product_category() ) {
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
            $reviews_counter ++; ?>
						<li class="<?php echo $post_wrap = ( $reviews_counter > 1 ) ? 'ssp-aggregator-post-wrap' : 'ssp-aggregator-post-wrap ssp-aggregator-post-wrap-first'; ?>" itemscope itemtype="http://schema.org/Review">
							<a class="ssp-aggregator-post-permalink" href="<?php echo esc_url( the_permalink() ); ?>"><?php 
								if ( has_post_thumbnail() ) {
									echo $product->get_image();
								} ?>
								<p class="ssp-aggregator-post-title" itemprop="itemReviewed" itemscope itemtype="http://schema.org/Product"><span itemprop="name"><?php echo esc_html( the_title() ); ?></span></p>
								<?php echo $this->rating_template( $product ); ?>
								<p itemprop="author" itemscope itemtype="http://schema.org/Person" class="ssp-aggregator-post-author"><span itemprop="name"><?php echo esc_html( $comments[0]->comment_author ); ?></span></p>
								<p class="ssp-aggregator-post-excerpt" itemprop="reviewBody"><?php echo esc_html( $comments[0]->comment_content ); ?></p>
							</a>
						</li><?php
					}
					wp_reset_postdata();
				}
        if ( $reviews_counter > 0 ) {
          $this->template( $loop = ob_get_clean(), __( 'Reviews', 'ssp' ) );
				}
			}
		}
  }

  private function template( $loop, $title = '' ) { ?>
    <div class="before-footer">
			<div class="col-full">
				<div class="ssp-aggregator">
					<p class="ssp-aggregator-title"><?php echo esc_html( $title ); ?></p>
					<ul class="ssp-aggregator-post-list">
						<?php echo $loop; ?>
					</ul>
				</div>
			</div>
		</div><?php
	}
  
  private function rating_template( $product ) { 
		$rating = $product->get_average_rating(); ?>
		<div class="ssp-aggregator-rating" title="<?php echo sprintf( esc_attr__( 'Rated %s out of 5', 'woocommerce' ), esc_attr( $rating )  ); ?>" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
			<span style="width: <?php echo ( ( $rating / 5 ) * 100 ); ?>%"><strong class="rating" itemprop="ratingValue"><?php echo esc_html( $rating ); ?></strong> <?php esc_html_e( 'out of 5', 'woocommerce' ); ?></span>
		</div><?php
	}
}
