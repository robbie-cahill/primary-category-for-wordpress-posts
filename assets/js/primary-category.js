(function($) {
	$('.primary-category').select2({
		ajax: {
			url: '/wp-admin/admin-ajax.php?action=primary_category_query',
			dataType: 'json',
			data : function(params) {
				var nonce = $('#primary-category-nonce').val();

				var query = {
					term: params.term,
					type: 'public',
					_wpnonce: nonce
				}

				return query;
			}
		}
	});
})(jQuery);