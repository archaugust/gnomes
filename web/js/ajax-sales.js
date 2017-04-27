$(function(){
	$('#menu-sales').addClass('active');
	$('.btn-filter').click(function (){
		$('#filter-box').slideToggle();
	});
	$('html').click(function() {
		$('#filter-box').slideUp();
	});
	$('.btn-filter').click(function(event){
		event.stopPropagation();
	});
	$('#filter-box').click(function(event){
		event.stopPropagation();
	});
	
	$('.datepicker').datepicker({
	    dateFormat: 'yy-mm-dd',
	    changeMonth: true, 
	    changeYear: true, 
    });
});

$('.query').change(function() {
	$('#filter-form').submit();
});

$('.filters a').click(function(){
	$('.details').html('<i class="fa fa-refresh fa-spin fa-5x"></i><span class="sr-only">Loading...</span>');
	var remove = $(this).find('span').text();
    $.ajax({
        url: "/admin/sales/ajax_",
        type: 'GET',
        data: 'remove='+ remove,
        cache: false,
        success: function (data) {
            $('.details').hide().html(data).slideDown();
        }
    });
});
