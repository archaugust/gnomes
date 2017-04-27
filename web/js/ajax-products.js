$(function(){
	$('.btn-filter').click(function (){
		$('#filter-box').slideToggle();
	});
	$('html').click(function() {
		$('.dropdown-box').slideUp();
	});
	$('.btn-filter').click(function(event){
		event.stopPropagation();
	});
	$('.btn-collection').click(function(event){
		event.stopPropagation();
	});
	$('.btn-discounter').click(function(event){
		event.stopPropagation();
	});
	$('.dropdown-box').click(function(event){
		event.stopPropagation();
	});

	function loadCheckboxes(mode) {
	    $.ajax({
	        url: "/admin/get-checkboxes/"+ mode,
	        cache: false,
	        success: function (data) {
	    	    $('#select_list').hide().html(data).slideDown();
	        }
	    });
	}
	
	$('#addCollectionConfirm').click(function(){
		$('#list_action').val('collection_add');
		$('#select_title').html('Add to Collection');
		loadCheckboxes('collection');
	});

	$('#removeCollectionConfirm').click(function(){
		$('#list_action').val('collection_remove');
		$('#select_title').html('Remove from Collection');
		loadCheckboxes('collection');
	});

	$('#addDiscounterConfirm').click(function(){
		$('#list_action').val('discounter_add');
		$('#select_title').html('Add to Discount Group');
		loadCheckboxes('discounter');
	});

	$('#removeDiscounterConfirm').click(function(){
		$('#list_action').val('discounter_remove');
		$('#select_title').html('Remove from Discount Group');
		loadCheckboxes('discounter');
	});
})
var mode = $('#mode').val();

$('.query').change(function() {
	$('#filter-form').submit();
});
$('#filter-field').change(function(){
	var field = $(this).val();
	if (field != '') {
		switch (field) {
			case 'tags':
				$('#filter-value').hide().html('<input id="filter-v" type="text" class="form-control" required="required" name="v">').slideDown();
				break;
			case 'is_active':
				$('#filter-value').hide().html('<p>is</p><select id="filter-v" name="v" class="form-control" required="required"><option value="">Select a value...</option><option value="1">visible in store</option><option value="0">not visible in store</option></select>').slideDown();
				break;
			case 'pre_sell':
				$('#filter-value').hide().html('<p>is</p><select id="filter-v" name="v" class="form-control" required="required"><option value="">Select a value...</option><option value="1">on</option><option value="0">off</option></select>').slideDown();
				break;
			case 'vend_active':
				$('#filter-value').hide().html('<p>is</p><select id="filter-v" name="v" class="form-control" required="required"><option value="">Select a value...</option><option value="1">active</option><option value="0">not active</option></select>').slideDown();
				break;
			case 'product_type':
			case 'brand_name':
			case 'supplier_name':
			    $.ajax({
			        url: "/admin/get-select",
			        type: 'POST',
			        data: 'field='+ field,
			        cache: false,
			        success: function (data) {
			            $('#filter-value').hide().html(data).slideDown();
			        }
			    });
				break;
		}
		$('#filter-submit').hide().removeClass('hidden').slideDown();
	}
	else {
		$('#filter-value').slideUp();
		$('#filter-submit').slideUp();
	}
});
$('#filter-form').submit(function(){
	$('.details').html('<i class="fa fa-refresh fa-spin fa-5x"></i><span class="sr-only">Loading...</span>');
	var data = $(this).serialize();
    $.ajax({
        url: "/admin/product/ajax_",
        type: 'GET',
        data: data,
        cache: false,
        success: function (data) {
            $('#details').hide().html(data).slideDown();
        }
    });
});

$('.filters a').click(function(){
	$('.details').html('<i class="fa fa-refresh fa-spin fa-5x"></i><span class="sr-only">Loading...</span>');
	var remove = $(this).find('span').text();
    $.ajax({
        url: "/admin/product/ajax_",
        type: 'GET',
        data: 'remove='+ remove,
        cache: false,
        success: function (data) {
            $('#details').hide().html(data).slideDown();
        }
    });
});
