function validateEmail(emailAddress) {
    var pattern = new RegExp(/^(("[\w-+\s]+")|([\w-+]+(?:\.[\w-+]+)*)|("[\w-+\s]+")([\w-+]+(?:\.[\w-+]+)*))(@((?:[\w-+]+\.)*\w[\w-+]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][\d]\.|1[\d]{2}\.|[\d]{1,2}\.))((25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\.){2}(25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\]?$)/i);
    return pattern.test(emailAddress);
}

function onSubmit(token) {
	var emptyFields = $('#registerForm input[required]').filter(function() {
	    return $(this).val() === "";
	}).length;

	if (emptyFields === 0) {
		if (validateEmail($('#fos_user_registration_form_email').val()))
			$('#registerForm').trigger('submit');
		else
			$('#messageDiv').html('Please enter a valid email.');
	}
	else {
		$('#messageDiv').html('Please complete all required info.');
	}
	grecaptcha.reset(recaptcha1);
}

$(function(){
	$('label').each(function(){
		$(this).html($(this).html().replace('*', '<span class="u-blue">*</span>'));
	});
});