$('.toggle-active').click(function (){
	var e = $(this);
	var msg, value;
	var id = e.closest('tr').find('input').val();
	if (e.find('i').hasClass('fa-star')) {
		value = 0;
		e.find('i').removeClass('fa-star').addClass('fa-star-o');
	}
	else {
		value = 1;
		e.find('i').removeClass('fa-star-o').addClass('fa-star');
	}
	
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
	var e = $(this);
	var msg, value;
	var id = e.closest('tr').find('input').val();
	if (e.find('i').hasClass('fa-star')) {
		value = 0;
		e.find('i').removeClass('fa-star').addClass('fa-star-o');
		$('#warning').hide().removeClass('hidden').slideDown();
	}
	else {
		value = 1;
		$('.toggle-default').each(function(){
			$(this).find('i').removeClass('fa-star').addClass('fa-star-o');
		});
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
