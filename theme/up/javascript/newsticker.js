(function($) {
	$.fn.extend({
		vscroller : function(options) {
			var settings = $.extend({
				speed : 4000,
				stay : 10000,
				newsfeed : '',
				cache : true
			}, options);

			return this.each(function() {
				var interval = null;
				var mouseIn = false;
				var totalElements;
				var isScrolling = false;
				var wrapper = $('.newsticker-wrapper');
				var newsContents = $('.newsticker-contents');
				var i = 0;
				var sum = 0;
				totalElements = $.find('.newstickerpost').length;
				if (totalElements > 1) {
					$('.newstickerpost', wrapper).each(function() {
							$(this).css({
								top : sum
							});
							sum += 350;
					});

					newsContents.mouseenter(function() {
						mouseIn = true;
						if (!isScrolling) {
							$('.newstickerpost').stop(true, false);
							clearTimeout(interval);
						}
					});
					
					newsContents.mouseleave(function() {
						mouseIn = false;
						interval = setTimeout(scroll, settings.stay);
					});
					interval = setTimeout(scroll, 1);

					function scroll() {
						if (!mouseIn && !isScrolling) {
							isScrolling = true;
							var firstItemHeight = 350;
							$('.newstickerpost:eq(0)').stop(true, false).animate(
									{
										top : -firstItemHeight
									},
									settings.speed,
									function() {
										clearTimeout(interval);
										var current = $('.newstickerpost:eq(0)')
												.clone(true);
										current.css({
											top : sum - firstItemHeight
										});
										$('.newsticker-contents').append(current);
										$('.newstickerpost:eq(0)').remove();
										isScrolling = false;
										interval = setTimeout(scroll,
												settings.stay);

									});
							$('.newstickerpost:gt(0)').stop(true, false).animate({
								top : '-=' + firstItemHeight
							}, settings.speed);
						}
					}
				}
			});
		}
	});
})(jQuery);

$(document).ready(function() {
	$('#newsticker').vscroller();
});
