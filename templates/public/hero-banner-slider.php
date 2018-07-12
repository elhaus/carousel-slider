<?php

use CarouselSlider\Supports\DynamicStyle;
use CarouselSlider\Supports\Utils;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$content_sliders   = get_post_meta( $id, '_content_slider', true );
$settings          = get_post_meta( $id, '_content_slider_settings', true );
$_lazy_load_image  = get_post_meta( $id, '_lazy_load_image', true );
$_be_lazy          = in_array( $_lazy_load_image, array( 'on', 'off' ) ) ? $_lazy_load_image : 'on';
$content_animation = empty( $settings['content_animation'] ) ? '' : esc_attr( $settings['content_animation'] );

?>
<div class="carousel-slider-outer carousel-slider-outer-contents carousel-slider-outer-<?php echo $id; ?>">
	<?php DynamicStyle::generate( $id ); ?>
    <div id="id-<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $class ); ?>"
         data-slide_type="<?php echo esc_attr( $slide_type ); ?>"
         data-owl_carousel='<?php echo json_encode( $owl_options ); ?>'
         data-animation="<?php echo $content_animation; ?>">
		<?php
		foreach ( $content_sliders as $slide_id => $slide ):

			$html = '';

			$_link_type   = isset( $slide['link_type'] ) && in_array( $slide['link_type'],
				array( 'full', 'button' ) ) ? $slide['link_type'] : 'full';
			$_slide_link  = ! empty( $slide['slide_link'] ) ? esc_url( $slide['slide_link'] ) : '';
			$_link_target = ! empty( $slide['link_target'] ) && in_array( $slide['link_target'],
				array( '_self', '_blank' ) ) ? esc_attr( $slide['link_target'] ) : '_self';

			$_cell_style = '';
			$_cell_style .= isset( $settings['slide_height'] ) ? 'height: ' . $settings['slide_height'] . ';' : '';

			if ( $_link_type == 'full' && Utils::is_url( $_slide_link ) ) {
				$html .= '<a class="carousel-slider-hero__cell" style="' . $_cell_style . '" href="' . $_slide_link . '" target="' . $_link_target . '">';
			} else {
				$html .= '<div class="carousel-slider-hero__cell" style="' . $_cell_style . '">';
			}

			// Slide Background
			$_background_type  = ! empty( $slide['background_type'] ) ? esc_attr( $slide['background_type'] ) : 'classic';
			$_img_bg_position  = ! empty( $slide['img_bg_position'] ) ? esc_attr( $slide['img_bg_position'] ) : 'center center';
			$_img_bg_size      = ! empty( $slide['img_bg_size'] ) ? esc_attr( $slide['img_bg_size'] ) : 'contain';
			$_bg_color         = ! empty( $slide['bg_color'] ) ? esc_attr( $slide['bg_color'] ) : '';
			$_bg_overlay       = ! empty( $slide['bg_overlay'] ) ? esc_attr( $slide['bg_overlay'] ) : '';
			$_ken_burns_effect = ! empty( $slide['ken_burns_effect'] ) ? esc_attr( $slide['ken_burns_effect'] ) : '';
			$_img_id           = ! empty( $slide['img_id'] ) ? absint( $slide['img_id'] ) : 0;
			$_img_src          = wp_get_attachment_image_src( $_img_id, 'full' );
			$_have_img         = is_array( $_img_src );

			if ( 'gradient' == $_background_type ) {
				$_gradient        = ! empty( $slide['bg_gradient_color'] ) ? $slide['bg_gradient_color'] : array();
				$_gradient_angle  = isset( $_gradient['angle'] ) ? intval( $_gradient['angle'] ) . 'deg' : '0deg';
				$_gradient_type   = isset( $_gradient['type'] ) ? esc_attr( $_gradient['type'] ) : 'linear';
				$_gradient_colors = isset( $_gradient['colors'] ) ? json_decode( $_gradient['colors'], true ) : array();
				$n_value          = array();
				foreach ( $_gradient_colors as $_value ) {
					$n_value[] = sprintf( '%s %s%%', $_value['color'], round( $_value['position'] * 100 ) );
				}

				if ( 'linear' == $_gradient_type ) {
					$_gradient_image = sprintf( 'linear-gradient(%1$s, %2$s);',
						$_gradient_angle, implode( ', ', $n_value ) );
				} else {
					$_gradient_image = sprintf( 'radial-gradient(%s);', implode( ', ', $n_value ) );
				}
			}


			// Slide background
			$_slide_bg_style = '';
			$_slide_bg_style .= 'background-position: ' . $_img_bg_position . ';';
			$_slide_bg_style .= 'background-size: ' . $_img_bg_size . ';';
			if ( 'classic' == $_background_type && $_have_img && $_be_lazy == 'off' ) {
				$_slide_bg_style .= 'background-image: url(' . $_img_src[0] . ');';
			}
			if ( ! empty( $_bg_color ) ) {
				$_slide_bg_style .= 'background-color: ' . $_bg_color . ';';
			}

			if ( 'gradient' == $_background_type && isset( $_gradient_image ) ) {
				$_slide_bg_style .= 'background-image: ' . $_gradient_image . ';';
			}

			// Background class
			$_slide_bg_class = 'carousel-slider-hero__cell__background';

			if ( 'zoom-in' == $_ken_burns_effect ) {
				$_slide_bg_class .= ' carousel-slider-hero-ken-in';
			} elseif ( 'zoom-out' == $_ken_burns_effect ) {
				$_slide_bg_class .= ' carousel-slider-hero-ken-out';
			}

			if ( 'classic' == $_background_type && $_be_lazy == 'on' ) {
				$html .= '<div class="' . $_slide_bg_class . ' owl-lazy" data-src="' . $_img_src[0] . '" id="slide-item-' . $id . '-' . $slide_id . '" style="' . $_slide_bg_style . '"></div>';
			} elseif ( in_array( $_background_type, array( 'classic', 'gradient' ) ) ) {
				$html .= '<div class="' . $_slide_bg_class . '" id="slide-item-' . $id . '-' . $slide_id . '" style="' . $_slide_bg_style . '"></div>';
			}

			// Video Background
			if ( 'video' == $_background_type ) {
				$video_url           = isset( $slide['video_url'] ) ? esc_url( $slide['video_url'] ) : '';
				$aspect_ratio        = isset( $slide['aspect_ratio'] ) ? esc_url( $slide['aspect_ratio'] ) : '';
				$display_mode        = isset( $slide['display_mode'] ) ? esc_url( $slide['display_mode'] ) : '';
				$video_overlay_color = isset( $slide['video_overlay_color'] ) ? esc_url( $slide['video_overlay_color'] ) : '';
				$mute_video          = isset( $slide['mute_video'] ) ? esc_url( $slide['mute_video'] ) : '';
				$autoplay_video      = isset( $slide['autoplay_video'] ) ? esc_url( $slide['autoplay_video'] ) : '';
				$loop_video          = isset( $slide['loop_video'] ) ? esc_url( $slide['loop_video'] ) : '';
				$hide_video_controls = isset( $slide['hide_video_controls'] ) ? esc_url( $slide['hide_video_controls'] ) : '';
				$youtube_id          = $this->get_youtube_id_from_url( $video_url );

				$video_src = add_query_arg( array(
					'autoplay'       => 1, // play automatically once the player has loaded
					'controls'       => 0, // Player controls do not display in the player
					'loop'           => 1, // play the video again and again
					'modestbranding' => 1, // prevent the YouTube logo from displaying in the control bar
					'enablejsapi'    => 1, // Enable JavaScript Player API calls
					'origin'         => site_url(), // provides an extra security measure for the IFrame API
					'playlist'       => $youtube_id, // Set only current video only in playlist
					'showinfo'       => 0, // Hide video title and uploader before the video starts playing
					'rel'            => 0, // Hide related videos when playback of the initial video ends
					'vq'             => 'hd720',
				), 'http://www.youtube.com/embed/' . $youtube_id );

				$html .= '<div class="carousel-slider-hero__cell__background carousel-slider-has-video" id="slide-item-' . $id . '-' . $slide_id . '" style="' . $_slide_bg_style . '">';
				$html .= '<div class="video-wrapper">';
				$html .= '<iframe height="100%" width="100%" src="' . esc_url_raw( $video_src ) . '"></iframe>';
				$html .= '</div>';
				$html .= '</div>';
			}

			// Cell Inner
			$_content_alignment = ! empty( $slide['content_alignment'] ) ? esc_attr( $slide['content_alignment'] ) : 'left';
			$_cell_inner_class  = 'carousel-slider-hero__cell__inner carousel-slider--h-position-center';
			if ( $_content_alignment == 'left' ) {
				$_cell_inner_class .= ' carousel-slider--v-position-middle carousel-slider--text-left';
			} elseif ( $_content_alignment == 'right' ) {
				$_cell_inner_class .= ' carousel-slider--v-position-middle carousel-slider--text-right';
			} else {
				$_cell_inner_class .= ' carousel-slider--v-position-middle carousel-slider--text-center';
			}

			$slide_padding   = isset( $settings['slide_padding'] ) && is_array( $settings['slide_padding'] ) ? $settings['slide_padding'] : array();
			$_padding_top    = isset( $slide_padding['top'] ) ? esc_attr( $slide_padding['top'] ) : '1rem';
			$_padding_right  = isset( $slide_padding['right'] ) ? esc_attr( $slide_padding['right'] ) : '3rem';
			$_padding_bottom = isset( $slide_padding['bottom'] ) ? esc_attr( $slide_padding['bottom'] ) : '1rem';
			$_padding_left   = isset( $slide_padding['left'] ) ? esc_attr( $slide_padding['left'] ) : '3rem';

			$_cell_inner_style = '';
			$_cell_inner_style .= 'padding: ' . $_padding_top . ' ' . $_padding_right . ' ' . $_padding_bottom . ' ' . $_padding_left . '';

			$html .= '<div class="' . $_cell_inner_class . '" style="' . $_cell_inner_style . '">';

			// Background Overlay
			if ( ! empty( $_bg_overlay ) ) {
				$_bg_overlay_style = 'background-color: ' . $_bg_overlay . ';';

				$html .= '<div class="carousel-slider-hero__cell__background_overlay" style="' . $_bg_overlay_style . '"></div>';
			}

			$_content_style = '';
			$_content_style .= isset( $settings['content_width'] ) ? 'max-width: ' . $settings['content_width'] . ';' : '850px;';

			$html .= '<div class="carousel-slider-hero__cell__content" style="' . $_content_style . '">';

			// Slide Heading
			$_slide_heading = isset( $slide['slide_heading'] ) ? $slide['slide_heading'] : '';

			$html .= '<div class="carousel-slider-hero__cell__heading">';
			$html .= wp_kses_post( $_slide_heading );
			$html .= '</div>'; // .carousel-slider-hero__cell__heading

			$_slide_description = isset( $slide['slide_description'] ) ? $slide['slide_description'] : '';

			$html .= '<div class="carousel-slider-hero__cell__description">';
			$html .= wp_kses_post( $_slide_description );
			$html .= '</div>'; // .carousel-slider-hero__cell__content

			// Buttons
			if ( $_link_type == 'button' ) {
				$html .= '<div class="carousel-slider-hero__cell__buttons">';

				// Slide Button #1
				$_btn_1_text   = ! empty( $slide['button_one_text'] ) ? esc_attr( $slide['button_one_text'] ) : '';
				$_btn_1_url    = ! empty( $slide['button_one_url'] ) ? esc_url( $slide['button_one_url'] ) : '';
				$_btn_1_target = ! empty( $slide['button_one_target'] ) ? esc_attr( $slide['button_one_target'] ) : '_self';
				$_btn_1_type   = ! empty( $slide['button_one_type'] ) ? esc_attr( $slide['button_one_type'] ) : 'normal';
				$_btn_1_size   = ! empty( $slide['button_one_size'] ) ? esc_attr( $slide['button_one_size'] ) : 'medium';
				if ( Utils::is_url( $_btn_1_url ) ) {
					$_btn_1_class = 'button cs-hero-button';
					$_btn_1_class .= ' cs-hero-button-' . $slide_id . '-1';
					$_btn_1_class .= ' cs-hero-button-' . $_btn_1_type;
					$_btn_1_class .= ' cs-hero-button-' . $_btn_1_size;

					$html .= '<span class="carousel-slider-hero__cell__button__one">';
					$html .= '<a class="' . $_btn_1_class . '" href="' .
					         $_btn_1_url . '" target="' . $_btn_1_target . '">' . esc_attr( $_btn_1_text ) . "</a>";
					$html .= '</span>';
				}

				// Slide Button #2
				$_btn_2_text   = ! empty( $slide['button_two_text'] ) ? esc_attr( $slide['button_two_text'] ) : '';
				$_btn_2_url    = ! empty( $slide['button_two_url'] ) ? esc_url( $slide['button_two_url'] ) : '';
				$_btn_2_target = ! empty( $slide['button_two_target'] ) ? esc_attr( $slide['button_two_target'] ) : '_self';
				$_btn_2_size   = ! empty( $slide['button_two_size'] ) ? esc_attr( $slide['button_two_size'] ) : 'medium';
				$_btn_2_type   = ! empty( $slide['button_two_type'] ) ? esc_attr( $slide['button_two_type'] ) : 'normal';
				if ( Utils::is_url( $_btn_2_url ) ) {
					$_btn_2_class = 'button cs-hero-button';
					$_btn_2_class .= ' cs-hero-button-' . $slide_id . '-2';
					$_btn_2_class .= ' cs-hero-button-' . $_btn_2_type;
					$_btn_2_class .= ' cs-hero-button-' . $_btn_2_size;

					$html .= '<span class="carousel-slider-hero__cell__button__two">';
					$html .= '<a class="' . $_btn_2_class . '" href="' . $_btn_2_url . '" target="' . $_btn_2_target . '">' . esc_attr( $_btn_2_text ) . "</a>";
					$html .= '</span>';
				}

				$html .= '</div>'; // .carousel-slider-hero__cell__button
			}

			$html .= '</div>'; // .carousel-slider-hero__cell__content
			$html .= '</div>'; // .carousel-slider-hero__cell__inner

			if ( $_link_type == 'full' && Utils::is_url( $_slide_link ) ) {
				$html .= '</a>'; // .carousel-slider-hero__cell
			} else {
				$html .= '</div>'; // .carousel-slider-hero__cell
			}

			echo apply_filters( 'carousel_slider_content', $html, $slide_id, $slide );
		endforeach;
		?>
    </div>
</div>
