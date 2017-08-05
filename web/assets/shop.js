(function($) {

	var Shop = function() {
		var bindEvents = function() {
			alert(1);
		};

		return {
			_construct : function() {
				bindEvents();
			}
		};
	}();

	$.fn.extend({
		Shop : Shop
	});

})(jQuery);

alert(1);