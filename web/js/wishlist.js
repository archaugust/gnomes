$(function(){
	$('.toggle-wishlist').click(function (){
		var e = $(this), 
			mode,
			handle = e.closest('.o-product').find('input').val();
		
		if (e.hasClass('active')) {
			mode = 'remove';
			e.removeClass('active').prop('title', 'Add to Wishlist');
		}
		else {
			mode = 'add';
			e.addClass('active').prop('title', 'Remove from Wishlist');
		}
		console.log(e.prop('title'));
	    $.ajax({
	        url: "/wishlist-toggle",
	        data: 'mode='+ mode +'&handle='+ handle,
	        type: "POST",
	        cache: false,
	        success: function (data) {
	    	    e.notify(data,{style:'default', position: 'top center'});
	    	    setTimeout(reloadWishlist, 1000);
	        }
	    });
	});
});