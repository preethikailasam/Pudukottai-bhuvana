jQuery(document).ready(function ($) {

	// Re-arrange plugin list

	var thePluginList = ['feed-instagram-lite', 'easy-media-gallery', 'contact-form-lite', 'image-slider-widget', 'gallery-lightbox-slider', 'image-carousel', 'icon', 'easy-notify-lite'];

	// Get Plugins Content via AJAX
	if ($(".ajax-recommended-plugins").length) {

		var elmnt = $(".ajax-recommended-plugins");

		setTimeout(function () {

			ajax_get_content(elmnt);

		}, 1000);

	}


	function ajax_get_content(elmnt) {

		dat = {};
		dat['security'] = elmnt.data('secure');
		dat['action'] = 'get_content_via_ajax';

		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: dat,

			success: function (response) {

				if (response) {

					$(".wntabloader").hide();
					$('.ajax-recommended-plugins').html(response).fadeIn(1000);
					$('.plugin-to-short').zoomReOrder(thePluginList);

					$('html, body').animate({
						scrollTop: $(".ajax-recommended-plugins").offset().top - 50
					}, 1000);

				} else {

					return false;

				}

				// end success-		
			}

			// end ajax
		});

	}

	// Animate Logo on page load
	setTimeout(function () {
		$('.theme-logo-cont .show-off').css({
			'top': '-7px',
			'left': '0px',
			'transform': 'rotate(0deg)'
		});
		$('.zoom-badge-top-small .show-off').css({
			'top': '-10px',
			'left': '-10px',
			'transform': 'rotate(0deg)'
		});
	}, 300);

	setTimeout(function () {
		$('.zoom-badge-top-small .show-off').css('transform', 'rotate(365deg)');
		$('.theme-logo-cont .show-off').css('transform', 'rotate(365deg)');
	}, 700);


}); // End Doc Ready


(function ($) {

	$.fn.zoomReOrder = function (array) {
		return this.each(function () {

			if (array) {
				for (var i = 0; i < array.length; i++) {
					if (array[i] == 'feed-instagram-lite') $('div[id="' + array[i] + '"]').find('.plugin-title-on-list').text('Instagram Feed Plugin');
					array[i] = $('div[id="' + array[i] + '"]');
				}

				$(this).empty();

				for (var i = 0; i < array.length; i++)
					$(this).append(array[i]);
			}
		});
	}
})(jQuery);