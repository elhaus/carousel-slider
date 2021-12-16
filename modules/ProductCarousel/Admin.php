<?php

namespace CarouselSlider\Modules\ProductCarousel;

use CarouselSlider\Helper;
use CarouselSlider\Supports\MetaBoxForm;

defined( 'ABSPATH' ) || exit;

/**
 * Admin class
 *
 * @package Modules/ProductCarousel
 */
class Admin {
	/**
	 * The instance of the class
	 *
	 * @var self
	 */
	protected static $instance;

	/**
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return self
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			add_action( 'carousel_slider/meta_box_content', [ self::$instance, 'meta_box_content' ], 10, 2 );
		}

		return self::$instance;
	}

	/**
	 * Show meta box content for product carousel
	 *
	 * @param int    $slider_id The slider id.
	 * @param string $slider_type The slider type.
	 */
	public function meta_box_content( int $slider_id, string $slider_type ) {
		$form = new MetaBoxForm();
		?>
		<div data-id="open" id="section_product_query" class="shapla-toggle shapla-toggle--stroke"
			style="display: <?php echo 'product-carousel' !== $slider_type ? 'none' : 'block'; ?>">
			<span class="shapla-toggle-title">
				<?php esc_html_e( 'Product Query', 'carousel-slider' ); ?>
			</span>
			<div class="shapla-toggle-inner">
				<div class="shapla-toggle-content">
					<?php
					$form->select(
						array(
							'id'      => '_product_query_type',
							'name'    => esc_html__( 'Query Type', 'carousel-slider' ),
							'std'     => 'query_product',
							'options' => array(
								'query_product'      => esc_html__( 'Query Products', 'carousel-slider' ),
								'product_categories' => esc_html__( 'Product Categories', 'carousel-slider' ),
								'product_tags'       => esc_html__( 'Product Tags', 'carousel-slider' ),
								'specific_products'  => esc_html__( 'Specific Products', 'carousel-slider' ),
							),
						)
					);
					$form->select(
						array(
							'id'      => '_product_query',
							'name'    => esc_html__( 'Choose Query', 'carousel-slider' ),
							'std'     => 'featured',
							'options' => array(
								'featured'                => esc_html__( 'Featured Products', 'carousel-slider' ),
								'recent'                  => esc_html__( 'Recent Products', 'carousel-slider' ),
								'sale'                    => esc_html__( 'Sale Products', 'carousel-slider' ),
								'best_selling'            => esc_html__( 'Best-Selling Products', 'carousel-slider' ),
								'top_rated'               => esc_html__( 'Top Rated Products', 'carousel-slider' ),
								'product_categories_list' => esc_html__( 'Product Categories List', 'carousel-slider' ),
							),
						)
					);
					$form->post_terms(
						array(
							'id'       => '_product_categories',
							'taxonomy' => 'product_cat',
							'multiple' => true,
							'name'     => esc_html__( 'Product Categories', 'carousel-slider' ),
							'desc'     => esc_html__( 'Show products associated with selected categories.', 'carousel-slider' ),
						)
					);
					$form->post_terms(
						array(
							'id'       => '_product_tags',
							'taxonomy' => 'product_tag',
							'multiple' => true,
							'name'     => esc_html__( 'Product Tags', 'carousel-slider' ),
							'desc'     => esc_html__( 'Show products associated with selected tags.', 'carousel-slider' ),
						)
					);
					$form->posts_list(
						array(
							'id'        => '_product_in',
							'post_type' => 'product',
							'multiple'  => true,
							'name'      => esc_html__( 'Specific products', 'carousel-slider' ),
							'desc'      => esc_html__( 'Select products that you want to show as slider. Select at least 5 products', 'carousel-slider' ),
						)
					);
					$form->number(
						array(
							'id'   => '_products_per_page',
							'name' => esc_html__( 'Product per page', 'carousel-slider' ),
							'std'  => 12,
							'desc' => esc_html__( 'How many products you want to show on carousel slide.', 'carousel-slider' ),
						)
					);
					$form->checkbox(
						array(
							'id'    => '_product_title',
							'name'  => esc_html__( 'Show Title', 'carousel-slider' ),
							'label' => esc_html__( 'Show Title.', 'carousel-slider' ),
							'desc'  => esc_html__( 'Check to show product title.', 'carousel-slider' ),
							'std'   => 'on',
						)
					);
					$form->checkbox(
						array(
							'id'    => '_product_rating',
							'name'  => esc_html__( 'Show Rating', 'carousel-slider' ),
							'label' => esc_html__( 'Show Rating.', 'carousel-slider' ),
							'desc'  => esc_html__( 'Check to show product rating.', 'carousel-slider' ),
							'std'   => 'on',
						)
					);
					$form->checkbox(
						array(
							'id'    => '_product_price',
							'name'  => esc_html__( 'Show Price', 'carousel-slider' ),
							'label' => esc_html__( 'Show Price.', 'carousel-slider' ),
							'desc'  => esc_html__( 'Check to show product price.', 'carousel-slider' ),
							'std'   => 'on',
						)
					);
					$form->checkbox(
						array(
							'id'    => '_product_cart_button',
							'name'  => esc_html__( 'Show Cart Button', 'carousel-slider' ),
							'label' => esc_html__( 'Show Cart Button.', 'carousel-slider' ),
							'desc'  => esc_html__( 'Check to show product add to cart button.', 'carousel-slider' ),
							'std'   => 'on',
						)
					);
					$form->checkbox(
						array(
							'id'    => '_product_onsale',
							'name'  => esc_html__( 'Show Sale Tag', 'carousel-slider' ),
							'label' => esc_html__( 'Show Sale Tag', 'carousel-slider' ),
							'desc'  => esc_html__( 'Check to show product sale tag for onsale products.', 'carousel-slider' ),
							'std'   => 'on',
						)
					);
					$form->checkbox(
						array(
							'id'    => '_product_wishlist',
							'name'  => esc_html__( 'Show Wishlist Button', 'carousel-slider' ),
							'label' => esc_html__( 'Show Wishlist Button', 'carousel-slider' ),
							'std'   => 'off',
							/* translators: 1: YITH WooCommerce Wishlist plugin url*/
							'desc'  => sprintf( esc_html__( 'Check to show wishlist button. This feature needs %s plugin to be installed.', 'carousel-slider' ), sprintf( '<a href="https://wordpress.org/plugins/yith-woocommerce-wishlist/" target="_blank" >%s</a>', __( 'YITH WooCommerce Wishlist', 'carousel-slider' ) ) ),
						)
					);
					$form->checkbox(
						array(
							'id'    => '_product_quick_view',
							'name'  => esc_html__( 'Show Quick View', 'carousel-slider' ),
							'label' => esc_html__( 'Show Quick View', 'carousel-slider' ),
							'desc'  => esc_html__( 'Check to show quick view button.', 'carousel-slider' ),
							'std'   => 'on',
						)
					);
					$form->color(
						array(
							'id'   => '_product_title_color',
							'type' => 'color',
							'name' => esc_html__( 'Title Color', 'carousel-slider' ),
							'desc' => esc_html__( 'Pick a color for product title. This color will also apply to sale tag and price.', 'carousel-slider' ),
							'std'  => Helper::get_default_setting( 'product_title_color' ),
						)
					);
					$form->color(
						array(
							'id'   => '_product_button_bg_color',
							'type' => 'color',
							'name' => esc_html__( 'Button Background Color', 'carousel-slider' ),
							'desc' => esc_html__( 'Pick a color for button background color. This color will also apply to product rating.', 'carousel-slider' ),
							'std'  => Helper::get_default_setting( 'product_button_bg_color' ),
						)
					);
					$form->color(
						array(
							'id'   => '_product_button_text_color',
							'type' => 'color',
							'name' => esc_html__( 'Button Text Color', 'carousel-slider' ),
							'desc' => esc_html__( 'Pick a color for button text color.', 'carousel-slider' ),
							'std'  => Helper::get_default_setting( 'product_button_text_color' ),
						)
					);
					?>
				</div>
			</div>
		</div>
		<?php
	}
}
