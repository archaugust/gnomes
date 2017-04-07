$(function() {
	
$("#appointment_new").change(function() {
	if ($(this).val() == 'Yes') {
		$('#patient-new').show('slow');
		$('.returning').attr('required','required');
	}
	else {
		$('#patient-new').hide('slow');
		$('.returning').removeAttr('required');
	}
	if ($(this).val() != '') {
		$('#contact-select').show('slow');
		$('#appointment_email');
	}
});


$("input[name='appointment[contact][]']").change(function() {
	if ($("input[name='appointment[contact][]']:checked").length > 0) 
		$('#booking-details').show('slow');
	else {
		$('#booking-details').hide('slow');
		$('#submit').hide('slow');
	}
});

$("#appointment_appointment").change(function() {
	switch ($(this).val()) {
		case 'Check Up':
			$('#dentist').show('slow');
			$('#referral').hide('slow');
			$('#referral-self').hide('slow');
			$('#referral-other').hide('slow');
			$('#referral-other-cds').hide('slow');
			$('#appointment-other').hide('slow');
			break;
		case 'Consultation':
			$('#referral').show('slow');
			$('#appointment-other').hide('slow');
			$('#dentist').hide('slow');
			break;
		case 'Other':
			$('#appointment-other').show('slow');
			$('#referral').show('slow');
			$('#dentist').hide('slow');
			break;
		default:
			$('#submit').hide('slow');
	}
});

$('#appointment_referral').change(function() {
	switch ($(this).val()) {
		case 'Referred':
			$('#referral-other').show('slow');
			$('#referral-self').hide('slow');
			break;
		case 'Self-referred':
			$('#referral-self').show('slow');
			$('#dentist').show('slow');
			$('#referral-other').hide('slow');
			$('#referral-other-cds').hide('slow');
			break;
	}
});

$('#appointment_referred_by').change(function() {
	if ($(this).val() == 'The Community Dental Service')
		$('#referral-other-cds').show('slow');
	else
		$('#referral-other-cds').hide('slow');

	$('#dentist').show('slow');
});
$('#appointment_appointment_other').removeAttr("required");
$('#appointment_appointment_other').change(function() {
	if ($('#appointment_appointment').val() == 'Other')
		$('#referral').show('slow');
});

$('#appointment_dentist').change(function() {
	$('#schedule').show('slow');
});

function showSubmit() {
	if ($("input[name='appointment[schedule_days][]']:checked").length > 0 && $("input[name='appointment[schedule_time][]']:checked").length > 0) {
		$('#comments').show('slow');
		$('#submit').show('slow');
	}
	else {
		$('#comments').hide('slow');
		$('#submit').hide('slow');
	}
}
$("input[name='appointment[schedule_days][]']").change(function() {
	showSubmit();
});
$("input[name='appointment[schedule_time][]']").change(function() {
	showSubmit();
});

$( "#appointment_dob" ).datepicker({
    changeMonth: true,
    changeYear: true,
    yearRange: "-40:+0",
	dateFormat: 'd MM, yy',
});

$('#form_book').submit(function(event) {
	var data = $('#form_book').serialize();
    $('body,html').animate({
        scrollTop : $('#bookDiv').offset().top - 400
    }, 500);
    $('#bookDiv').html('<div class="col-md-12"><i class="fa fa-refresh fa-spin fa-5x red"></i><span class="sr-only">Loading...</span></div>');
    $.ajax({
        url: "/book",
        data: data,
        type: "POST",
        success: function (data) {
            $('#bookDiv').html(data);
        }
    })
    event.preventDefault();
    
});

});
