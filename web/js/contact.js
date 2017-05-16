function validateEmail(emailAddress) {
    var pattern = new RegExp(/^(("[\w-+\s]+")|([\w-+]+(?:\.[\w-+]+)*)|("[\w-+\s]+")([\w-+]+(?:\.[\w-+]+)*))(@((?:[\w-+]+\.)*\w[\w-+]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][\d]\.|1[\d]{2}\.|[\d]{1,2}\.))((25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\.){2}(25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\]?$)/i);
    return pattern.test(emailAddress);
}

function onSubmit(token) {
	var emptyFields = $('#subscribeForm input[required]').filter(function() {
	    return $(this).val() === "";
	}).length;

	if (emptyFields === 0) {
		if (validateEmail($('#email').val()))
			$('#subscribeForm').trigger('submit');
		else
			$('#messageDiv').html('Please enter a valid email.');
	}
	else {
		$('#messageDiv').html('Please complete all info.');
	}
	grecaptcha.reset(recaptcha1);
}

function onContactSubmit(token) {
	var emptyFields = $('#contactForm input[required], #formContact textarea[required]').filter(function() {
	    return $(this).val() === "";
	}).length;

	if (emptyFields == 0) {
		if (validateEmail($('#contact_email').val()))
			$('#contactForm').trigger('submit');
		else
			$('#contactDiv').html('Please enter a valid email.');
	}
	else {
		$('#contactDiv').html('Please complete all info.');
	}

	grecaptcha.reset(recaptcha2);
}

$('#subscribeForm').submit(function(event) {
	var data = $(this).serialize();
    var recaptcha = grecaptcha.getResponse(recaptcha1);
    $.ajax({
        url: "/subscribe-process",
        data: data+"&g-recaptcha-response="+recaptcha,
        type: "POST",
        success: function (data) {
            if (data != 'error') {
                $('#recaptcha1').prop('disabled','disabled');
            	$('#messageDiv').hide().html(data).fadeIn();
            } 
            else {
            	$('#messageDiv').html('Robot verification failed, please try again.');
            	grecaptcha.reset(recaptcha1);
            }
        }
    });
    event.preventDefault();
});

$('#contactForm').submit(function(event) {
	var data = $(this).serialize();
    var recaptcha = grecaptcha.getResponse(recaptcha2);
    $.ajax({
        url: "/pages/contact",
        data: data+"&g-recaptcha-response="+recaptcha,
        type: "POST",
        success: function (data) {
            if (data != 'error') {
                $('#contact_save').html('Message Sent').prop('disabled','disabled');
            	$('#contactDiv').hide().html('<h3 class="u-blue u-margin-none">Thanks for that!</h3><div class="u-ls-20">Your info is now blasting through cyberspace en route to our inbox. We’ll take a look and be right in touch!</div>').fadeIn();
            } 
            else {
            	$('#contactDiv').html('Robot verification failed, please try again.');
            	grecaptcha.reset(recaptcha2);
            }
        }
    });
    event.preventDefault();
});

function initMap() { 
	var gnomes = new google.maps.LatLng(-43.489878, 172.114674);
	var map = new google.maps.Map(document.getElementById('map'), {
	    zoom: 11,
	    center: gnomes,
	    scrollwheel: false,
	    styles: [{"featureType":"all","elementType":"all","stylers":[{"saturation":-100},{"gamma":1}]}]      
    });
    var marker = new google.maps.Marker({
        position: gnomes,
        map: map,
    });
}

$(function(){
	$('label').each(function(){
		$(this).html($(this).html().replace('*', '<span class="u-blue">*</span>'));
	});
});