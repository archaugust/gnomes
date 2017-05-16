function toggleStar(e) {
	var value;
	
	if (e.find('i').hasClass('fa-star')) {
		value = 0;
		e.find('i').removeClass('fa-star').addClass('fa-star-o');
	}
	else {
		value = 1;
		e.find('i').removeClass('fa-star-o').addClass('fa-star');
	}
	return value;
}

$('.toggle-active').click(function (){
	var e = $(this), 
		value = toggleStar(e),
		id = e.closest('tr').find('input').val();
	
    $.ajax({
        url: "/admin/vend-toggle",
        data: 'mode='+ mode +'&action=active&id='+ id +'&value='+ value,
        type: "POST",
        cache: false,
        success: function (data) {
    	    $.notify(data,{style:'default'});
        }
    });
});

$('.toggle-default').click(function (){
	var e = $(this),
		value,
		id = e.closest('tr').find('input').val();
	
	if (e.find('i').hasClass('fa-star')) {
		value = 0;
		e.find('i').removeClass('fa-star').addClass('fa-star-o');
		$('#warning').hide().removeClass('hidden').slideDown();
	}
	else {
		value = 1;
		$('.toggle-default i').removeClass('fa-star').addClass('fa-star-o');
		e.find('i').removeClass('fa-star-o').addClass('fa-star');
		$('#warning').slideUp();
	}
	
    $.ajax({
        url: "/admin/vend-toggle",
        data: 'mode='+ mode +'&action=default&id='+ id +'&value='+ value,
        type: "POST",
        cache: false,
        success: function (data) {
    	    $.notify(data,{style:'default'});
        }
    });
});

$('.toggle-pre_sell').click(function (){
	var e = $(this),
		value = toggleStar(e),
		id = e.closest('tr').find('input').val();
	if (value == 1)
		e.closest('tr').find('.pre-sell-text').removeAttr('disabled');
	else
		e.closest('tr').find('.pre-sell-text').attr('disabled','disabled');
    $.ajax({
        url: "/admin/vend-toggle",
        data: 'mode='+ mode +'&action=pre_sell&id='+ id +'&value='+ value,
        type: "POST",
        cache: false,
        success: function (data) {
    	    $.notify(data,{style:'default'});
        }
    });
});

$('.pre-sell-text').change(function (){
	var e = $(this),
		id = e.closest('tr').find('input').val(),
		text_id = e.val();
    $.ajax({
        url: "/admin/product-change-pre-sell-text",
        data: 'id='+ id +'&text_id='+ text_id,
        type: "POST",
        cache: false,
        success: function (data) {
    	    $.notify(data,{style:'default'});
        }
    });
});
