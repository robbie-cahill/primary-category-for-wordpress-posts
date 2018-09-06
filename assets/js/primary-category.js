(function($) {
	$('.primary-category').select2({
		ajax: {
			url: '/wp-admin/admin-ajax.php?action=primary_category_query',
			dataType: 'json'
			// Additional AJAX parameters go here; see the end of this chapter for the full code of this example
		}
	});
})(jQuery);