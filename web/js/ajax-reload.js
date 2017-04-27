$('#vendContinue').click(function(){
	$('#vendReload').trigger('click');
});

$('#vendReload').click(function(){
	if (typeof get_id !== 'undefined') {
		var data = "mode="+ mode +"&get_id="+ get_id;
		var url = "/admin/"+ (mode.replace("_", "-")) +"/"+ get_id +"/ajax_";
	}
	else {
		var data = "mode="+ mode;
		var url = "/admin/"+ (mode.replace("_", "-")) +"/ajax_";
	}
	
	if ($('#vend_page').length > 0) {
		data = data + "&page=" + $('#vend_page').val();
	}
	
	$('#vendConfirm').html('<i class="fa fa-refresh fa-spin"></i><span class="sr-only">Loading...</span>');
	$('#vendContinue').addClass('hidden');
    $.ajax({
        url: "/admin/vend-reload",
        data: data,
        cache: false,
        success: function (data) {
        	var data = data.split('|'); 
        	if (data[0] == parseInt(data[0])) {
	        	$('#vend_page').val(data[0]);
	        	$('#vendContinue').removeClass('hidden');
	        	$('#vendConfirm').html(data[0] + ' of '+ data[1] + ' pages loaded').attr('data-toggle','').attr('disabled','disabled');
        	}
        	else {
        		$('#vendConfirm').html(data).attr('data-toggle','').attr('disabled','disabled');
    	    	$('.details').html('<i class="fa fa-refresh fa-spin fa-5x"></i><span class="sr-only">Loading...</span>');
        	    $.ajax({
        	        url: url,
        	        cache: false,
        	        success: function (data) {
       	        		$('#details').hide().html(data).slideDown();
        	        }
        	    });
        	}
        }
    });
});