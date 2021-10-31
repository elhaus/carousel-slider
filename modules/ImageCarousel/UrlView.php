<?php

namespace CarouselSlider\Modules\ImageCarousel;

use CarouselSlider\Abstracts\AbstractView;
use CarouselSlider\Supports\Validate;
use CarouselSlider\Helper;

defined( 'ABSPATH' ) || exit;

class UrlView extends AbstractView {

	/**
	 * @inheritDoc
	 */
	public function render(): string {
		$slider_id   = $this->get_slider_id();
		$slider_type = $this->get_slider_type();
		$images_urls = (array) get_post_meta( $slider_id, '_images_urls', true );
		if ( count( $images_urls ) < 1 ) {
			return '';
		}
		$image_target            = get_post_meta( $slider_id, '_image_target', true );
		$image_target            = in_array( $image_target, [ '_self', '_blank' ] ) ? $image_target : '_self';
		$lazy_load_image         = get_post_meta( $slider_id, '_lazy_load_image', true );
		$show_attachment_title   = get_post_meta( $slider_id, '_show_attachment_title', true );
		$show_attachment_caption = get_post_meta( $slider_id, '_show_attachment_caption', true );

		$css_classes = [
			"carousel-slider-outer",
			"carousel-slider-outer-images",
			"carousel-slider-outer-{$slider_id}"
		];

		$attributes_array = Helper::get_slider_attributes( $slider_id, $slider_type );

		$html = '<div class="' . join( ' ', $css_classes ) . '">' . PHP_EOL;
		$html .= "<div " . join( " ", $attributes_array ) . ">" . PHP_EOL;

		foreach ( $images_urls as $imageInfo ) {
			$title   = sprintf( '<h4 class="title">%1$s</h4>', esc_html( $imageInfo['title'] ) );
			$caption = sprintf( '<p class="caption">%1$s</p>', esc_html( $imageInfo['caption'] ) );

			if ( Validate::checked( $show_attachment_title ) && Validate::checked( $show_attachment_caption ) ) {
				$full_caption = sprintf( '<div class="carousel-slider__caption">%1$s%2$s</div>', $title, $caption );
			} elseif ( Validate::checked( $show_attachment_title ) ) {
				$full_caption = sprintf( '<div class="carousel-slider__caption">%s</div>', $title );
			} elseif ( Validate::checked( $show_attachment_caption ) ) {
				$full_caption = sprintf( '<div class="carousel-slider__caption">%s</div>', $caption );
			} else {
				$full_caption = '';
			}

			if ( Validate::checked( $lazy_load_image ) ) {
				$image = sprintf( '<img class="owl-lazy" data-src="%1$s" alt="%2$s" />',
					$imageInfo['url'], $imageInfo['alt'] );
			} else {
				$image = sprintf( '<img src="%1$s" alt="%2$s" />', $imageInfo['url'], $imageInfo['alt'] );
			}

			$html .= '<div class="carousel-slider__item">';
			if ( Validate::url( $imageInfo['link_url'] ) ) {
				$html .= sprintf( '<a href="%1$s" target="%4$s">%2$s %3$s</a>', $imageInfo['link_url'], $image,
					$full_caption, $image_target );
			} else {
				$html .= $image;
				$html .= $full_caption;
			}
			$html .= '</div>' . PHP_EOL;
		}

		$html .= '</div><!-- .carousel-slider-' . $slider_id . ' -->' . PHP_EOL;
		$html .= '</div><!-- .carousel-slider-outer-' . $slider_id . ' -->' . PHP_EOL;

		return apply_filters( 'carousel_slider_link_images_carousel', $html, $slider_id );
	}
}
