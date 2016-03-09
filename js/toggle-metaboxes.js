jQuery(function($) {

	var $categorySelect = $('#acf-field-block_type');
	var metaboxes = [];

	if($categorySelect.length > 0) {
		$.post(
			document.location.origin + '/wp-admin/admin-ajax.php',
			{ action: 'acfdm_get_metaboxes' },
			function(data) {
				if(Array.isArray(data))
					metaboxes = data;
				$categorySelect.on('change', check_categories);
				check_categories();
			}
		);
	}

	function check_categories()
	{
		metaboxes.forEach(function(box) {
			var $box = $('#'+box.id);
			$box.hide();
			var value = $categorySelect.val();
			value = parseInt(value);
			if ($.inArray(value, box.types) > -1)
			{
				$box.show();
			}
		});
	}
});
