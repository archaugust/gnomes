function initMap() { 
	var dentist = new google.maps.LatLng(-43.510985, 172.621110);
	
	var map = new google.maps.Map(document.getElementById('map'), {
	    zoom: 16,
	    center: dentist,
	    scrollwheel: false,
	    styles: [{"featureType":"all","elementType":"all","stylers":[{"saturation":-100},{"gamma":1}]}]      
    });
    var marker = new google.maps.Marker({
        position: dentist,
        map: map,
    });
}

$(window).scroll(function() {
    if ($(this).scrollTop() >= 50) {
        $('#return-to-top').fadeIn(200);
    } else {
        $('#return-to-top').fadeOut(200);
    }
});

$('.find-us').click(function() {
    $('body,html').animate({
        scrollTop : $('#find-us').offset().top
    }, 500);
    $('#menu-modal').modal('hide'); 
});

$('#return-to-top').click(function() {
    $('body,html').animate({
        scrollTop : 0
    }, 500);
});

$(window).load(function() {
    jQuery.fn.verticalAlign = function ()
	{
	    return this
        .css("margin-top",($(this).parent().height() - $(this).height())/2 + 'px' )
	};

	function valign() {
		$('.caption-book').verticalAlign();
	}

	valign();

	$(window).resize(function(){ 
		valign(); 
	});

	$('#page').click(function() {
		$("#menu-mobile").removeClass('in');
	});

	$('#main-menu').affix({
	    offset: {
	        top: 135
	    }
	});

	$('#menu-mobile').affix({
	    offset: {
	        top: 135
	    }
	});
});

