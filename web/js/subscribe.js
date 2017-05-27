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
			$('#messageDiv').removeClass("u-hidden").html('Please enter a valid email.').fadeIn();
	}
	else {
		$('#messageDiv').removeClass("u-hidden").html('Please complete all info.').fadeIn();
	}
	grecaptcha.reset(recaptcha_sub);
}

$('#subscribeForm').submit(function(event) {
	var data = $(this).serialize();
    var recaptcha = grecaptcha.getResponse(recaptcha_sub);
    $("#recaptcha_sub").html('<i class="fa fa-refresh fa-spin"></i><span class="sr-only">Loading...</span>');
    $.ajax({
        url: "/subscribe-process",
        data: data+"&g-recaptcha-response="+recaptcha,
        type: "POST",
        success: function (data) {
            if (data != 'error') {
                $('#recaptcha_sub').prop('disabled','disabled');
            	$('#messageDiv').hide().removeClass("u-hidden").html(data).fadeIn();
            } 
            else {
            	$('#messageDiv').removeClass("u-hidden").html('Robot verification failed, please try again.').fadeIn();
            	grecaptcha.reset(recaptcha_sub);
            }
            $("#recaptcha_sub").html('GO');
        }
    });
    event.preventDefault();
});

var recaptcha_sub;
var onloadCallback = function() {
	recaptcha_sub = grecaptcha.render('recaptcha_sub', {
	      'sitekey' : '6Lc0bSAUAAAAAGndHIe00rz1MW9DvkBAtwGbjQJw',
	      'callback' : onSubmit
	    });
};
