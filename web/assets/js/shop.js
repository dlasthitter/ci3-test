(function($) {

	var Shop = function() {
		var orderCallback = function() {
			$('.removeOrder')
			.unbind('click')
			.bind('click', function(e) {
				e.preventDefault();

				var id = $(this).attr('data-id');

				modConfirm('Are you sure you want to remove this?', function() {
					serverProcess({
						action : 'api/shop/removeitem',
						data : {
							id : id
						},
						show_process : true,
						callback : function(json) {
							if (json.success) {
								$('#orderSummary').html(json.orderHtml);
								orderCallback();
							} else {
								modAlert(json.error);
							}
						}

					});
				});
			});
		};

		var bindEvents = function() {
			$('.addToCart').bind('click', function(e) {
				e.preventDefault();

				var id = $(this).attr('data-id');

				serverProcess({
					action : 'api/shop/addtocart',
					data : {
						id : id,
						qty : $('#qty_' + id).val()
					},
					show_process : true,
					callback : function(json) {
						if (json.success) {
							$('#orderSummary').html(json.orderHtml);
							orderCallback();
						} else {
							modAlert(json.error);
						}
					}

				});

			});
		};
		
		return {
			_construct : function() {
				bindEvents();
				orderCallback();
			}
		};
	}();

	$.fn.extend({
		Shop : Shop
	});

})(jQuery);