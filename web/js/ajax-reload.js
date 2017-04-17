$('#vendReload').click(function(){
	if (typeof get_id !== 'undefined') {
		var data = "mode="+ mode +"&get_id="+ get_id;
		var url = "/admin/"+ (mode.replace("_", "-")) +"/"+ get_id +"/ajax_";
	}
	else {
		var data = "mode="+ mode;
		var url = "/admin/"+ (mode.replace("_", "-")) +"/ajax_";
	}
	console.log(url)
	$('#vendConfirm').html('<i class="fa fa-refresh fa-spin"></i><span class="sr-only">Loading...</span>');
    $.ajax({
        url: "/admin/vend-reload",
        data: data,
        success: function (data) {
            $('#vendConfirm').html(data).attr('data-toggle','').attr('disabled','disabled');
	    	$('.details').html('<i class="fa fa-refresh fa-spin fa-5x"></i><span class="sr-only">Loading...</span>');
    	    $.ajax({
    	        url: url,
    	        cache: false,
    	        success: function (data) {
    	            $('.details').hide().html(data).slideDown();
    	        }
    	    });
        }
    });
});