import $ from 'jquery';
import EventBus from "@/admin/EventBus.js";
import Tooltip from "@/libs/tooltip/index.js";
import '@/libs/tooltip/index.scss';
import '@/libs/color-picker/index.js';
import 'select2/dist/js/select2.js'

import '@/admin/carousels/hero-banner-slider.js';
import '@/admin/carousels/image-carousel.js';
import '@/admin/carousels/image-carousel-url.js';
import '@/admin/carousels/post-carousel.js';
import '@/admin/carousels/product-carousel.js';
import '@/admin/carousels/video-carousel.js';


let slide_type = $('#_carousel_slider_slide_type');

slide_type.on('change', () => EventBus.changeSlideType(slide_type.val()));


let elements = document.querySelectorAll(".cs-tooltip");
if (elements.length) {
	elements.forEach(element => new Tooltip(element));
}

// Initializing WP Color Picker
$('.color-picker').each(function () {
	$(this).wpColorPicker();
});

// Initializing Select2
$("select.select2").each(function () {
	$(this).select2();
});

// Initializing jQuery UI Accordion
$(".shapla-toggle").each(function () {
	if ($(this).attr('data-id') === 'closed') {
		$(this).accordion({collapsible: true, heightStyle: "content", active: false});
	} else {
		$(this).accordion({collapsible: true, heightStyle: "content"});
	}
});

// Initializing jQuery UI Tab
$(".shapla-tabs").tabs({
	hide: {effect: "fadeOut", duration: 200},
	show: {effect: "fadeIn", duration: 200}
});