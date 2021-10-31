<?php

namespace CarouselSlider\Modules\VideoCarousel;

use CarouselSlider\Abstracts\AbstractView;
use CarouselSlider\Helper;
use CarouselSlider\Modules\VideoCarousel\Helper as VideoCarouselHelper;
use CarouselSlider\Supports\Validate;

defined( 'ABSPATH' ) || exit;

class View extends AbstractView {
	/**
	 * @inheritDoc
	 */
	public function render(): string {
		$slider_id       = $this->get_slider_id();
		$slider_type     = $this->get_slider_type();
		$urls            = get_post_meta( $slider_id, '_video_url', true );
		$lazy_load_image = get_post_meta( $slider_id, '_lazy_load_image', true );
		if ( is_string( $urls ) ) {
			$urls = array_filter( explode( ',', $urls ) );
		}
		$urls = VideoCarouselHelper::get_video_url( $urls );

		$css_classes = [
			"carousel-slider-outer",
			"carousel-slider-outer-videos",
			"carousel-slider-outer-{$slider_id}"
		];

		$attributes_array = Helper::get_slider_attributes( $slider_id, $slider_type );

		$html = '<div class="' . join( ' ', $css_classes ) . '">' . PHP_EOL;
		$html .= "<div " . join( " ", $attributes_array ) . ">" . PHP_EOL;
		foreach ( $urls as $url ) {
			$html .= '<div class="carousel-slider-item-video">';
			$html .= '<div class="carousel-slider-video-wrapper">';
			$html .= '<a class="magnific-popup" href="' . esc_url( $url['url'] ) . '">';
			$html .= '<div class="carousel-slider-video-play-icon"></div>';
			$html .= '<div class="carousel-slider-video-overlay"></div>';
			if ( Validate::checked( $lazy_load_image ) ) {
				$html .= '<img class="owl-lazy" data-src="' . esc_url( $url['thumbnail']['large'] ) . '"/>';
			} else {
				$html .= '<img src="' . esc_url( $url['thumbnail']['large'] ) . '"/>';
			}
			$html .= '</a>';
			$html .= '</div>';
			$html .= '</div>' . PHP_EOL;
		}

		$html .= '</div><!-- .carousel-slider-' . $slider_id . ' -->' . PHP_EOL;
		$html .= '</div><!-- .carousel-slider-outer-' . $slider_id . ' -->' . PHP_EOL;

		return apply_filters( 'carousel_slider_videos_carousel', $html, $slider_id );
	}
}
