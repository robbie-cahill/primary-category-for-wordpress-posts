(function($) {
	$('.primary-category').select2({
		ajax: {
			url: '/wp-admin/admin-ajax.php?action=primary_category_query',
			dataType: 'json'
		}
	});
})(jQuery);